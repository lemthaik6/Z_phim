<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\BookingComboItem;
use App\Models\Seat;
use App\Models\Showtime;
use App\Services\SeatLockManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(BookingRequest $request)
    {
        $showtime = Showtime::findOrFail($request->showtime_id);
        $seats = Seat::whereIn('id', $request->seat_ids)->get();

        // Check if seats are available
        foreach ($seats as $seat) {
            if ($seat->isBookedForShowtime($showtime->id)) {
                return response()->json(['message' => "Seat {$seat->row}{$seat->number} is already booked"], 409);
            }
        }

        $seatTotal = $seats->sum(function ($seat) use ($showtime) {
            return $seat->type === 'vip' ? $showtime->price * 1.5 : $showtime->price;
        });

        $comboAmount = 0;
        $availableCombos = collect(config('combos.items'))->keyBy('id');
        $comboItems = [];

        foreach ($request->input('combos', []) as $combo) {
            if (!is_array($combo) || empty($combo['id']) || empty($combo['quantity'])) {
                continue;
            }

            $quantity = intval($combo['quantity']);
            if ($quantity < 1 || !$availableCombos->has($combo['id'])) {
                continue;
            }

            $definition = $availableCombos->get($combo['id']);
            $itemTotal = $definition['price'] * $quantity;
            $comboItems[] = [
                'item_id' => $definition['id'],
                'name' => $definition['name'],
                'type' => $definition['type'],
                'quantity' => $quantity,
                'unit_price' => $definition['price'],
                'total_price' => $itemTotal,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $comboAmount += $itemTotal;
        }

        $totalAmount = $seatTotal + $comboAmount;
        $booking = null;

        DB::transaction(function () use ($request, $showtime, $seats, $seatTotal, $comboAmount, &$booking, $comboItems) {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'showtime_id' => $showtime->id,
                'total_amount' => $seatTotal + $comboAmount,
                'status' => 'pending',
            ]);

            foreach ($seats as $seat) {
                $price = $seat->type === 'vip' ? $showtime->price * 1.5 : $showtime->price;
                $booking->seats()->attach($seat->id, ['price' => $price]);
            }

            if (!empty($comboItems)) {
                foreach ($comboItems as &$item) {
                    $item['booking_id'] = $booking->id;
                }
                BookingComboItem::insert($comboItems);
            }

            // Lock seats for 5 minutes
            SeatLockManager::lockSeats($booking);
        });

        $timeRemaining = SeatLockManager::getTimeRemaining($booking);

        return response()->json([
            'message' => 'Booking created successfully, please proceed to payment',
            'booking_id' => $booking->id,
            'total_amount' => $booking->total_amount,
            'locked_until' => $booking->locked_until,
            'time_remaining_seconds' => $timeRemaining,
        ], 201);
    }

    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['showtime.movie', 'showtime.room.cinema', 'seats', 'payments', 'comboItems'])
            ->latest('created_at')
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->load(['showtime.movie', 'showtime.room.cinema', 'seats', 'payments', 'comboItems']);

        return new BookingResource($booking);
    }

    public function cancel(Booking $booking)
    {
        // Check authorization
        if ($booking->user_id !== request()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if booking can be cancelled
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['message' => 'Booking cannot be cancelled'], 400);
        }

        DB::transaction(function () use ($booking) {
            // Update booking status
            $booking->update(['status' => 'cancelled']);

            // Refund if payment exists
            $payment = $booking->payments()->where('status', 'completed')->first();
            if ($payment) {
                $payment->update(['status' => 'refunded']);
            }
        });

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}
