<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name'];

    public function freelancers()
    {
        return $this->belongsToMany(User::class, 'freelancer_skill')
                    ->withPivot('years_of_experience');
    }
}
