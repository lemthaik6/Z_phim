<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['showtime.movie', 'showtime.room.cinema', 'seats', 'payments', 'bookingDetails.seat'])
            ->latest('created_at')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->user()->id) {
            abort(403);
        }

        $booking->load(['showtime.movie', 'showtime.room.cinema', 'seats', 'payments', 'payment', 'bookingDetails.seat']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->user()->id) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed', 'paid'])) {
            return redirect()->back()->with('error', 'Đặt chỗ không thể hủy.');
        }

        $booking->update(['status' => 'cancelled', 'locked_until' => null]);

        if ($payment = $booking->payments()->where('status', 'completed')->first()) {
            $payment->update(['status' => 'refunded']);
        }

        return redirect()->route('bookings.index')->with('success', 'Đặt chỗ đã được hủy thành công.');
    }
}