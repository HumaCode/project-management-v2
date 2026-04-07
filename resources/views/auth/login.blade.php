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

                <div class="alert-error" id="alertError" style="display:none; align-items: center; gap: 10px;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="alertMsg"></span>
                </div>

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
