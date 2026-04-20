<?php

namespace App\Repositories\Interfaces;

interface ProposalRepositoryInterface
{
    public function find(int $id);

    public function create(array $data);

    public function rejectOthers(int $projectId, int $acceptedProposalId);

    public function updateStatus(int $id, string $status);
}
