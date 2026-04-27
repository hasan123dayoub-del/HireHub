<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $projects = $this->projectService->listProjects();
        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject(
            $request->user(),
            $request->validated(),
            $request->tags ?? [],
            $request->file('attachments') ?? []
        );

        return response()->json([
            'message' => 'Project published successfully',
            'status'  => 'success',
            'data'    => new ProjectResource($project)
        ], 201);
    }
    public function show($id): ProjectResource
    {
        $project = $this->projectService->getProjectDetails($id);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $updatedProject = $this->projectService->updateProject($project, $request->validated());

        return response()->json([
            'message' => 'Project updated successfully',
            'status'  => 'success',
            'data'    => new ProjectResource($updatedProject)
        ]);
    }
    public function destroy(Project $project): JsonResponse
    {
        $this->projectService->deleteProject($project);

        return response()->json([
            'message' => 'Project and its attachments deleted successfully'
        ], 200);
    }
}
