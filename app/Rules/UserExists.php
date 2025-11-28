<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UserExists implements ValidationRule
{
    public function __construct(
        protected string $ip,
        protected int $maxAttempts = 5,
    ) {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $key = throttleKey($value."|".$this->ip);
        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $fail("User $value not found. ".trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]), null);

            return;
        }
        if (!User::whereAny(['email', 'username'], $value)->exists()) {
            RateLimiter::hit($key);

            $fail("User {$value} not found.", null);
        } else {
            RateLimiter::clear($key);
        }
    }
}
