<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\SettingService;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $settings = app(SettingService::class)->getAll();
        
        if (($settings['allow_registration'] ?? '1') == '0') {
            abort(404);
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $settings = app(SettingService::class)->getAll();

        if (($settings['allow_registration'] ?? '1') == '0') {
            abort(403, 'Pendaftaran ditutup oleh administrator.');
        }

        // Dynamic Password Policy
        $passwordRule = Password::min($settings['password_min_length'] ?? 8);
        if (($settings['password_require_symbol'] ?? '0') == '1') $passwordRule->symbols();
        if (($settings['password_require_number'] ?? '0') == '1') $passwordRule->numbers();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:255', 'unique:'.User::class, 'alpha_dash'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', $passwordRule],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => ($settings['admin_approval'] ?? '1') == '1' ? 0 : 1,
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil!',
                'redirect' => route('inactive')
            ]);
        }

        return redirect(route('inactive'));
    }
}
