<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Proposal;

class StoreProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', [Proposal::class, $this->project_id]);
    }

    public function rules(): array
    {
        return [
            'project_id'    => ['required', 'exists:projects,id'],
            'amount'        => ['required', 'numeric', 'min:1'],
            'delivery_days' => ['required', 'integer', 'min:1'],
            'cover_letter'  => ['required', 'string', 'min:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.exists'      => 'The selected project does not exist.',
            'amount.required'        => 'Please specify your bid amount.',
            'amount.numeric'         => 'The bid amount must be a valid number.',
            'amount.min'             => 'The bid amount must be greater than zero.',
            'delivery_days.required' => 'Please estimate the delivery time in days.',
            'delivery_days.min'      => 'Delivery time must be at least 1 day.',
            'cover_letter.required'  => 'A cover letter is required to apply.',
            'cover_letter.min'       => 'The cover letter must be at least 50 characters long to better explain your approach.',
        ];
    }
}
