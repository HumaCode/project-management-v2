<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
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
            'identitas' => ['required', 'string', 'max:100'],
            'password'  => ['required', 'string'],
            'g-recaptcha-response' => (app()->environment('testing') || empty(config('services.recaptcha.site_key'))) ? ['nullable'] : ['required', new \App\Rules\Recaptcha],
        ];
    }

    public function attributes(): array
    {
        return [
            'identitas'  => 'Identitas',
            'password'   => 'Password',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Cari user dengan eager load roles & permissions untuk mempercepat rendering sidebar setelah login
        $user = User::with(['roles', 'permissions'])
            ->where('email', $this->identitas)
            ->orWhere('username', $this->identitas)
            ->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            \App\Models\SecurityLog::create([
                'ip_address' => $this->ip() === '127.0.0.1' ? '182.16.14.92' : $this->ip(),
                'event_type' => 'Brute-Force Login',
                'url' => $this->getRequestUri(),
                'user_agent' => $this->header('User-Agent'),
                'status' => 'BLOCKED',
            ]);

            throw ValidationException::withMessages([
                'identitas' => trans('auth.failed'),
            ]);
        }

        // Pengecekan user aktif dipindahkan ke middleware user.active
        // if ($user->is_active == '0') {
        //     RateLimiter::hit($this->throttleKey());
        //
        //     throw ValidationException::withMessages([
        //         'identitas' => 'Akun Anda belum aktif atau dinonaktifkan. Silakan hubungi Administrator.',
        //     ]);
        // }

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        \App\Models\SecurityLog::create([
            'ip_address' => $this->ip() === '127.0.0.1' ? '182.16.14.92' : $this->ip(),
            'event_type' => 'Rate Limit Lockout',
            'url' => $this->getRequestUri(),
            'user_agent' => $this->header('User-Agent'),
            'status' => 'BLOCKED',
        ]);

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identitas' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('identitas')) . '|' . $this->ip());
    }
}
