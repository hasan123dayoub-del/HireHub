<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth; 

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
   
        $isOwner = Auth::check() && Auth::id() === $this->id;

        // Skills belong to the user (freelancer_skill), but they are shown inside the profile.
        if ($this->relationLoaded('profile') && $this->profile && $this->relationLoaded('skills')) {
            $this->profile->setRelation('skills', $this->skills);
        }

        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->when($isOwner, $this->email),
            'role'  => $this->role,

            'profile' => $this->when(
                $this->role === 'freelancer',
                new FreelancerProfileResource($this->whenLoaded('profile'))
            ),

            'avatar_url' => $this->avatar ? asset('storage/' . $this->avatar) : asset('default-avatar.png'),
            'verified'   => $this->email_verified_at !== null,

            'owner_stats' => $this->when($isOwner, [
                'profile_completion' => 85,
                'member_since'       => $this->created_at?->toFormattedDateString(),
            ]),
        ];
    }
}