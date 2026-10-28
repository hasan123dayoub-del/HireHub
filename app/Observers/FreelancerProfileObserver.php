<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;
use App\Models\FreelancerProfile;

class FreelancerProfileObserver
{
    public function updated(FreelancerProfile $profile)
    {
        if ($profile->wasChanged('availability')) {
            Cache::tags(['freelancers'])->flush();
        }
    }
}