<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectService
{
    protected ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }
    public function listProjects(array $filters = [], int $perPage = 15)
    {
        $cacheKey = 'projects_list_' . md5(serialize($filters) . $perPage);

        return Cache::tags(['projects'])->remember($cacheKey, 3600, function () use ($filters, $perPage) {

            return $this->projectRepository->listProjects($filters, $perPage);
        });
    }

    public function createProject(User $user, array $data, array $tags = [], array $files = []): Project
    {
        $project = DB::transaction(function () use ($user, $data, $tags, $files) {
            $project = $user->projects()->create($data);

            if (!empty($tags)) {
                $project->tags()->sync($tags);
            }

            foreach ($files as $file) {
                $path = $file->store('projects/attachments', 'public');
                $project->attachments()->create(['file_path' => $path]);
            }

            return $project->load(['user', 'tags', 'attachments']);
        });

        // Invalidate cache for open projects
        Cache::tags(['projects'])->flush();

        // Dispatch job to send notifications
        \App\Jobs\SendProjectPublishedNotification::dispatch($project);

        return $project;
    }

    public function getProjectDetails(int $id): Project
    {
        return Project::with(['user', 'tags', 'attachments', 'proposals.user', 'review'])
            ->withCount('proposals')
            ->findOrFail($id);
    }
    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project->load(['user', 'tags']);
    }
    public function deleteProject(Project $project): bool
    {
        return DB::transaction(function () use ($project) {

            foreach ($project->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }

            return $project->delete();
        });
    }
}
