<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\User;
use App\Rules\CleanContent;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('project'));
    }

    protected function prepareForValidation()
    {
        if ($this->has('title')) {
            $this->merge([
                'title' => strip_tags(trim($this->title)),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'         => ['sometimes', 'string', 'min:10', 'max:255', new CleanContent],
            'description'   => ['sometimes', 'string', 'min:50', new CleanContent],
            'budget_type'   => ['sometimes', 'in:fixed,hourly'],
            'budget_amount' => [
                'sometimes',
                'numeric',
                ($this->budget_type ?? $this->route('project')->budget_type) === 'fixed' ? 'min:10' : 'min:1'
            ],
            'delivery_date' => ['sometimes', 'date', 'after:today'],
            'tags'          => ['sometimes', 'array', 'min:1', 'max:5'],
            'tags.*'        => ['exists:tags,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'title.min'           => 'The project title must be at least 10 characters.',
            'description.min'     => 'The description must be at least 50 characters long.',
            'budget_amount.min'   => 'Invalid budget amount for the selected budget type.',
            'delivery_date.after' => 'The delivery date must be a future date.',
            'tags.*.exists'       => 'One or more selected tags are invalid.',
        ];
    }
}
