<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;


class UpdateFreelancerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!$this->user()->hasRole('freelancer')) {
            return false;
        }

        $profile = $this->user()->freelancerProfile;

        if ($profile) {
            return $this->user()->can('update', $profile);
        }

        return true;
    }

    public function rules(): array
    {
        return [
            'bio'         => ['sometimes', 'string', 'min:100'],
            'hourly_rate' => ['sometimes', 'numeric', 'min:5'],
            'phone_number'       => ['sometimes', 'string', 'unique:freelancer_profiles,phone_number,' . $this->user()->freelancerProfile?->id],
            'availability' => ['sometimes', 'in:available,busy,unavailable'],
            'avatar'      => ['nullable', 'image', 'max:2048'],
            'skills'      => ['sometimes', 'array'],
            'skills.*.id' => ['required_with:skills', 'exists:skills,id'],
            'skills.*.years' => ['required_with:skills', 'integer', 'min:0'],
        ];
    }
    public function attributes(): array
    {
        return [
            'bio'            => 'professional biography',
            'hourly_rate'    => 'hourly rate',
            'phone_number'   => 'phone number',
            'availability'   => 'availability status',
            'avatar'         => 'profile picture',
            'skills'         => 'skills list',
            'skills.*.id'    => 'skill',
            'skills.*.years' => 'years of experience',
        ];
    }

    public function messages(): array
    {
        return [
            'bio.min'                => 'When updating your :attribute, it must still be at least :min characters.',
            'phone_number.unique'    => 'This :attribute is already linked to another account.',
            'hourly_rate.min'        => 'The :attribute cannot be less than $5.',
            'avatar.image'           => 'The :attribute must be a valid image file.',
            'avatar.max'             => 'The :attribute size should not exceed 2MB.',
            'skills.*.id.exists'     => 'One of the selected :attribute is invalid.',
            'skills.*.years.required_with' => 'You must specify :attribute for each skill you update.',
            'skills.*.years.min'     => 'The :attribute cannot be less than zero.',
        ];
    }
}
