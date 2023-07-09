<?php

namespace App\Rules;

use App\Services\FFProbeService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use FFMpeg\FFProbe;
class VideoDuration implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (FFProbeService::make()->format($value)->get('duration') > 15) {
            $fail('Video length is too long');
        }
    }
}
