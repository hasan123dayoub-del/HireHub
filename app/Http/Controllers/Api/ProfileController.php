<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\AddSkillRequest;
use App\Services\FreelancerProfileService;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected FreelancerProfileService $freelancerProfileService;

    public function __construct(FreelancerProfileService $freelancerProfileService)
    {
        $this->freelancerProfileService = $freelancerProfileService;
    }

    /**
     * عرض الـ profile الحالي للمستخدم المصادق عليه
     */
    public function show(): JsonResponse
    {
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * تحديث الـ profile
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();
        $updatedUser = $this->freelancerProfileService->updateProfile($user, $request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($updatedUser),
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * إضافة مهارة جديدة مع سنوات الخبرة
     */
    public function addSkill(AddSkillRequest $request): JsonResponse
    {
        $user = Auth::user();
        $skill = $this->freelancerProfileService->addSkill($user, $request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $skill,
            'message' => 'Skill added successfully'
        ]);
    }

    /**
     * تحديث مهارة موجودة
     */
    public function updateSkill(int $skillId, AddSkillRequest $request): JsonResponse
    {
        $user = Auth::user();
        $skill = $this->freelancerProfileService->updateSkill($user, $skillId, $request->validated());

        return response()->json([
            'status' => 'success',
            'data' => $skill,
            'message' => 'Skill updated successfully'
        ]);
    }

    /**
     * حذف مهارة
     */
    public function deleteSkill(int $skillId): JsonResponse
    {
        $user = Auth::user();
        $this->freelancerProfileService->deleteSkill($user, $skillId);

        return response()->json([
            'status' => 'success',
            'message' => 'Skill deleted successfully'
        ]);
    }
}
