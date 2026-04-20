<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App\Models\FreelancerProfile;

class FreelancerProfileService
{
    public function listFreelancers(int $perPage = 15): LengthAwarePaginator
    {
        return User::freelancers()
            ->verified()
            ->with(['profile', 'skills'])
            ->whereHas('profile', function ($query) {
                $query->bestRated();
            })
            ->latest()
            ->paginate($perPage);
    }

    public function getFreelancerDetails(int $id): User
    {
        return User::freelancers()
            ->with(['profile', 'skills', 'projects'])
            ->withCount(['projects as completed_projects' => function ($q) {
                $q->where('status', 'closed');
            }])
            ->findOrFail($id);
    }

    // public function saveProfile(User $user, array $data): User
    // {
    //     return DB::transaction(function () use ($user, $data) {

    //         if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
    //             if ($user->profile?->avatar) {
    //                 Storage::disk('public')->delete($user->profile->avatar);
    //             }
    //             $data['avatar'] = $data['avatar']->store('avatars', 'public');
    //         }

    //         $user->profile()->updateOrCreate(
    //             ['user_id' => $user->id],
    //             Arr::except($data, ['skills'])
    //         );

    //         if (isset($data['skills'])) {
    //             $syncData = [];
    //             foreach ($data['skills'] as $skill) {
    //                 $syncData[$skill['id']] = [
    //                     'years_of_experience' => $skill['years'] ?? 0
    //                 ];
    //             }
    //             $user->skills()->sync($syncData);
    //         }

    //         return $user->load(['profile', 'skills']);
    //     });
    // }
    // public function updateProfile(User $user, array $data): User
    // {
    //     return DB::transaction(function () use ($user, $data) {

    //         if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
    //             if ($user->profile?->avatar) {
    //                 Storage::disk('public')->delete($user->profile->avatar);
    //             }
    //             $data['avatar'] = $data['avatar']->store('avatars', 'public');
    //         }

    //         $user->profile()->update($data);

    //         return $user->load('profile');
    //     });
    // }
    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {

            if (isset($data['avatar']) && $data['avatar'] instanceof \Illuminate\Http\UploadedFile) {
                if ($user->profile?->avatar) {
                    Storage::disk('public')->delete($user->profile->avatar);
                }
                $data['avatar'] = $data['avatar']->store('avatars', 'public');
            }

            $user->profile()->update(Arr::except($data, ['skills']));

            if (isset($data['skills'])) {
                $syncData = [];
                foreach ($data['skills'] as $skill) {
                    $syncData[$skill['id']] = [
                        'years_of_experience' => $skill['years'] ?? 0
                    ];
                }
                $user->skills()->sync($syncData);
            }

            return $user->load(['profile', 'skills']);
        });
    }
    public function addSkill(User $user, array $data): User
    {
        $user->skills()->syncWithoutDetaching([
            $data['skill_id'] => ['years_of_experience' => $data['years_of_experience']]
        ]);

        return $user->load('skills');
    }

    public function updateUserSkill(User $user, $skillId, array $data)
    {
        $user->skills()->updateExistingPivot($skillId, [
            'years_of_experience' => $data['years_of_experience']
        ]);
        return $user->load('skills');
    }

    public function removeSkill(User $user, int $skillId)
    {
        return $user->skills()->detach($skillId);
    }
}
