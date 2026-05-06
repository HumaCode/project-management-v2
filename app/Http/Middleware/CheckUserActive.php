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

            // Cek apakah is_active = '0'
            if ($user->is_active == '0') {
                return redirect()->route('inactive');
            }
        }

        return $next($request);
    }
}
