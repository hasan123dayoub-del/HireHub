<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'code'
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
