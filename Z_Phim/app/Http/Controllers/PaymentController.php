<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentGateways\MoMoGateway;
use App\Services\PaymentGateways\SimulationGateway;
use App\Services\PaymentGateways\VNPayGateway;
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

    public function startQrPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'Đơn hàng này không thể thanh toán.'], 422);
        }

        $request->validate([
            'payment_method' => 'required|in:online,card,cash',
        ]);

        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod !== 'online') {
            return response()->json(['message' => 'Chỉ phương thức thanh toán trực tuyến mới hỗ trợ cổng ngân hàng.'], 422);
        }

        $payment = $booking->payments()->where('status', 'pending')->latest('created_at')->first();
        if (!$payment) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
            ]);
        }

        $gateway = $this->makeGateway();
        $result = $gateway->process($booking->id, $booking->total_amount, [
            'payment_id' => $payment->id,
            'payment_method' => $paymentMethod,
        ]);

        if (!isset($result['success']) || $result['success'] === false || empty($result['redirect_url'])) {
            if ($payment && $payment->status === 'pending' && isset($result['message'])) {
                $payment->update(['status' => 'failed']);
            }

            return response()->json(['message' => $result['message'] ?? 'Không thể khởi tạo cổng thanh toán.'], 400);
        }

        return response()->json([
            'redirect_url' => $result['redirect_url'],
            'payment_id' => $payment->id,
        ]);
    }

    private function makeGateway()
    {
        $driver = config('payment.default', 'simulation');

        return match ($driver) {
            'vnpay' => new VNPayGateway(),
            'momo' => new MoMoGateway(),
            default => new SimulationGateway(),
        };
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
        $pendingPayment = $booking->payments()->where('status', 'pending')->latest('created_at')->first();

        DB::transaction(function () use ($booking, $paymentMethod, $pendingPayment) {
            if ($pendingPayment) {
                $pendingPayment->update([
                    'amount' => $booking->total_amount,
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                ]);
            } else {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount,
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                ]);
            }

            $booking->update([
                'status' => 'paid',
                'paid_at' => now(),
                'locked_until' => null,
            ]);
        });

        $booking->refresh();

        return redirect()->route('bookings.show', $booking)->with('success', 'Thanh toán thành công bằng ' . ucfirst($paymentMethod) . '.');
    }

    public function confirmQrPayment(Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id() || $payment->booking_id !== $booking->id) {
            abort(403);
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('bookings.show', $booking)->with('error', 'Không tìm thấy giao dịch chờ thanh toán.');
        }

        $booking->load(['showtime.movie', 'showtime.room.cinema']);

        return view('payments.confirm-qr', compact('booking', 'payment'));
    }
}
