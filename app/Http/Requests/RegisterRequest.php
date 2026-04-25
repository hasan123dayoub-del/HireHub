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

    public function attributes(): array
    {
        return [
            'name'     => 'full name',
            'email'    => 'email address',
            'password' => 'password',
            'role'     => 'account type',
            'city_id'  => 'city',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'     => 'Please tell us who you are; the :attribute is required.',
            'name.min'          => 'The :attribute must be at least :min characters.',

            'email.required'    => 'An :attribute is essential for creating your account.',
            'email.unique'      => 'This :attribute is already registered. Please try logging in.',

            'password.required'  => 'You must secure your account with a :attribute.',
            'password.confirmed' => 'The password confirmation does not match.',

            'role.required'     => 'Please choose your :attribute (Client or Freelancer).',
            'role.in'           => 'The selected :attribute is invalid.',
            'city_id.required'  => 'Please select your :attribute to help us find jobs near you.',
            'city_id.exists'    => 'The selected :attribute is not in our records.',
        ];
    }
}
