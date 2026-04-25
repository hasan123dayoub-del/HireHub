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

    public function attributes(): array
    {
        return [
            'project_id'    => 'project',
            'amount'        => 'bid amount',
            'delivery_days' => 'delivery timeframe',
            'cover_letter'  => 'cover letter',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.exists'      => 'It seems the :attribute you are applying for is no longer available.',
            'amount.required'        => 'Please specify your :attribute to let the client know your rate.',
            'amount.min'             => 'The :attribute must be at least $1.',
            'delivery_days.required' => 'The client needs to know your estimated :attribute.',
            'delivery_days.min'      => 'The :attribute must be at least :min day.',
            'cover_letter.required'  => 'Your :attribute is your chance to shine; please don\'t leave it empty.',
            'cover_letter.min'       => 'To increase your chances, the :attribute should be at least :min characters long.',
        ];
    }
}
