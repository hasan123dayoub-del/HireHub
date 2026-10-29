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

    // Matches the column default, so a new proposal reports its status before it is reloaded.
    protected $attributes = ['status' => 'pending'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function freelancer()
    {
        // The freelancer who sent the proposal is stored in user_id.
        return $this->belongsTo(User::class, 'user_id');
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
