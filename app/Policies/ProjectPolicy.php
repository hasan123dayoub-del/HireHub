<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return User::clients()->verified()
            ->where('id', $user->id)
            ->exists();
    }

    public function update(User $user, Project $project): bool
    {
        $isVerifiedClient = User::clients()->verified()->where('id', $user->id)->exists();

        return $isVerifiedClient
            && $project->user_id === $user->id
            && $project->status === 'open';
    }
}
