<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentGateways\MoMoGateway;
use App\Services\PaymentGateways\VNPayGateway;
use App\Services\SeatLockManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function store(PaymentRequest $request)
    {
        $booking = Booking::findOrFail($request->booking_id);

        // Check authorization
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if seats are still locked
        if (!SeatLockManager::isLocked($booking)) {
            return response()->json(['message' => 'Seat lock has expired. Please create a new booking.'], 410);
        }

        // Check if booking is pending
        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'Booking cannot be paid'], 400);
        }

        // Check if payment already exists
        $existingPayment = Payment::where('booking_id', $booking->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return response()->json(['message' => 'Payment already completed for this booking'], 400);
        }

        // Validate amount matches booking total
        if ((float)$request->amount !== (float)$booking->total_amount) {
            return response()->json(['message' => 'Payment amount does not match booking total'], 400);
        }

        // Process payment through gateway
        $paymentResult = $this->processPayment($request, $booking);

        if ($paymentResult['success'] === false) {
            return response()->json($paymentResult, 400);
        }

        $paidAt = Carbon::now();

        // Update booking on successful payment
        DB::transaction(function () use ($request, $booking, $paymentResult, $paidAt) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);

            // Update booking status and unlock seats
            $booking->update([
                'status' => 'paid',
                'paid_at' => $paidAt,
                'locked_until' => null,
            ]);
        });

        $booking->refresh();

        return response()->json([
            'message' => 'Payment processed successfully',
            'booking_id' => $booking->id,
            'status' => 'paid',
            'paid_at' => $booking->paid_at,
        ], 201);
    }

    public function show(Payment $payment)
    {
        if ($payment->booking->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new PaymentResource($payment->load('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if booking can be cancelled
        if (!in_array($booking->status, ['pending', 'paid'])) {
            return response()->json(['message' => 'Booking cannot be cancelled'], 400);
        }

        DB::transaction(function () use ($booking) {
            // Update booking status
            $booking->update(['status' => 'cancelled', 'locked_until' => null]);

            // Refund if payment exists
            $payment = $booking->payments()->where('status', 'completed')->first();
            if ($payment) {
                $payment->update(['status' => 'refunded']);
            }
        });

        return response()->json(['message' => 'Booking cancelled successfully']);
    }

    /**
     * Gateway callback from MoMo
     */
    public function momoCallback(Request $request)
    {
        return $this->handleGatewayCallback($request, 'momo');
    }

    /**
     * Gateway callback from VNPay
     */
    public function vnpayCallback(Request $request)
    {
        return $this->handleGatewayCallback($request, 'vnpay');
    }

    private function handleGatewayCallback(Request $request, string $gateway)
    {
        $bookingId = $request->input('booking_id');
        $booking = Booking::findOrFail($bookingId);

        $gatewayService = $this->makeGateway($gateway);
        $result = $gatewayService->verify($request->all());

        $payment = Payment::where('booking_id', $booking->id)->where('status', 'pending')->latest('created_at')->first();
        if (!$payment) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Không tìm thấy giao dịch chờ thanh toán.');
        }

        if ($result['success'] === true) {
            DB::transaction(function () use ($booking, $payment) {
                $payment->update(['status' => 'completed']);
                $booking->update([
                    'status' => 'paid',
                    'paid_at' => Carbon::now(),
                    'locked_until' => null,
                ]);
            });

            return redirect()->route('bookings.show', $booking)->with('success', 'Thanh toán thành công qua ' . strtoupper($gateway) . '.');
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('bookings.show', $booking)->with('error', $result['message'] ?? 'Thanh toán thất bại.');
    }

    private function makeGateway(string $gateway)
    {
        if ($gateway === 'vnpay') {
            return new VNPayGateway();
        }

        return new MoMoGateway();
    }

    /**
     * Process payment through selected gateway
     * Can be simulated or integrated with VNPay/MoMo
     */
    private function processPayment(PaymentRequest $request, Booking $booking): array
    {
        $paymentMethod = $request->payment_method;

        switch ($paymentMethod) {
            case 'cash':
                return $this->processCashPayment($booking);
            case 'card':
                return $this->processCardPayment($request, $booking);
            case 'online':
                return $this->processOnlinePayment($request, $booking);
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }

    /**
     * Simulate cash payment (always success)
     */
    private function processCashPayment(Booking $booking): array
    {
        return ['success' => true];
    }

    /**
     * Simulate card payment
     */
    private function processCardPayment(PaymentRequest $request, Booking $booking): array
    {
        // Simulate 90% success rate
        $success = rand(0, 100) <= 90;

        return [
            'success' => $success,
            'message' => $success ? 'Card payment successful' : 'Card payment declined',
        ];
    }

    /**
     * Online payment (VNPay/MoMo placeholder)
     * This can be extended with actual gateway integration
     */
    private function processOnlinePayment(PaymentRequest $request, Booking $booking): array
    {
        // Placeholder for VNPay or MoMo integration
        // In production, this would redirect to payment gateway
        
        // For now, simulate success
        return ['success' => true];
    }
}