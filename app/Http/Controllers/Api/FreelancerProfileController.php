<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFreelancerProfileRequest;
use App\Http\Requests\UpdateFreelancerProfileRequest;
use App\Services\FreelancerProfileService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Models\FreelancerProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class FreelancerProfileController extends Controller
{
    protected FreelancerProfileService $freelancerprofileservice;

    public function __construct(FreelancerProfileService $freelancerprofileservice)
    {
        $this->freelancerprofileservice = $freelancerprofileservice;
    }

    public function index(): JsonResponse
    {
        $freelancers = $this->freelancerprofileservice->listFreelancers();

        return response()->json([
            'status' => 'success',
            'data'   => UserResource::collection($freelancers),
            'meta'   => [
                'total' => $freelancers->total(),
                'page'  => $freelancers->currentPage()
            ]
        ]);
    }
    public function show(int $id): JsonResponse
    {
        $freelancer = $this->freelancerprofileservice->getFreelancerDetails($id);
        return response()->json([
            'status' => 'success',
            'data'   => new UserResource($freelancer)
        ]);
    }

    public function store(StoreFreelancerProfileRequest $request)
    {
        $updatedUser = $this->freelancerprofileservice->saveProfile($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profile created successfully',
            'data' => new UserResource($updatedUser)
        ], 201);
    }

    public function update(UpdateFreelancerProfileRequest $request)
    {
        $updatedUser = $this->freelancerprofileservice->saveProfile($request->user(), $request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => new UserResource($updatedUser)
        ], 200);
    }
    public function destroy(FreelancerProfile $freelancerProfile): JsonResponse
    {
        $this->freelancerprofileservice->deletefreelancerProfile($freelancerProfile);

        return response()->json([
            'message' => 'Profile deleted successfully'
        ], 200);
    }
}
