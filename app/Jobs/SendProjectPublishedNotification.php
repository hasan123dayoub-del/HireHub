<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendProjectPublishedNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Project $project)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send email notification to freelancers or interested users
        // For example, send to all freelancers
        $freelancers = \App\Models\User::freelancers()->get();

        foreach ($freelancers as $freelancer) {
            // Mail::to($freelancer->email)->send(new ProjectPublishedMail($this->project));
            // Placeholder: log or send notification
        }
    }
}
