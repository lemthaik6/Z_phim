<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function checkout(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        return view('payments.checkout', compact('booking'));
    }

    public function complete(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)->with('error', 'Đơn hàng này không thể thanh toán.');
        }

        $request->validate([
            'payment_method' => 'required|in:online,card,cash',
        ]);

        $paymentMethod = $request->input('payment_method');

        DB::transaction(function () use ($booking, $paymentMethod) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
            ]);

            $booking->update([
                'status' => 'paid',
                'paid_at' => now(),
                'locked_until' => null,
            ]);
        });

        $booking->refresh();

        return redirect()->route('bookings.show', $booking)->with('success', 'Thanh toán thành công bằng ' . ucfirst($paymentMethod) . '.');
    }
}
