<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'budget_type',
        'budget_amount',
        'delivery_date',
        'status'
    ];

    protected $with = ['tags'];

    protected $casts = [
        'delivery_date' => 'datetime',
        'budget_amount' => 'decimal:2',
    ];
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'project_tag',
            'project_id',
            'tag_id'
        )->withTimestamps();
    }


    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function review()
    {
        return $this->morphOne(Review::class, 'reviewable');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }
    /**
     * Requirement: Dynamic Budget display and Deadline counter.
     */
    protected function budgetDisplay(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->budget_type === 'fixed'
                ? number_format((float) $this->budget_amount, 0) . " USD"
                : "$" . number_format((float) $this->budget_amount, 2) . "/hr",
        );
    }

    protected function deadlineStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->delivery_date) return 'No deadline';

                $days = now()->diffInDays($this->delivery_date, false);
                return $days < 0 ? 'Expired' : $days . " days left";
            },
        );
    }

    // --- Scopes ---

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function scopeBudgetAbove($query, $amount)
    {
        return $query->where('budget_amount', '>=', $amount);
    }

    public function scopePublishedThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
    }

    protected $appends = ['budget_display', 'deadline_status'];
}
