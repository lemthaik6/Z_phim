<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
{
    private $showtimeId;

    public function __construct($resource, $showtimeId = null)
    {
        parent::__construct($resource);
        $this->showtimeId = $showtimeId;
    }

    public function toArray(Request $request): array
    {
        // Determine if seat is occupied or locked
        $isOccupied = $this->showtimeId ? $this->isBookedForShowtime($this->showtimeId) : false;
        
        // Check for seat locks
        $lockedUntil = null;
        if ($this->showtimeId) {
            $lockedBooking = $this->bookings()
                ->where('showtime_id', $this->showtimeId)
                ->where('locked_until', '>', now())
                ->where('status', '!=', 'cancelled')
                ->orderByDesc('locked_until')
                ->first();
            if ($lockedBooking) {
                $lockedUntil = $lockedBooking->locked_until;
            }
        }

        return [
            'id' => $this->id,
            'row' => $this->row,
            'row_number' => $this->row,
            'number' => $this->number,
            'column_number' => $this->number,
            'type' => $this->type,
            'is_occupied' => $isOccupied,
            'locked_until' => $lockedUntil,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}