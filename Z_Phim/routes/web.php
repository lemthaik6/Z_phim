<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MovieController as WebMovieController;
use App\Http\Controllers\BookingController as WebBookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController as WebPaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\API\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/admin/dashboard', '/admin');

Route::middleware('auth')->group(function () {
    // Movies
    Route::get('/movies', function () {
        return view('movies.index');
    })->name('movies.index');
    Route::get('/movies/{movie}', [WebMovieController::class, 'show'])->name('movies.show');
    Route::get('/movies/{movie}/showtimes/{showtime}/seats', [WebMovieController::class, 'seats'])->name('movies.seats');

    // Bookings
    Route::get('/bookings', [WebBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [WebBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [WebBookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews
    Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('movies.reviews.store');

    // Payments
    Route::get('/payments/{booking}/checkout', [WebPaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/{booking}/complete', [WebPaymentController::class, 'complete'])->name('payments.complete');

    // Admin routes
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            $stats = [
                'movies' => \App\Models\Movie::count(),
                'showtimes' => \App\Models\Showtime::count(),
                'bookings' => \App\Models\Booking::count(),
                'total_revenue' => \App\Models\Payment::where('status', 'completed')->sum('amount'),
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('dashboard');
        Route::get('/movies', [AdminController::class, 'indexMovies'])->name('movies.index');
        Route::get('/movies/create', [AdminController::class, 'createMovie'])->name('movies.create');
        Route::post('/movies', [AdminController::class, 'storeMovie'])->name('movies.store');
        Route::get('/movies/{movie}', [AdminController::class, 'showMovie'])->name('movies.show');
        Route::get('/movies/{movie}/edit', [AdminController::class, 'editMovie'])->name('movies.edit');
        Route::put('/movies/{movie}', [AdminController::class, 'updateMovie'])->name('movies.update');
        Route::delete('/movies/{movie}', [AdminController::class, 'destroyMovie'])->name('movies.destroy');

        Route::get('/showtimes', [AdminController::class, 'indexShowtimes'])->name('showtimes.index');
        Route::get('/showtimes/create', [AdminController::class, 'createShowtime'])->name('showtimes.create');
        Route::post('/showtimes', [AdminController::class, 'storeShowtime'])->name('showtimes.store');
        Route::get('/showtimes/{showtime}', [AdminController::class, 'showShowtime'])->name('showtimes.show');
        Route::get('/showtimes/{showtime}/edit', [AdminController::class, 'editShowtime'])->name('showtimes.edit');
        Route::put('/showtimes/{showtime}', [AdminController::class, 'updateShowtime'])->name('showtimes.update');
        Route::delete('/showtimes/{showtime}', [AdminController::class, 'destroyShowtime'])->name('showtimes.destroy');

        Route::get('/bookings', [AdminController::class, 'indexBookings'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminController::class, 'showBooking'])->name('bookings.show');
        Route::get('/bookings/{booking}/edit', [AdminController::class, 'editBooking'])->name('bookings.edit');
        Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [AdminController::class, 'destroyBooking'])->name('bookings.destroy');

        Route::get('/reviews', [AdminController::class, 'indexReviews'])->name('reviews.index');
        Route::put('/reviews/{review}', [AdminController::class, 'updateReview'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');

        Route::get('/payments', [AdminController::class, 'indexPayments'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
        Route::put('/payments/{payment}', [AdminController::class, 'updatePayment'])->name('payments.update');
        Route::delete('/payments/{payment}', [AdminController::class, 'destroyPayment'])->name('payments.destroy');
    });
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
