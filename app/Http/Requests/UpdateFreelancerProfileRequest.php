<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class UpdateFreelancerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $profile = $this->user()->freelancerProfile;

        if (!$profile) return false;

        return $this->user()->can('update', $profile);
    }

    public function rules(): array
    {
        return [
            'bio'             => ['sometimes', 'string', 'min:100'],
            'hourly_rate'     => ['sometimes', 'numeric', 'min:5'],
            'phone'           => ['sometimes', 'string', 'unique:freelancer_profiles,phone,' . $this->user()->profile->id],
            'availability'    => ['sometimes', 'in:available,busy,unavailable'],
            'avatar'          => ['nullable', 'image', 'max:2048'],
            'skills'          => ['sometimes', 'array'],
            'skills.*.id'     => ['required_with:skills', 'exists:skills,id'],
            'skills.*.years'  => ['required_with:skills', 'integer', 'min:0'],
        ];
    }
}
