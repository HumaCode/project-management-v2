<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Mail\OtpLoginMail;

class OtpLoginController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'g-recaptcha-response' => (app()->environment('testing') || empty(config('services.recaptcha.site_key'))) ? ['nullable'] : ['required', new \App\Rules\Recaptcha],
        ], [
            'exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        $email = $request->email;
        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(10);

        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $email, $otp, $expiresAt);

        // Send Professional Mailable
        try {
            Mail::to($email)->send(new OtpLoginMail($otp));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengirim email. Silakan coba lagi.'], 500);
        }

        return response()->json(['message' => 'OTP berhasil dikirim ke email Anda.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric|digits:6',
            'g-recaptcha-response' => (app()->environment('testing') || empty(config('services.recaptcha.site_key'))) ? ['nullable'] : ['required', new \App\Rules\Recaptcha],
        ]);

        $cachedOtp = Cache::get('otp_' . $request->email);

        if (!$cachedOtp || $cachedOtp != $request->code) {
            return response()->json([
                'errors' => ['code' => ['Kode OTP tidak valid atau sudah kadaluarsa.']]
            ], 422);
        }

        // OTP Valid, find user and login
        $user = User::where('email', $request->email)->first();
        
        Auth::login($user, $request->boolean('remember'));

        // Clear OTP
        Cache::forget('otp_' . $request->email);

        $redirectUrl = redirect()->intended(route('dashboard'))->getTargetUrl();

        return response()->json([
            'redirect' => $redirectUrl,
            'message' => 'Login berhasil!'
        ]);
    }
}
