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

    public function attributes(): array
    {
        return [
            'amount'        => 'bid amount',
            'delivery_days' => 'delivery timeframe',
            'cover_letter'  => 'cover letter',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'             => 'Even when updating, your :attribute must be at least $:min.',
            'delivery_days.min'      => 'The :attribute must be at least :min day.',
            'cover_letter.min'       => 'To keep your proposal competitive, the :attribute must be at least :min characters long.',
            'amount.numeric'         => 'The :attribute must be a valid number.',
            'delivery_days.integer'  => 'The :attribute must be a whole number of days.',
        ];
    }
}
