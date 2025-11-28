<?php

namespace App\Http\Requests;

use App\Rules\UserExists;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginAuthRequest extends FormRequest
{
    public string $keyUIP;
    public int $decaySecond = 30;
    public int $maxAttempts = 5;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', new UserExists($this->ip())],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'username.required' => 'Username is required.',
            'username.string' => 'Full name must be a string.',
        ];
    }

    protected function passedValidation()
    {
        $this->keyUIP = throttleKey($this->ip());
        $this->authenticate();
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        //get from formData
        $this->ensureIsNotRateLimited();

        $input = $this->input('username');

        // Detect is email or username
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Create credentials array
        $credentials = [
            $field => $input,
            'password' => $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->keyUIP, $this->decaySecond);
            throw ValidationException::withMessages([
                'password' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($this->keyUIP);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->keyUIP, $this->maxAttempts)) {
            return;
        }
        event(new Lockout($this));
        $seconds = RateLimiter::availableIn($this->keyUIP);
        throw ValidationException::withMessages([
            'password' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }
}
