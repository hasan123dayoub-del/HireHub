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
            'phone_number' => ['required', 'string', 'unique:freelancer_profiles,phone_number'],
            'availability'    => ['required', 'in:available,busy,unavailable'],
            'avatar'          => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'skills'          => ['required', 'array', 'min:1'],
            'skills.*.id'     => ['required', 'exists:skills,id'],
            'skills.*.years'  => ['required', 'integer', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'bio'              => 'professional biography',
            'hourly_rate'      => 'hourly rate',
            'phone_number'     => 'phone number',
            'availability'     => 'availability status',
            'avatar'           => 'profile picture',
            'skills'           => 'skills list',
            'skills.*.id'      => 'skill',
            'skills.*.years'   => 'years of experience',
        ];
    }

    public function messages(): array
    {
        return [
            'bio.required'           => 'Your :attribute is essential for clients to know your background.',
            'bio.min'                => 'The :attribute is too short. Please write at least :min characters to better describe your experience.',
            'phone_number.required'  => 'A :attribute is required so clients can contact you.',
            'phone_number.unique'    => 'This :attribute is already registered with another account.',
            'hourly_rate.min'        => 'To maintain service quality, the :attribute must be at least $5.',
            'avatar.required'        => 'A :attribute is required to complete your professional profile.',
            'avatar.max'             => 'The :attribute size should not exceed 2MB.',
            'skills.min'             => 'Please add at least one :attribute to your profile.',
            'skills.*.id.exists'     => 'One of the selected skills is invalid.',
            'skills.*.years.required' => 'Please specify the :attribute for each skill you selected.',
            'skills.*.years.min'     => 'The :attribute cannot be negative.',
        ];
    }
}
