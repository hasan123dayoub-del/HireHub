<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use App\Models\Review;

class ReviewService
{
    public function submitReview(User $reviewer, array $data)
    {
        $reviewable = $this->resolveReviewable($data['type'], $data['id']);

        if ($data['type'] === 'project' && $reviewable->status !== 'closed') {
            abort(403, 'A project can only be evaluated after it has been closed and completed.');
        }

        $review = $reviewable->reviews()->create([
            'rating'  => $data['rating'],
            'comment' => $data['comment'],
            'user_id' => $reviewer->id,
        ]);

        // Dispatch job to recalculate rating
        if ($data['type'] === 'freelancer') {
            \App\Jobs\CalculateFreelancerRating::dispatch($data['id']);
        }

        return $review;
    }

    private function resolveReviewable(string $type, int $id)
    {
        return match ($type) {
            'project'    => Project::findOrFail($id),
            'freelancer' => User::where('role', 'freelancer')->findOrFail($id),
            default      => abort(400, 'Invalid review type'),
        };
    }
}
