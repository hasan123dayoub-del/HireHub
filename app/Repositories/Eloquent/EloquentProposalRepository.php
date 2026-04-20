<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\ProposalRepositoryInterface;
use App\Models\Proposal;

class EloquentProposalRepository implements ProposalRepositoryInterface
{
    protected $model;

    public function __construct(Proposal $model)
    {
        $this->model = $model;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStatus(int $id, string $status)
    {
        $proposal = $this->find($id);
        $proposal->update(['status' => $status]);
        return $proposal;
    }

    public function rejectOthers(int $projectId, int $acceptedProposalId)
    {
        return $this->model->where('project_id', $projectId)
            ->where('id', '!=', $acceptedProposalId)
            ->update(['status' => 'rejected']);
    }
}
