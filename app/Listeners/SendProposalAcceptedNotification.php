<?php

namespace App\Listeners;

use App\Events\ProposalAccepted;
use App\Jobs\SendProposalAcceptedNotificationJob;

class SendProposalAcceptedNotification
{
    public function handle(ProposalAccepted $event): void
    {
        // Dispatch job to send notification
        SendProposalAcceptedNotificationJob::dispatch($event->proposal);
    }
}
