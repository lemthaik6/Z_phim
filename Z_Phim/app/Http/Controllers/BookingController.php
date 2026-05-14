<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingComboItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        $booking->load(['showtime.movie', 'showtime.room.cinema', 'seats', 'payments', 'payment', 'bookingDetails.seat', 'comboItems']);

        return view('bookings.show', compact('booking'));
    }

    public function addCombos(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->user()->id) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Chỉ có thể thêm combo khi đặt vé đang chờ thanh toán.');
        }

        $availableItems = collect(config('combos.items'))->keyBy('id');

        $request->validate([
            'combos' => 'required|array|min:1',
            'combos.*.id' => ['required_with:combos', 'string', Rule::in($availableItems->keys()->all())],
            'combos.*.quantity' => 'required_with:combos|integer|min:1',
        ]);

        $comboItems = [];
        $comboTotal = 0;

        foreach ($request->input('combos', []) as $combo) {
            if (!is_array($combo) || empty($combo['id']) || empty($combo['quantity'])) {
                continue;
            }

            $quantity = intval($combo['quantity']);
            if ($quantity < 1 || !$availableItems->has($combo['id'])) {
                continue;
            }

            $definition = $availableItems->get($combo['id']);
            $itemTotal = $definition['price'] * $quantity;
            $comboItems[] = [
                'booking_id' => $booking->id,
                'item_id' => $definition['id'],
                'name' => $definition['name'],
                'type' => $definition['type'],
                'quantity' => $quantity,
                'unit_price' => $definition['price'],
                'total_price' => $itemTotal,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $comboTotal += $itemTotal;
        }

        if (empty($comboItems)) {
            return redirect()->back()->with('error', 'Vui lòng chọn combo hợp lệ.');
        }

        DB::transaction(function () use ($booking, $comboItems, $comboTotal) {
            $booking->comboItems()->delete();
            BookingComboItem::insert($comboItems);

            $seatTotal = $booking->bookingDetails()->sum('price');
            $booking->update(['total_amount' => $seatTotal + $comboTotal]);
        });

        return redirect()->route('bookings.show', $booking)->with('success', 'Combo đã được thêm vào đơn hàng.');
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