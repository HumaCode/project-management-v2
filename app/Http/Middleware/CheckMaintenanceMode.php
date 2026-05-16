<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Abaikan jika sedang mengakses halaman login (agar dev bisa login) atau rute logout
        if ($request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        try {
            if (\Schema::hasTable('settings')) {
                $settings = Setting::whereIn('key', ['maintenance_mode', 'maintenance_message', 'maintenance_end_time'])
                    ->pluck('value', 'key');

                if (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] === '1') {
                    // Jika user login
                    if (Auth::check()) {
                        // Jika dev, biarkan lewat
                        if (Auth::user()->hasRole('dev')) {
                            return $next($request);
                        }

                        // Jika bukan dev, paksa logout dan bersihkan session
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();
                    }

                    // Tampilkan halaman maintenance (sekarang user sudah logout)
                    return response()->view('errors.maintenance', [
                        'message' => $settings['maintenance_message'] ?? 'Sistem sedang dalam pemeliharaan.',
                        'endTime' => $settings['maintenance_end_time'] ?? null
                    ], 503);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB not ready
        }

        return $next($request);
    }
}
