<x-guest-layout>

    @push('auth-css')
        <link rel="stylesheet" href="{{ asset('assets/auth/css/forgot-password.css') }}">
    @endpush

    @push('auth-js')
        <script src="{{ asset('assets/auth/js/forgot-password.js') }}"></script>
    @endpush

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="page-wrapper">
        <div class="container-fluid px-0" style="max-width:1080px;width:100%">
            <div class="fp-card row g-0 mx-auto">

                <!-- ══ KIRI: Brand Panel ══ -->
                <div class="col-12 col-lg-5 brand-panel">

                    <div class="brand-logo">
                        <div class="logo-icon"><i class="bi bi-diagram-3-fill"></i></div>
                        <div class="logo-text">
                            <strong>PMS</strong>
                            Project Management System
                        </div>
                    </div>

                    <div class="brand-headline">
                        <div class="brand-tag">Pemulihan Akun</div>
                        <h2>Reset <span>Password</span><br>Anda</h2>
                        <p>Ikuti langkah berikut untuk memulihkan akses ke akun Anda dengan aman.</p>
                    </div>

                    <div class="flow-section">
                        <div class="flow-label">Alur Pemulihan</div>
                        <div class="flow-steps">
                            <div class="flow-item active" id="fl1">
                                <div class="flow-dot" id="fd1">1</div>
                                <div class="flow-content">
                                    <div class="flow-title">Masukkan Email</div>
                                    <div class="flow-desc">Kami kirim kode OTP ke email Anda</div>
                                </div>
                            </div>
                            <div class="flow-item pending" id="fl2">
                                <div class="flow-dot" id="fd2">2</div>
                                <div class="flow-content">
                                    <div class="flow-title">Verifikasi OTP</div>
                                    <div class="flow-desc">Masukkan 6 digit kode yang dikirim</div>
                                </div>
                            </div>
                            <div class="flow-item pending" id="fl3">
                                <div class="flow-dot" id="fd3">3</div>
                                <div class="flow-content">
                                    <div class="flow-title">Buat Password Baru</div>
                                    <div class="flow-desc">Tentukan password yang kuat & aman</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="security-note">
                        <i class="bi bi-shield-check-fill"></i>
                        <span>Kode OTP hanya berlaku selama <strong style="color:var(--text)">10 menit</strong> dan hanya
                            dapat digunakan satu kali untuk menjaga keamanan akun Anda.</span>
                    </div>

                </div>

                <!-- ══ KANAN: Form Panel ══ -->
                <div class="col-12 col-lg-7 form-panel">
                    <div class="form-scroll-area">

                        <!-- Progress dots -->
                        <div class="progress-dots">
                            <div class="pdot active" id="pd1"></div>
                            <div class="pdot" id="pd2"></div>
                            <div class="pdot" id="pd3"></div>
                        </div>

                        <!-- ══ STEP 1: Email ══ -->
                        <div class="step-panel active" id="stepEmail">

                            <a href="{{ route('login') }}" class="back-link">
                                <i class="bi bi-arrow-left"></i> Kembali ke Login
                            </a>

                            <div class="form-header">
                                <div class="welcome-tag">
                                    <span class="status-dot"></span>
                                    Langkah 1 dari 3
                                </div>
                                <h1>Lupa Password?</h1>
                                <p>Tidak perlu khawatir. Masukkan email yang terdaftar dan kami akan mengirimkan kode
                                    verifikasi.</p>
                            </div>

                            <div class="info-box">
                                <i class="bi bi-info-circle-fill"></i>
                                <p>Pastikan Anda memasukkan email yang <strong>terdaftar di sistem</strong>. Kode OTP akan
                                    dikirim ke inbox email tersebut.</p>
                            </div>

                            <form id="formEmail" data-url="{{ route('password.email') }}" novalidate>
                                @csrf
                                <div class="field-group">
                                    <label class="field-label" for="email">Alamat Email</label>
                                    <div class="input-wrap">
                                        <i class="bi bi-envelope input-icon"></i>
                                        <input type="email" id="email" name="email" class="form-input"
                                            placeholder="nama@email.com" autocomplete="email" required />
                                        <span class="input-line"></span>
                                    </div>
                                    <div class="field-msg" id="emailMsg"></div>
                                </div>

                                <button type="submit" class="btn-submit" id="btnEmail">
                                    <span><i class="bi bi-send-fill"></i> Kirim Kode OTP</span>
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

                            <div class="login-back" style="margin-top:20px">
                                Sudah ingat password? <a href="{{ route('login') }}">Masuk di sini</a>
                            </div>
                        </div>

                        <!-- ══ STEP 2: OTP (Placeholder for now) ══ -->
                        <div class="step-panel" id="stepOtp">

                            <button class="back-link" type="button" onclick="goTo(1)">
                                <i class="bi bi-arrow-left"></i> Ganti email
                            </button>

                            <div class="form-header">
                                <div class="welcome-tag">
                                    <span class="status-dot"></span>
                                    Langkah 2 dari 3
                                </div>
                                <h1>Cek Email Anda</h1>
                                <p id="otpSubtitle">Kode OTP 6 digit telah dikirim ke <strong
                                        style="color:var(--cyan)">–</strong></p>
                            </div>

                            <div class="info-box" style="margin-bottom:20px">
                                <i class="bi bi-envelope-open-fill"></i>
                                <p>Periksa folder <strong>Inbox</strong> atau <strong>Spam</strong>. Kode berlaku selama
                                    <strong>10 menit</strong>.</p>
                            </div>

                            <form id="formOtp" novalidate>
                                <div class="field-group" style="animation-delay:0.3s">
                                    <label class="field-label"
                                        style="justify-content:center;margin-bottom:14px">Masukkan Kode OTP</label>
                                    <div class="otp-row">
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o1" />
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o2" />
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o3" />
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o4" />
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o5" />
                                        <input class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                            id="o6" />
                                    </div>
                                    <div class="field-msg" id="otpMsg" style="justify-content:center;margin-top:8px">
                                    </div>
                                </div>

                                <div class="resend-wrap">
                                    <span id="resendText">Kirim ulang kode dalam</span>
                                    <span id="countdown"
                                        style="color:var(--cyan);font-family:var(--font-mono);font-weight:600">02:00</span>
                                    <button type="button" class="btn-resend" id="btnResend" disabled
                                        onclick="startResend()">Kirim Ulang</button>
                                </div>

                                <button type="submit" class="btn-submit" id="btnOtp" style="margin-top:24px">
                                    <span><i class="bi bi-shield-check"></i> Verifikasi Kode</span>
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
                        </div>

                        <!-- ══ STEP 3: New Password ══ -->
                        <div class="step-panel" id="stepNewPass">

                            <button class="back-link" type="button" onclick="goTo(2)">
                                <i class="bi bi-arrow-left"></i> Kembali ke OTP
                            </button>

                            <div class="form-header">
                                <div class="welcome-tag">
                                    <span class="status-dot"></span>
                                    Langkah 3 dari 3
                                </div>
                                <h1>Buat Password Baru</h1>
                                <p>Buat password baru yang kuat dan berbeda dari sebelumnya.</p>
                            </div>

                            <form id="formNewPass" novalidate>

                                <!-- Password Baru -->
                                <div class="field-group">
                                    <label class="field-label" for="newPass">Password Baru</label>
                                    <div class="input-wrap">
                                        <i class="bi bi-lock input-icon"></i>
                                        <input type="password" id="newPass" class="form-input"
                                            placeholder="Minimal 8 karakter" autocomplete="new-password" required />
                                        <button type="button" class="input-icon-right" id="toggleNew">
                                            <i class="bi bi-eye" id="eyeNew"></i>
                                        </button>
                                        <span class="input-line"></span>
                                    </div>
                                    <!-- Strength -->
                                    <div class="strength-wrap" id="strengthWrap" style="display:none">
                                        <div class="strength-bar">
                                            <div class="strength-fill" id="strengthFill"></div>
                                        </div>
                                        <div class="strength-label">
                                            <span id="strengthText">Kekuatan password</span>
                                            <span id="strengthPct"></span>
                                        </div>
                                        <div class="strength-checks">
                                            <div class="strength-check" id="chkLen"><i class="bi bi-x-circle"></i> Min. 8
                                                karakter</div>
                                            <div class="strength-check" id="chkUpper"><i class="bi bi-x-circle"></i> Huruf
                                                besar</div>
                                            <div class="strength-check" id="chkNum"><i class="bi bi-x-circle"></i> Angka
                                            </div>
                                            <div class="strength-check" id="chkSym"><i class="bi bi-x-circle"></i> Simbol
                                                (!@#$)</div>
                                        </div>
                                    </div>
                                    <div class="field-msg" id="newPassMsg"></div>
                                </div>

                                <!-- Konfirmasi -->
                                <div class="field-group">
                                    <label class="field-label" for="confirmNewPass">Konfirmasi Password</label>
                                    <div class="input-wrap">
                                        <i class="bi bi-lock-fill input-icon"></i>
                                        <input type="password" id="confirmNewPass" class="form-input"
                                            placeholder="Ulangi password baru" autocomplete="new-password" required />
                                        <button type="button" class="input-icon-right" id="toggleConfirm">
                                            <i class="bi bi-eye" id="eyeConfirm"></i>
                                        </button>
                                        <span class="input-line"></span>
                                    </div>
                                    <div class="field-msg" id="confirmNewMsg"></div>
                                </div>

                                <button type="submit" class="btn-submit" id="btnNewPass">
                                    <span><i class="bi bi-check-circle-fill"></i> Simpan Password Baru</span>
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
                        </div>

                        <!-- ══ STEP 4: Sukses ══ -->
                        <div class="step-panel" id="stepDone">
                            <div class="success-panel">
                                <div class="success-icon-wrap">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <h3>Instruksi Terkirim!</h3>
                                <p>Kami telah mengirimkan email instruksi pemulihan password. Silakan periksa inbox atau
                                    folder spam Anda.</p>
                            </div>
                            <div style="margin-top:32px">
                                <a href="{{ route('login') }}" class="btn-submit"
                                    style="display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;color:#fff">
                                    <i class="bi bi-box-arrow-in-right"></i> Kembali ke Login
                                </a>
                            </div>
                            <div class="login-back" style="margin-top:20px">
                                Butuh bantuan? <a href="#">Hubungi Support</a>
                            </div>
                        </div>

                        <div class="sys-info" id="sysInfo">
                            PMS v2.0 &mdash; <span>secure connection</span> &mdash; &copy; 2025
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
