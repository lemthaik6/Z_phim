<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class SeatLockManager
{
    const LOCK_DURATION_MINUTES = 5;

    public static function lockSeats(Booking $booking): void
    {
        $lockedUntil = Carbon::now()->addMinutes(self::LOCK_DURATION_MINUTES);
        
        $booking->update([
            'locked_until' => $lockedUntil,
            'status' => 'pending',
        ]);
    }

    public static function unlockSeats(Booking $booking): void
    {
        $booking->update([
            'locked_until' => null,
            'status' => 'cancelled',
        ]);
    }

    public static function isLocked(Booking $booking): bool
    {
        return $booking->locked_until && Carbon::now()->isBefore($booking->locked_until);
    }

    public static function getTimeRemaining(Booking $booking): ?int
    {
        if (!$booking->locked_until) {
            return null;
        }

        $remaining = Carbon::now()->diffInSeconds($booking->locked_until, false);
        
        return $remaining > 0 ? $remaining : null;
    }
}