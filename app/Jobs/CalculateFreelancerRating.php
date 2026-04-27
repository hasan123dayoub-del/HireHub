<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CalculateFreelancerRating implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $freelancerId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lock = Cache::lock("calculate_rating_{$this->freelancerId}", 60);

        if ($lock->get()) {
            try {
                $freelancer = User::find($this->freelancerId);
                if (!$freelancer) return;

                $avgRating = $freelancer->reviews()->avg('rating');

                // Store in cache
                Cache::put("freelancer_rating_{$this->freelancerId}", $avgRating, 3600);
            } finally {
                $lock->release();
            }
        }
    }
}
