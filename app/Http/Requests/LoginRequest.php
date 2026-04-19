<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
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
            'email.required'    => 'We need your email address to log you in.',
            'email.email'       => 'Please provide a valid email format.',
            'password.required' => 'Password is required.',
        ];
    }
}
