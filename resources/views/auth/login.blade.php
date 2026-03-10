<x-guest-layout>

    @push('auth-css')
        <link rel="stylesheet" href="{{ asset('assets/auth/css/login.css') }}">
    @endpush

    @push('auth-js')
        <script src="{{ asset('assets/auth/js/login.js') }}"></script>
    @endpush

    <!-- Session Status -->
    {{-- <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form> --}}


    <div class="login-row row g-0 mx-auto">

        <!-- LEFT: Brand -->
        <div class="col-12 col-lg-5 brand-panel">
            <div class="brand-logo">
                <div class="logo-icon"><i class="bi bi-diagram-3-fill"></i></div>
                <div class="logo-text"><strong>PMS</strong>Project Management System</div>
            </div>
            <div class="brand-headline">
                <div class="brand-tag">Platform v2.0</div>
                <h2>Kelola Proyek<br>dengan <span>Lebih Cerdas</span></h2>
                <p>Dokumentasi, kolaborasi, dan monitoring proyek dalam satu platform terpadu yang modern.</p>
            </div>
            <div class="brand-features">
                <div class="feature-item"><i class="bi bi-folder2-open"></i><span>Manajemen Dokumen</span></div>
                <div class="feature-item"><i class="bi bi-people-fill"></i><span>Kolaborasi Tim</span></div>
                <div class="feature-item"><i class="bi bi-file-earmark-pdf-fill"></i><span>Generate Laporan PDF</span>
                </div>
                <div class="feature-item"><i class="bi bi-shield-lock-fill"></i><span>Role-based Access</span></div>
            </div>
        </div>

        <!-- RIGHT: Form -->
        <div class="col-12 col-lg-7 form-panel">
            <div class="form-scroll-area">

                <div class="form-header">
                    <div class="welcome-tag"><span class="status-dot"></span>System Online</div>
                    <h1>Masuk ke Akun</h1>
                    <p>Silakan masukkan kredensial Anda untuk melanjutkan</p>
                </div>

                <div class="alert-error" id="alertError" style="display:none">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="alertMsg">Email atau password salah. Silakan coba lagi.</span>
                </div>

                <form id="loginForm" novalidate>
                    <div class="field-group">
                        <label class="field-label" for="email">Email Address</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" id="email" class="form-input" placeholder="nama@email.com"
                                autocomplete="email" required />
                            <span class="input-line"></span>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="password">Password</label>
                        <div class="input-wrap">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="password" class="form-input" placeholder="Masukkan password"
                                autocomplete="current-password" required />
                            <button type="button" class="input-icon-right" id="togglePass"
                                aria-label="Toggle password">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            <span class="input-line"></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4 field-group">
                        <div class="check-group">
                            <input type="checkbox" class="custom-check" id="remember" />
                            <label class="check-label" for="remember">Ingat saya</label>
                        </div>
                        <a href="#" class="link-forgot">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn-login" id="btnLogin">
                        <span><i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang</span>
                        <div class="spinner-ring">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                                <circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,0.3)"
                                    stroke-width="2.5" />
                                <path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="2.5"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                    </button>
                </form>

                <div class="divider"><span>atau lanjutkan dengan</span></div>

                <div class="social-row">
                    <a href="#" class="btn-social"><i class="bi bi-google"></i><span>Google</span></a>
                    <a href="#" class="btn-social"><i class="bi bi-microsoft"></i><span>Microsoft</span></a>
                </div>

                <div class="register-row">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></div>
                <div class="sys-info">PMS v2.0 &mdash; <span>secure connection</span> &mdash; &copy; 2025</div>

            </div>
        </div>

    </div>
</x-guest-layout>
