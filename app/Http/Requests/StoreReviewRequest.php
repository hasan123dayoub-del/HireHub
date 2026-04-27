<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'client';
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['project', 'freelancer'])],

            'id' => ['required', 'integer', function ($attribute, $value, $fail) {
                if ($this->type === 'project') {
                    $project = DB::table('projects')->where('id', $value)->first();
                    if (!$project) return $fail("The project does not exist.");
                    if ($project->status !== 'closed') {
                        return $fail("The project can only be evaluated after it has been successfully closed.");
                    }
                } else {
                    $isFreelancer = DB::table('users')
                        ->where('id', $value)
                        ->where('role', 'freelancer')
                        ->exists();
                    if (!$isFreelancer) return $fail("The specified independent is incorrect.");
                }
            }],

            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'type'    => 'review type',
            'id'      => $this->type === 'project' ? 'project' : 'freelancer',
            'rating'  => 'star rating',
            'comment' => 'feedback comment',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required'   => 'Please specify if you are reviewing a :attribute or a freelancer.',
            'type.in'         => 'The selected :attribute is invalid.',
            'rating.required' => 'Your :attribute is important to us, please select between 1 and 5 stars.',
            'rating.min'      => 'Your :attribute must be at least :min star.',
            'rating.max'      => 'Your :attribute cannot exceed :max stars.',
            'comment.required' => 'Please share your experience in the :attribute field.',
            'comment.min'     => 'Your :attribute is a bit too short. Please provide at least :min characters.',
            'comment.max'     => 'Your :attribute is too long. Please keep it under :max characters.',
        ];
    }
}
