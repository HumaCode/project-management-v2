<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan ada user yang sedang login
        if (Auth::check()) {
            $user = Auth::user();

            // Cek apakah email_verified_at null ATAU is_active = '0'
            // (Menggunakan || agar jika salah satu gagal, langsung ditolak)
            if (is_null($user->email_verified_at) || $user->is_active == '0') {

                // Logout user agar tidak terjebak di loop otentikasi
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect ke login dengan membawa session flash bernama 'sca_error'
                return redirect()->route('login')->with(
                    'sca_error',
                    'Akun Anda belum aktif atau email belum diverifikasi. Silakan hubungi Administrator.'
                );
            }
        }

        return $next($request);
    }
}
