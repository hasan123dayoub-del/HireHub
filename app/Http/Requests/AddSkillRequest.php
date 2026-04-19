<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('freelancer');
    }

    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'exists:skills,id'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
        ];
    }
}