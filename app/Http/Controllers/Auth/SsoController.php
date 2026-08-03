<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * Redirect the user to the SSO HumaCode authorization page.
     */
    public function redirectToSso()
    {
        $query = http_build_query([
            'client_id' => env('SSO_CLIENT_ID'),
            'redirect_uri' => env('SSO_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'profile roles',
        ]);

        return redirect(env('SSO_HOST', 'http://localhost:8000') . '/oauth/authorize?' . $query);
    }

    /**
     * Handle the callback from the SSO HumaCode server.
     */
    public function handleSsoCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('login')->withErrors([
                'identitas' => 'Otorisasi SSO dibatalkan atau ditolak.',
            ]);
        }

        $code = $request->get('code');
        if (!$code) {
            return redirect()->route('login')->withErrors([
                'identitas' => 'Kode otorisasi SSO tidak ditemukan.',
            ]);
        }

        // Exchange authorization code for access token
        $response = Http::asForm()->post(env('SSO_HOST', 'http://localhost:8000') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => env('SSO_CLIENT_ID'),
            'client_secret' => env('SSO_CLIENT_SECRET'),
            'redirect_uri' => env('SSO_REDIRECT_URI'),
            'code' => $code,
        ]);

        if (!$response->successful()) {
            return redirect()->route('login')->withErrors([
                'identitas' => 'Gagal mendapatkan token SSO: ' . ($response->json('message') ?? 'Respon server tidak valid'),
            ]);
        }

        $accessToken = $response->json('access_token');

        // Fetch user profile from SSO server
        $userResponse = Http::withToken($accessToken)
            ->get(env('SSO_HOST', 'http://localhost:8000') . '/api/userinfo');

        if (!$userResponse->successful()) {
            return redirect()->route('login')->withErrors([
                'identitas' => 'Gagal mengunduh profil user dari SSO.',
            ]);
        }

        $ssoUser = $userResponse->json('data') ?? $userResponse->json();

        $email = $ssoUser['email'] ?? null;
        $name = $ssoUser['name'] ?? 'User SSO';

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'identitas' => 'Profil SSO tidak mengembalikan alamat email yang valid.',
            ]);
        }

        // Find or create user locally in project-management-v2
        $user = User::where('email', $email)->first();

        if (!$user) {
            $username = Str::slug($name) . rand(100, 999);
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'username' => $username,
                'password' => bcrypt(Str::random(16)),
                'is_active' => '1',
            ]);

            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('user');
                } catch (\Throwable $e) {
                    // Ignore if default role doesn't exist
                }
            }
        } else {
            if ($user->is_active !== '1') {
                $user->update(['is_active' => '1']);
            }
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
