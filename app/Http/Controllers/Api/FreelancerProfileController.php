<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFreelancerProfileRequest;
use App\Http\Requests\UpdateFreelancerProfileRequest;
use App\Http\Requests\AddSkillRequest; 
use App\Services\FreelancerProfileService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    public function myProfile(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data'   => new UserResource(Auth::user())
        ]);
    }

    public function storeOrUpdate(UpdateFreelancerProfileRequest $request): JsonResponse
    {
        $user = $this->service->saveProfile(Auth::user(), $request->validated());
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
}
