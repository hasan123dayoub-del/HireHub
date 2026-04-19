<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Proposal;
use App\Models\Project;

class ProposalPolicy
{
    public function create(User $user, int $projectId): bool
    {
        $project = Project::find($projectId);

        if (!$project) return false;

        return $user->role === 'freelancer'
            && $project->user_id !== $user->id
            && $project->status === 'open'
            && !$project->proposals()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Proposal $proposal): bool
    {
        return $user->role === 'freelancer'
            && $proposal->user_id === $user->id
            && $proposal->status === 'pending';
    }

    public function accept(User $user, Proposal $proposal): bool
    {
        return $user->id === $proposal->project->user_id
            && $proposal->project->status === 'open';
    }
}
