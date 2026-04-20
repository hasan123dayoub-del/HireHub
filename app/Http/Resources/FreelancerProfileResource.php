<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'bio' => $this->bio,
            'hourly_rate' => $this->hourly_rate,

            'skills' => optional($this->skills)->map(function ($skill) {
                return [
                    'id'    => $skill->id,
                    'name'  => $skill->name,
                    'years' => $skill->pivot->years_of_experience,
                    'is_editable' => $this->isOwner,
                ];
            }) ?? [],

            'reviews' => $this->when(!$this->isOwner, ReviewResource::collection($this->whenLoaded('reviews'))),
        ];
    }
}
