<x-guest-layout>

    @push('auth-css')
        <link rel="stylesheet" href="{{ asset('assets/auth/css/login.css') }}">
    @endpush

    @push('auth-js')
        <script src="{{ asset('assets/auth/js/login.js') }}"></script>
    @endpush

    <div class="login-row row g-0 mx-auto">

        <!-- LEFT: Brand -->
        <div class="col-12 col-lg-5 brand-panel">
            <div class="brand-logo">
                <div class="logo-icon">
                    @if(!empty($cms_settings['app_logo']))
                        <img src="{{ $cms_settings['app_logo'] }}" style="width:32px;height:32px;object-fit:contain" alt="Logo">
                    @else
                        <i class="bi bi-diagram-3-fill"></i>
                    @endif
                </div>
                <div class="logo-text"><strong>{{ $cms_settings['app_name'] ?? 'PMS' }}</strong>{{ $cms_settings['app_description'] ?? 'Project Management System' }}</div>
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

                <div class="alert-error" id="alertError" style="display:none; align-items: center; gap: 10px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="alertMsg"></span>
                </div>

                <div id="loginStandard">
                    <form method="POST" data-url="{{ route('login') }}" id="loginForm" novalidate>
                        @csrf
                        <div class="field-group">
                            <label class="field-label" for="identitas">Email Address / Username</label>
                            <div class="input-wrap">
                                <i class="bi bi-envelope input-icon"></i>
                                <input type="text" id="identitas" name="identitas" class="form-input"
                                    placeholder="nama@email.com" required autofocus />
                                <span class="input-line"></span>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="password">Password</label>
                            <div class="input-wrap">
                                <i class="bi bi-lock input-icon"></i>
                                <input type="password" id="password" name="password" class="form-input"
                                    placeholder="Masukkan password" required />
                                <button type="button" class="input-icon-right" id="togglePass">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                                <span class="input-line"></span>
                            </div>
                        </div>

                        <div class="check-group">
                            <input type="checkbox" name="remember" id="remember_me" class="custom-check">
                            <label for="remember_me" class="check-label">Ingat Saya</label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="link-forgot">Lupa Password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn-login" id="btnLogin" style="margin-top: 24px;">
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

                    @if(($cms_settings['enable_otp'] ?? '0') == '1')
                    <button type="button" class="btn-otp-toggle" id="btnOtpToggle" style="margin-top: 16px;">
                        <i class="bi bi-shield-lock-fill"></i> Masuk dengan Kode OTP (Passwordless)
                    </button>
                    @endif
                </div>

                <!-- ══ OTP LOGIN FLOW ══ -->
                <div id="loginOtp" style="display: none;">
                    <!-- Step 1: Input Email -->
                    <div id="otpStepEmail">
                        <div class="field-group">
                            <label class="field-label" for="otpEmail">Masukkan Email Terdaftar</label>
                            <div class="input-wrap">
                                <i class="bi bi-envelope-at input-icon"></i>
                                <input type="email" id="otpEmail" class="form-input"
                                    placeholder="nama@email.com" />
                                <span class="input-line"></span>
                            </div>
                            <div class="field-msg" id="otpEmailMsg"></div>
                        </div>
                        <button type="button" class="btn-login" id="btnSendOtp">
                            <span><i class="bi bi-send-fill"></i> Kirim Kode Verifikasi</span>
                            <div class="spinner-ring"><svg width="22" height="22" viewBox="0 0 22 22"
                                    fill="none">
                                    <circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,0.3)"
                                        stroke-width="2.5" />
                                    <path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="2.5"
                                        stroke-linecap="round" />
                                </svg></div>
                        </button>
                    </div>

                    <!-- Step 2: Input Code -->
                    <div id="otpStepCode" style="display: none;">
                        <div class="otp-header">
                            <i class="bi bi-envelope-open-fill"></i>
                            <p>Kode OTP 6 digit telah dikirim ke <strong id="displayOtpEmail"
                                    style="color:var(--cyan)"></strong></p>
                        </div>
                        <div class="otp-boxes">
                            <input type="text" class="otp-box" maxlength="1" id="o1"
                                inputmode="numeric">
                            <input type="text" class="otp-box" maxlength="1" id="o2"
                                inputmode="numeric">
                            <input type="text" class="otp-box" maxlength="1" id="o3"
                                inputmode="numeric">
                            <input type="text" class="otp-box" maxlength="1" id="o4"
                                inputmode="numeric">
                            <input type="text" class="otp-box" maxlength="1" id="o5"
                                inputmode="numeric">
                            <input type="text" class="otp-box" maxlength="1" id="o6"
                                inputmode="numeric">
                        </div>
                        <div class="field-msg" id="otpCodeMsg" style="text-align: center; margin-top: 12px;"></div>

                        <div class="resend-text">
                            Kirim ulang kode dalam <span id="otpTimer">02:00</span>
                            <button type="button" id="btnResendOtp" style="position: relative;" disabled>
                                <span>Kirim Ulang</span>
                                <div class="spinner-ring" style="width:14px; height:14px;">
                                    <svg width="14" height="14" viewBox="0 0 22 22" fill="none">
                                        <circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,0.3)"
                                            stroke-width="3" />
                                        <path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="3"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                            </button>
                        </div>

                        <button type="button" class="btn-login" id="btnVerifyOtp" style="margin-top: 24px;">
                            <span><i class="bi bi-shield-check-fill"></i> Verifikasi & Masuk</span>
                            <div class="spinner-ring"><svg width="22" height="22" viewBox="0 0 22 22"
                                    fill="none">
                                    <circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,0.3)"
                                        stroke-width="2.5" />
                                    <path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="2.5"
                                        stroke-linecap="round" />
                                </svg></div>
                        </button>
                    </div>

                    <button type="button" class="btn-back-login" id="btnBackLogin" style="margin-top: 20px;">
                        <i class="bi bi-arrow-left"></i> Kembali ke Login Biasa
                    </button>
                </div>

                <div class="divider"><span>atau lanjutkan dengan</span></div>

                <div class="social-row" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px;">
                    <a href="{{ route('auth.sso') }}" id="btnSso" class="btn-social" style="background: linear-gradient(135deg, #0F6E56, #0B4A3B); color: #ffffff; border: none; font-weight: 600; text-decoration: none;" onclick="handleSsoClick(this)">
                        <i class="bi bi-shield-lock-fill" style="color: #5DCAA5; font-size: 16px;"></i>
                        <span>SSO HumaCode</span>
                    </a>
                    @if(($cms_settings['enable_google_login'] ?? '1') == '1')
                    <a href="{{ route('auth.google') }}" class="btn-social" onclick="handleSsoClick(this)">
                        <i class="bi bi-google"></i>
                        <span>Google</span>
                    </a>
                    @endif
                </div>

                @if(($cms_settings['allow_registration'] ?? '1') == '1')
                <div class="register-row">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></div>
                @endif
                <div class="sys-info">{{ $cms_settings['app_name'] ?? 'PMS' }} {{ $cms_settings['app_version'] ?? 'v2.0' }} &mdash; <span>secure connection</span> &mdash; &copy; {{ date('Y') }}</div>

            </div>
        </div>

    </div>

    <script>
        function handleSsoClick(el) {
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.88';
            el.innerHTML = '<div style="display:inline-block; width:16px; height:16px; margin-right:8px; vertical-align:middle; animation: ssoSpin 0.75s linear infinite;"><svg width="16" height="16" viewBox="0 0 22 22" fill="none"><circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,0.3)" stroke-width="3" /><path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="3" stroke-linecap="round" /></svg></div><span>Sedang proses...</span>';
        }
    </script>
    <style>
        @keyframes ssoSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</x-guest-layout>
