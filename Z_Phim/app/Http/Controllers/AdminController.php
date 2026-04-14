<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Constructor removed for Laravel 11 compatibility

    public function dashboard()
    {
        $stats = [
            'movies' => Movie::count(),
            'showtimes' => Showtime::count(),
            'bookings' => Booking::count(),
            'total_revenue' => Booking::whereIn('status', ['confirmed', 'paid'])->sum('total_amount')
        ];

        $recentMovies = Movie::latest('created_at')->take(5)->get();
        $recentBookings = Booking::with(['user', 'showtime.movie'])->latest('created_at')->take(5)->get();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push([
                'key' => $month->format('Y-m'),
                'label' => $month->format('M'),
            ]);
        }

        $monthlyRevenue = Booking::whereIn('status', ['confirmed', 'paid'])
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->get()
            ->groupBy(fn ($booking) => $booking->created_at->format('Y-m'))
            ->map(fn ($items) => $items->sum('total_amount'));

        $revenueTrend = $months->map(fn ($month) => [
            'label' => $month['label'],
            'value' => $monthlyRevenue->get($month['key'], 0),
        ])->all();

        $statusCounts = Booking::select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return view('admin.dashboard', compact('stats', 'recentMovies', 'recentBookings', 'revenueTrend', 'statusCounts'));
    }

    // Movies CRUD
    public function indexMovies()
    {
        $movies = Movie::with('genres')->paginate(10);
        return view('admin.movies.index', compact('movies'));
    }

    public function createMovie()
    {
        return view('admin.movies.create');
    }

    public function storeMovie(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,jfif,bmp|max:5120',
            'trailer' => 'nullable|url',
            'genre_ids' => 'array',
            'genre_ids.*' => 'exists:genres,id'
        ]);

        $data = $request->only(['title', 'description', 'duration', 'release_date', 'trailer']);

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie = Movie::create($data);
        $movie->genres()->sync($request->genre_ids ?? []);

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully');
    }

    public function showMovie(Movie $movie)
    {
        return view('admin.movies.show', compact('movie'));
    }

    public function editMovie(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function updateMovie(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,jfif,bmp|max:5120',
            'trailer' => 'nullable|url',
            'genre_ids' => 'array',
            'genre_ids.*' => 'exists:genres,id'
        ]);

        $data = $request->only(['title', 'description', 'duration', 'release_date', 'trailer']);

        if ($request->hasFile('poster')) {
            if ($movie->poster && !str_starts_with($movie->poster, 'http') && Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }

            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie->update($data);
        $movie->genres()->sync($request->genre_ids ?? []);

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully');
    }

    public function destroyMovie(Movie $movie)
    {
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully');
    }

    // Showtimes CRUD
    public function indexShowtimes()
    {
        $showtimes = Showtime::with(['movie', 'room.cinema'])->paginate(10);
        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function createShowtime()
    {
        $movies = Movie::all();
        $cinemas = \App\Models\Cinema::with('rooms')->get();
        return view('admin.showtimes.create', compact('movies', 'cinemas'));
    }

    public function storeShowtime(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0'
        ]);

        Showtime::create($request->all());

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime created successfully');
    }

    public function showShowtime(Showtime $showtime)
    {
        return view('admin.showtimes.show', compact('showtime'));
    }

    public function editShowtime(Showtime $showtime)
    {
        $movies = Movie::all();
        $cinemas = \App\Models\Cinema::with('rooms')->get();
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'cinemas'));
    }

    public function updateShowtime(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'price' => 'required|numeric|min:0'
        ]);

        $showtime->update($request->all());

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime updated successfully');
    }

    public function destroyShowtime(Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime deleted successfully');
    }

    // Bookings CRUD
    public function indexBookings()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.room.cinema', 'seats'])->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'seats']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function editBooking(Booking $booking)
    {
        $booking->load(['user', 'showtime.movie', 'showtime.room.cinema', 'seats']);
        return view('admin.bookings.edit', compact('booking'));
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,refunded'
        ]);

        $booking->update($request->only(['status']));

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully');
    }

    public function destroyBooking(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully');
    }

    public function indexReviews()
    {
        $reviews = Review::with(['user', 'movie'])->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateReview(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update($request->only('status'));

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully');
    }

    public function destroyReview(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully');
    }

    public function indexPayments()
    {
        $payments = Payment::with(['booking.user', 'booking.showtime.movie'])->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.showtime.movie']);
        return view('admin.payments.show', compact('payment'));
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment->update($request->only('status'));

        return redirect()->route('admin.payments.index')->with('success', 'Payment status updated successfully');
    }

    public function destroyPayment(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully');
    }
}
