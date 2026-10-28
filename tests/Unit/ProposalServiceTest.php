<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProposalService;
use App\Repositories\Interfaces\ProposalRepositoryInterface;
use App\Models\Proposal;
use App\Events\ProposalAccepted;
use Illuminate\Support\Facades\Event;
use Mockery;

class ProposalServiceTest extends TestCase
{
    public function test_accept_proposal_logic_without_database()
    {
        // Keep this a pure unit test: the ProposalAccepted listener queues a job that reads the database.
        Event::fake([ProposalAccepted::class]);

        $repositoryMock = Mockery::mock(ProposalRepositoryInterface::class);

        $repositoryMock->shouldReceive('find')
            ->once()
            ->with(10)
            ->andReturn(new Proposal(['id' => 10, 'project_id' => 1]));

        $repositoryMock->shouldReceive('updateStatus')
            ->once()
            ->with(10, 'accepted');

        $repositoryMock->shouldReceive('rejectOthers')
            ->once()
            ->with(1, 10);

        $service = new ProposalService($repositoryMock);

        $service->acceptProposal(10);

        Event::assertDispatched(ProposalAccepted::class);
    }
}
