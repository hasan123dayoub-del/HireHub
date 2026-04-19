<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = [
        'project_id',
        'amount',
        'cover_letter',
        'delivery_days',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCheapest($query)
    {
        return $query->orderBy('amount', 'asc');
    }
    public function scopeFastest($query)
    {
        return $query->orderBy('delivery_days', 'asc');
    }
}
