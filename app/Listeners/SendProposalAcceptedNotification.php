<?php

namespace App\Listeners;

use App\Events\ProposalAccepted;

class SendProposalAcceptedNotification
{
    public function handle(ProposalAccepted $event): void
    {
        // Here we define the notification logic (Database, Email, SMS)

        // Currently, we will only send notifications within the platform.

        $freelancer = $event->proposal->freelancer;

        // Example: $freelancer->notify(new ProposalAcceptedNotification($event->proposal));

        // Tomorrow, if the CTO requests an SMS notification, we will modify this section only or create a new Listener.

        // Without touching the ProposalService at all.
    }
}
