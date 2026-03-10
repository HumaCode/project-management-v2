<x-guest-layout>

    @push('auth-css')
        <link rel="stylesheet" href="{{ asset('assets/auth/css/register.css') }}">
    @endpush

    @push('auth-js')
        <script src="{{ asset('assets/auth/js/register.js') }}"></script>
    @endpush

    <div class="register-row row g-0 mx-auto">

        <!-- ── LEFT: Brand Panel ── -->
        <div class="col-12 col-lg-5 brand-panel">

            <div class="brand-logo">
                <div class="logo-icon">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
                <div class="logo-text">
                    <strong>PMS</strong>
                    Project Management System
                </div>
            </div>

            <div class="brand-headline">
                <div class="brand-tag">Bergabung Sekarang</div>
                <h2>Buat Akun<br><span>Baru Anda</span></h2>
                <p>Daftarkan diri dan mulai kelola proyek-proyek Anda secara lebih terstruktur dan efisien.</p>
            </div>

            <div class="step-track">
                <div class="step-track-label">Langkah Pendaftaran</div>
                <div class="steps">
                    <div class="step-item active" id="step1">
                        <div class="step-num" id="stepNum1">1</div>
                        <span>Isi informasi akun</span>
                    </div>
                    <div class="step-item" id="step2">
                        <div class="step-num" id="stepNum2">2</div>
                        <span>Buat password aman</span>
                    </div>
                    <div class="step-item" id="step3">
                        <div class="step-num" id="stepNum3">3</div>
                        <span>Konfirmasi & selesai</span>
                    </div>
                </div>
            </div>

            <div class="brand-quote">
                "Setiap proyek besar dimulai dari langkah kecil — dan langkah Anda dimulai dari sini."
            </div>

        </div>

        <!-- ── RIGHT: Form Panel ── -->
        <div class="col-12 col-lg-7 form-panel">
            <div class="form-scroll-area">

                <div class="form-header">
                    <div class="welcome-tag">
                        <span class="status-dot"></span>
                        Pendaftaran Akun Baru
                    </div>
                    <h1>Daftar Akun</h1>
                    <p>Lengkapi formulir di bawah untuk membuat akun Anda</p>
                </div>

                <div class="alert-error" id="alertError" style="display:none">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="alertMsg">Terjadi kesalahan. Silakan periksa kembali formulir.</span>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate autocomplete="off">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="field-group">
                        <label class="field-label" for="nama">
                            Nama Lengkap
                            <span class="field-hint">Sesuai identitas resmi</span>
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" id="nama" name="name" class="form-input"
                                placeholder="Contoh: Budi Santoso" autocomplete="name" required />
                            <span class="input-line"></span>
                            <span class="input-status" id="namaStatus"></span>
                        </div>
                        <div class="field-msg info" id="namaMsg"></div>
                    </div>

                    <!-- Username -->
                    <div class="field-group">
                        <label class="field-label" for="username">
                            Username
                            <span class="field-hint">Hanya huruf, angka, dan _</span>
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-at input-icon"></i>
                            <input type="text" id="username" name="username" class="form-input"
                                placeholder="mis: budi_santoso" autocomplete="off" spellcheck="false" required />
                            <div class="username-status" id="usernameStatusWrap">
                                <div class="checking-spin" id="checkingSpin"></div>
                            </div>
                            <span class="input-line"></span>
                        </div>
                        <div class="field-msg info" id="usernameMsg"></div>
                    </div>

                    <!-- Email -->
                    <div class="field-group">
                        <label class="field-label" for="email">
                            Email
                            <span class="field-hint">Masukan email yang valid</span>
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-at input-icon"></i>
                            <input type="email" id="email" name="email" class="form-input"
                                placeholder="mis: example@gmail.com" spellcheck="false" required />
                            <div class="email-status" id="emailStatusWrap">
                                <div class="checking-spin" id="checkingSpin"></div>
                            </div>
                            <span class="input-line"></span>
                        </div>
                        <div class="field-msg info" id="emailMsg"></div>
                    </div>

                    <!-- Password -->
                    <div class="field-group">
                        <label class="field-label" for="password">
                            Password
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                            <button type="button" class="input-icon-right" id="togglePass"
                                aria-label="Toggle password">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            <span class="input-line"></span>
                        </div>

                        <!-- Strength meter -->
                        <div class="strength-wrap" id="strengthWrap" style="display:none">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-label">
                                <span id="strengthText">Kekuatan password</span>
                                <span id="strengthPct"></span>
                            </div>
                            <div class="strength-checks" id="strengthChecks">
                                <div class="strength-check" id="chkLen">
                                    <i class="bi bi-x-circle"></i> Min. 8 karakter
                                </div>
                                <div class="strength-check" id="chkUpper">
                                    <i class="bi bi-x-circle"></i> Huruf besar
                                </div>
                                <div class="strength-check" id="chkNum">
                                    <i class="bi bi-x-circle"></i> Angka
                                </div>
                                <div class="strength-check" id="chkSym">
                                    <i class="bi bi-x-circle"></i> Simbol (!@#$)
                                </div>
                            </div>
                        </div>
                        <div class="field-msg" id="passMsg"></div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="field-group">
                        <label class="field-label" for="confirmPass">
                            Konfirmasi Password
                        </label>
                        <div class="input-wrap">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" id="confirmPass" class="form-input" name="password_confirmation"
                                placeholder="Ulangi password Anda" autocomplete="new-password" required />
                            <button type="button" class="input-icon-right" id="toggleConfirm"
                                aria-label="Toggle confirm password">
                                <i class="bi bi-eye" id="eyeIconConfirm"></i>
                            </button>
                            <span class="input-line"></span>
                            <span class="input-status" id="confirmStatus"></span>
                        </div>
                        <div class="field-msg" id="confirmMsg"></div>
                    </div>

                    <!-- Terms -->
                    <div class="terms-group">
                        <input type="checkbox" class="custom-check" id="terms" required />
                        <label class="terms-text" for="terms">
                            Saya telah membaca dan menyetujui
                            <a href="#">Syarat & Ketentuan</a> serta
                            <a href="#">Kebijakan Privasi</a> yang berlaku.
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-register" id="btnRegister">
                        <span>
                            <i class="bi bi-person-plus-fill"></i>
                            Buat Akun Sekarang
                        </span>
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

                <div class="login-row-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>

                <div class="sys-info">
                    PMS v2.0 &mdash; <span>secure connection</span> &mdash; &copy; 2025
                </div>

            </div>
        </div>

    </div>
</x-guest-layout>
