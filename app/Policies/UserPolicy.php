<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewStats(User $user)
    {
        return $user->role === 'admin';
    }
}
