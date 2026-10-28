<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Cache;

class FreelancerProfile extends Model
{
    protected $fillable = [
        'bio',
        'hourly_rate',
        'phone_number',
        'avatar',
        'availability',
        'portfolio_links'
    ];
    protected $casts = [
        'portfolio_links' => 'array', // JSON Cast
        'hourly_rate' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
    /**
     * Requirement: Uniform Phone Number format.
     */
    protected function phoneNumber(): Attribute
    {
        return Attribute::make(
            set: fn($value) => str_replace([' ', '-', '(', ')'], '', $value), // Store clean digits only
        );
    }

    /**
     * Requirement: Full Avatar URL with fallback.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->avatar
                ? asset('storage/' . $this->avatar)
                : "https://ui-avatars.com/api/?name=" . urlencode($this->user?->name ?? 'User'),
        );
    }

    /**
     * Requirement: Rating as Decimal + Stars.
     */
    protected function ratingDisplay(): Attribute
    {
        return Attribute::make(
            get: function () {
                $cached = Cache::get("freelancer_rating_{$this->user_id}");
                if ($cached !== null) {
                    return number_format($cached, 1) . " ⭐";
                }
                $avg = $this->reviews_avg_rating ?? ($this->user?->reviews()->avg('rating'));
                return $avg ? number_format($avg, 1) . " ⭐" : "No reviews yet";
            },
        );
    }

    // --- Scopes ---

    public function scopeAvailable($query)
    {
        return $query->where('availability', 'available');
    }
    public function scopeBestRated($query)
    {
        return $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
    }

    protected $appends = ['avatar_url', 'rating_display'];
}
