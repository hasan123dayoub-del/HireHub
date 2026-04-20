<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFreelancerProfileRequest;
use App\Http\Requests\UpdateFreelancerProfileRequest;
use App\Http\Requests\AddSkillRequest;
use App\Http\Resources\ProjectResource;
use App\Services\FreelancerProfileService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FreelancerProfileController extends Controller
{
    protected FreelancerProfileService $service;

    public function __construct(FreelancerProfileService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $freelancers = $this->service->listFreelancers();
        return response()->json([
            'status' => 'success',
            'data'   => UserResource::collection($freelancers)
        ]);
    }
    public function show($id): JsonResponse
    {
        $freelancer = $this->service->getFreelancerDetails($id);
        return response()->json([
            'status' => 'success',
            'data'   => new UserResource($freelancer)
        ]);
    }

    public function myProfile(): JsonResponse
    {
        $freelancer = $this->service->getFreelancerDetails(Auth::id());

        return response()->json([
            'status' => 'success',
            'data'   => new ProjectResource($freelancer)
        ]);
    }

    public function update(UpdateFreelancerProfileRequest $request): JsonResponse
    {
        $user = $this->service->updateProfile(Auth::user(), $request->validated());
        return response()->json([
            'message' => 'Profile processed successfully',
            'data' => new UserResource($user)
        ]);
    }


    public function addSkill(AddSkillRequest $request): JsonResponse
    {
        $user = $this->service->addSkill(Auth::user(), $request->validated());
        return response()->json([
            'message' => 'Skill added successfully',
            'data' => new UserResource($user)
        ]);
    }

    public function updateSkill(Request $request, $skillId)
    {
        $validated = $request->validate([
            'years_of_experience' => 'required|integer|min:0'
        ]);

        $user = $this->service->updateUserSkill(Auth::user(), $skillId, $validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Skill updated successfully',
            'data' => new UserResource($user)
        ]);
    }
    public function deleteSkill($skillId): JsonResponse
    {
        $this->service->removeSkill(Auth::user(), $skillId);

        return response()->json([
            'status' => 'success',
            'message' => 'Skill removed from your profile successfully'
        ]);
    }
}
