<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\ProposalRepositoryInterface;
use App\Events\ProposalAccepted;

class ProposalService
{
    public function __construct(
        protected ProposalRepositoryInterface $repository
    ) {}
    public function submitProposal(User $user, array $data): Proposal
    {
        return DB::transaction(function () use ($user, $data) {
            $project = Project::findOrFail($data['project_id']);

            $proposal = $user->proposals()->make($data);
            $proposal->project()->associate($project);
            $proposal->save();

            return $proposal->load(['project', 'freelancer']);
        });
    }

    public function acceptProposal(int $proposalId)
    {
        $proposal = $this->repository->find($proposalId);

        $this->repository->updateStatus($proposalId, 'accepted');

        $this->repository->rejectOthers($proposal->project_id, $proposalId);

        event(new ProposalAccepted($proposal));
    }

    public function getProposalDetails(int $id): Proposal
    {
        return $this->repository->find($id)->load(['project', 'freelancer', 'attachments']);
    }
}
