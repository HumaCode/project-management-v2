<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginController extends Controller
{
    /**
     * Handle secure auto-login via signed URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $admin_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request, $admin_id)
    {
        // 1. Validasi user admin
        $admin = User::findOrFail($admin_id);

        // 2. Keamanan tambahan: Pastikan user memiliki role admin
        if (!$admin->hasRole('admin')) {
            abort(403, 'Akses ditolak. Hanya admin yang dapat menggunakan fitur ini.');
        }

        // 3. Login otomatis
        Auth::login($admin);

        // 4. Redirect ke tujuan (misal: halaman users dengan filter email)
        $redirectUrl = $request->get('redirect', '/dashboard');
        
        return redirect($redirectUrl)->with('success', 'Berhasil masuk secara otomatis sebagai Admin.');
    }
}
