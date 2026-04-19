<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255', 'min:3'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()
            ],
            'role'     => ['required', 'in:client,freelancer'],
            'city_id'  => ['required', 'exists:cities,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower(trim($this->email)),
        ]);
    }

    public function messages(): array
    {
        return [
            'email.unique'   => 'This email is already registered. Please try logging in.',
            'password.min'   => 'Password must be at least 8 characters long.',
            'role.in'        => 'Please select a valid role (Client or Freelancer).',
            'city_id.exists' => 'The selected city is invalid.',
        ];
    }
}
