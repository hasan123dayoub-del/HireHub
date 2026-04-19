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
            'hourly_rate' => (float) $this->hourly_rate,
            'availability' => $this->availability,
            'phone' => $this->phone,
            // مهارات المستقل مع سنوات الخبرة (من الجدول الوسيط)
            'skills' => $this->skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'years_of_experience' => $skill->pivot->years,
                ];
            }),
        ];
    }
}