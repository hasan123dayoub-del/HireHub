<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'country_id'];
    protected $with = ['country'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
