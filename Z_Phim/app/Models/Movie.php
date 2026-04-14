<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_date',
        'duration',
        'poster',
        'trailer',
    ];

    protected $appends = ['poster_url'];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function getPosterUrlAttribute()
    {
        if (! $this->poster) {
            return null;
        }

        if (str_starts_with($this->poster, 'http')) {
            return $this->poster;
        }

        // Use asset helper to properly link to public/storage via symlink
        return asset('storage/' . $this->poster) . '?t=' . md5($this->updated_at);
    }

    public function getTrailerUrlAttribute()
    {
        return $this->trailer;
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }
}