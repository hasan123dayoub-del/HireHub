<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'profile' => $this->when(
                $this->role === 'freelancer',
                new FreelancerProfileResource($this->whenLoaded('freelancerProfile'))
            ),
            'avatar_url' => $this->avatar ? asset('storage/' . $this->avatar) : asset('default-avatar.png'),
            'verified' => $this->email_verified_at !== null,
        ];
    }
}
