<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('freelancer');
    }

    public function rules(): array
    {
        return [
            'bio' => ['sometimes', 'string', 'min:100'],
            'hourly_rate' => ['sometimes', 'numeric', 'min:5'],
            'phone' => ['sometimes', 'string', 'unique:freelancer_profiles,phone,' . auth()->id()],
            'availability' => ['sometimes', 'in:available,busy,unavailable'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}