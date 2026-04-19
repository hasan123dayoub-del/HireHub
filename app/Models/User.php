<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'city_id',
        'email_verified_at'
    ];

    protected $with = ['profile'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function country(): HasOneThrough
    {
        return $this->hasOneThrough(Country::class, City::class, 'id', 'id', 'city_id', 'country_id');
    }
    public function profile()
    {
        return $this->hasOne(FreelancerProfile::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'freelancer_skill',
            'user_id',
            'skill_id'
        )->withPivot('years_of_experience')
            ->withTimestamps();
    }
    /**
     * Requirement: Encrypt password automatically.
     * Logic: Using Mutator ensures encryption even if the developer forgets bcrypt() in Controller.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value),
        );
    }

    /**
     * Requirement: Full Name (Ready to use).
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => ucwords($this->name),
        );
    }

    /**
     * Requirement: Readable Join Date.
     */
    protected function joinedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => "Member since " . $this->created_at?->format('F Y') ?? 'Unknown',
        );
    }

    // --- Scopes for Filtering ---

    public function scopeFreelancers($query)
    {
        return $query->where('role', 'freelancer');
    }
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }
    // Appends to include these in API response by default
    protected $appends = ['full_name', 'joined_at'];
}
