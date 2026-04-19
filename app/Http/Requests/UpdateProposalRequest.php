<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('proposal'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'amount'        => ['sometimes', 'numeric', 'min:1'],
            'delivery_days' => ['sometimes', 'integer', 'min:1'],
            'cover_letter'  => ['sometimes', 'string', 'min:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'             => 'The bid amount must be at least $1.',
            'delivery_days.min'      => 'Delivery time must be at least 1 day.',
            'cover_letter.min'       => 'Please provide a more detailed cover letter (at least 50 characters).',
        ];
    }
}
