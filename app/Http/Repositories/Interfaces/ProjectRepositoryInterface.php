<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function listProjects(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
