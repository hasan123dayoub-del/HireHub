<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    protected $model;

    public function __construct(Project $model)
    {
        $this->model = $model;
    }

    public function listProjects(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()

            ->with(['user', 'tags'])

            ->withCount('proposals')

            ->open()

            ->when($filters['min_budget'] ?? null, function ($query, $minBudget) {
                $query->budgetAbove($minBudget);
            })
            ->when($filters['this_month'] ?? null, function ($query) {
                $query->publishedThisMonth();
            })

            ->latest()
            ->paginate($perPage);
    }
}
