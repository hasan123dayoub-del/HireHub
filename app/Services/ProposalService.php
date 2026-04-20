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

            if ($project->user_id === $user->id) {
                abort(403, 'You cannot apply for your own project.');
            }

            if ($project->status !== 'open') {
                abort(
                    403,
                    'This project is no longer accepting bids.'
                );
            }

            $exists = $user->proposals()->where('project_id', $project->id)->exists();
            if ($exists) {
                abort(400, 'I have already applied for this project.');
            }

            $proposal = $user->proposals()->make($data);
            $proposal->project()->associate($project);
            $proposal->save();

            return $proposal->load(['project.client', 'freelancer.profile']);
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
        $proposal = $this->repository->find($id);

        $proposal->load(['project.client', 'freelancer.profile']);

        if ($proposal->status === 'accepted') {
            $proposal->load(['attachments', 'milestones']);
        }

        return $proposal;
    }
    public function updateProposal(Proposal $proposal, array $data): Proposal
    {
        $proposal->update($data);

        return $proposal->load(['project', 'user']);
    }
}
