<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\BookingDetail;
use App\Models\BookingComboItem;
use App\Models\Payment;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'showtime_id',
        'total_amount',
        'status',
        'locked_until',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'locked_until' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showtime(): BelongsTo
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats(): BelongsToMany
    {
        return $this->belongsToMany(Seat::class, 'booking_details')->withPivot('price');
    }

    // Alias for booking details (for backward compatibility)
    public function bookingDetails(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function comboItems(): HasMany
    {
        return $this->hasMany(BookingComboItem::class);
    }

    public function getSeatTotalAttribute(): float
    {
        return $this->bookingDetails->sum('price');
    }

    public function getComboTotalAttribute(): float
    {
        return $this->comboItems->sum('total_price');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}