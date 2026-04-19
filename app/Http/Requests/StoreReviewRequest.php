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
            'type' => 'review type',
            'id'   => 'target ID',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'comment.min' => 'Please provide a brief comment about your experience (at least 10 characters).',
        ];
    }
}
