<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CleanContent implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $badWords = ['badword1', 'badword2', 'محتوى_مسيء'];

        foreach ($badWords as $word) {
            if (str_contains(mb_strtolower($value), $word)) {
                $fail('The :attribute contains inappropriate language.');
                break;
            }
        }
    }
}
