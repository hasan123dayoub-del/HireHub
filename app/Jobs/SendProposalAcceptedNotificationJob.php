<?php

namespace App\Jobs;

use App\Models\Proposal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendProposalAcceptedNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public Proposal $proposal)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send notification to the freelancer
        // Mail::to($this->proposal->freelancer->email)->send(new ProposalAcceptedMail($this->proposal));

        // Send notification to other bidders that their proposals were rejected
        $rejectedProposals = $this->proposal->project->proposals()->where('status', 'rejected')->get();
        foreach ($rejectedProposals as $rejected) {
            // Mail::to($rejected->freelancer->email)->send(new ProposalRejectedMail($rejected));
        }
    }
}
