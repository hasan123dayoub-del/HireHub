<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FreelancerProfile;

class FreelancerProfilePolicy
{
    public function delete(User $user, FreelancerProfile $freelancerProfile): bool
    {
        return $user->id === $freelancerProfile->user_id;
    }
    public function create(User $user): bool
    {
        return User::freelancers()->verified()->where('id', $user->id)->exists();
    }
}
