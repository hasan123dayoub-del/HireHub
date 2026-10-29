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
            // The @example values pre-fill the request in the API docs with a demo account.
            /** @example client@hirehub.com */
            'email'    => ['required', 'string', 'email'],
            /** @example password */
            'password' => ['required', 'string'],
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
            'email'    => 'email address',
            'password' => 'password',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'We need your :attribute to log you in.',
            'email.email'       => 'The :attribute format is invalid.',
            'password.required' => 'The :attribute is required to access your account.',
        ];
    }
}
