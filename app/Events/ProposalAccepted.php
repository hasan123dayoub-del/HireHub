<?php

namespace App\Events;

use App\Models\Proposal;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProposalAccepted
{
    use Dispatchable, SerializesModels;

    public function __construct(public Proposal $proposal) {}
}
