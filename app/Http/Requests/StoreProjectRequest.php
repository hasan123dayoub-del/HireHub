<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CleanContent;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Project::class);
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags(trim($this->title)),
        ]);
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'min:10', 'max:255', new CleanContent],
            'description'   => ['required', 'string', 'min:50', new CleanContent],
            'budget_type'   => ['required', 'in:fixed,hourly'],
            'budget_amount' => [
                'required',
                'numeric',
                $this->budget_type === 'fixed' ? 'min:10' : 'min:1'
            ],
            'delivery_date' => ['required', 'date', 'after:today'],
            'tags'          => ['required', 'array', 'min:1', 'max:5'],
            'tags.*'        => ['exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min'             => 'The project title is too short. Please provide at least 10 characters.',
            'description.min'       => 'Please provide a more detailed description (at least 50 characters).',
            'budget_amount.min'     => 'The minimum budget for fixed projects is $10, and $1 for hourly projects.',
            'delivery_date.after'   => 'The delivery date must be a future date.',
            'tags.required'         => 'Please select at least one skill tag for your project.',
            'tags.*.exists'         => 'One of the selected tags is invalid.',
        ];
    }
}
