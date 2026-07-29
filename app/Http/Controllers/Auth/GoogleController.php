<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google for authentication.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle()
    {
        $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
        return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
    }

    /**
     * Handle Google callback and login the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
            $googleUser = Socialite::driver('google')->redirectUrl($redirectUrl)->user();
            
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // If user exists, update their google_id if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                
                Auth::login($user);
            } else {
                // If user doesn't exist, create a new one
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt('password'), // Random password
                    'is_active' => 0, // Default to inactive for new registration
                ]);

                // Assign default role 'user' (create it first if it doesn't exist to prevent errors in tests/empty db)
                \App\Models\Shield\Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web'], [
                    'slug' => 'user',
                    'type_role' => 'system',
                    'description' => 'Pengguna luar / pemohon aplikasi'
                ]);
                $newUser->assignRole('user');

                Auth::login($newUser);
            }

            return redirect()->intended('/dashboard');
            
        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat masuk dengan Google: ' . $e->getMessage());
        }
    }
}
