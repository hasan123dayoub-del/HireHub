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

    public function attributes(): array
    {
        return [
            'title'         => 'project title',
            'description'   => 'project description',
            'budget_type'   => 'budget type',
            'budget_amount' => 'budget amount',
            'delivery_date' => 'delivery date',
            'tags'          => 'project tags',
            'tags.*'        => 'selected tag',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Your project needs a :attribute to attract freelancers.',
            'title.min'              => 'The :attribute is too short. Please provide at least :min characters.',
            'description.min'        => 'Please provide a more detailed :attribute (at least :min characters).',
            'budget_amount.min'      => $this->budget_type === 'fixed'
                ? 'The minimum budget for fixed projects is $10.'
                : 'The minimum rate for hourly projects is $1.',
            'delivery_date.after'    => 'The :attribute must be a future date; we can\'t travel back in time!',
            'tags.required'          => 'Please select at least one :attribute to help freelancers find your project.',
            'tags.max'               => 'You can only select up to :max :attribute.',
            'tags.*.exists'          => 'One of the :attribute is invalid or no longer exists.',
        ];
    }
}
