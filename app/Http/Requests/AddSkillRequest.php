<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class AddSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return User::query()
            ->where('id', $user->id)
            ->freelancers()
            ->verified()
            ->exists();
    }

    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'exists:skills,id'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
        ];
    }
}
