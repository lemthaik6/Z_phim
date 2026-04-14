<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'row',
        'number',
        'type',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_details')->withPivot('price');
    }

    public function isBookedForShowtime($showtimeId): bool
    {
        return $this->bookings()->where('showtime_id', $showtimeId)->exists();
    }

    // Accessors for compatibility with API/views
    public function getRowNumberAttribute()
    {
        return $this->row;
    }

    public function getColumnNumberAttribute()
    {
        return $this->number;
    }
}