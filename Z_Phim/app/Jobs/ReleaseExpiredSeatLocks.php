<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\SeatLockManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;

class ReleaseExpiredSeatLocks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find all bookings with expired locks
        $expiredBookings = Booking::where('locked_until', '<=', Carbon::now())
            ->where('status', 'pending')
            ->whereNull('paid_at')
            ->get();

        foreach ($expiredBookings as $booking) {
            SeatLockManager::unlockSeats($booking);
        }
    }
}
