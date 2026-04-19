<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'float',
    ];
    public function reviewable()
    {
        $this->morphTo();
    }
}
