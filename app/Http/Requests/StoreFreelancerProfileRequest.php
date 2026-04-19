<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\FreelancerProfile;

class StoreFreelancerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', FreelancerProfile::class);
    }

    public function rules(): array
    {
        return [
            'bio'             => ['required', 'string', 'min:100'],
            'hourly_rate'     => ['required', 'numeric', 'min:5'],
            'phone'           => ['required', 'string', 'unique:freelancer_profiles,phone'],
            'availability'    => ['required', 'in:available,busy,unavailable'],
            'avatar'          => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'skills'          => ['required', 'array', 'min:1'],
            'skills.*.id'     => ['required', 'exists:skills,id'],
            'skills.*.years'  => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.min' => 'The biography is too short. Please write at least 100 characters to better describe your experience.',
            'phone.unique' => 'This phone number is already registered with another account.',
            'avatar.required' => 'A profile picture is required to complete your professional profile.',
            'skills.min' => 'Please add at least one skill to your profile.',
            'hourly_rate.min' => 'The hourly rate must be at least $5.',
            'skills.*.id.exists' => 'One of the selected skills is invalid.',
            'skills.*.years.required' => 'Years of experience is required for each skill.',
        ];
    }
}
