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
                            <div class="flow-item {{ !session('status') ? 'active' : 'done' }}">
                                <div class="flow-dot">@if(!session('status')) 1 @else <i class="bi bi-check-lg" style="font-size:12px"></i> @endif</div>
                                <div class="flow-content">
                                    <div class="flow-title">Masukkan Email</div>
                                    <div class="flow-desc">Kirim tautan pemulihan ke email Anda</div>
                                </div>
                            </div>
                            <div class="flow-item {{ session('status') ? 'active' : 'pending' }}">
                                <div class="flow-dot">2</div>
                                <div class="flow-content">
                                    <div class="flow-title">Cek Inbox</div>
                                    <div class="flow-desc">Klik tautan untuk membuat password baru</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="security-note">
                        <i class="bi bi-shield-check-fill"></i>
                        <span>Gunakan password yang kuat dan unik untuk menjaga keamanan akun Anda dari akses yang tidak sah.</span>
                    </div>

                </div>

                <!-- ══ KANAN: Form Panel ══ -->
                <div class="col-12 col-lg-7 form-panel">
                    <div class="form-scroll-area">

                        @if (session('status'))
                            <!-- ══ SUKSES: Link Terkirim ══ -->
                            <div class="step-panel active">
                                <div class="success-panel">
                                    <div class="success-icon-wrap">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <h3>Tautan Terkirim!</h3>
                                    <p>{{ session('status') }}</p>
                                </div>
                                <div style="margin-top:32px">
                                    <a href="{{ route('login') }}" class="btn-submit"
                                        style="display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;color:#fff">
                                        <i class="bi bi-box-arrow-in-right"></i> Kembali ke Login
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- ══ FORM: Masukkan Email ══ -->
                            <div class="step-panel active">

                                <a href="{{ route('login') }}" class="back-link">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Login
                                </a>

                                <div class="form-header">
                                    <div class="welcome-tag">
                                        <span class="status-dot"></span>
                                        Lupa Password?
                                    </div>
                                    <h1>Atur Ulang Password</h1>
                                    <p>Tidak perlu khawatir. Masukkan email yang terdaftar dan kami akan mengirimkan tautan pemulihan.</p>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <p>Sistem akan mengirimkan email berisi instruksi dan tautan khusus untuk mengganti password Anda secara aman.</p>
                                </div>

                                <form method="POST" action="{{ route('password.email') }}" id="formEmail" novalidate>
                                    @csrf
                                    <div class="field-group">
                                        <label class="field-label" for="email">Alamat Email</label>
                                        <div class="input-wrap">
                                            <i class="bi bi-envelope input-icon"></i>
                                            <input type="email" id="email" name="email" class="form-input"
                                                placeholder="nama@email.com" value="{{ old('email') }}" required autofocus />
                                            <span class="input-line"></span>
                                        </div>
                                        @error('email')
                                            <div class="field-msg error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn-submit" id="btnEmail">
                                        <span><i class="bi bi-send-fill"></i> Kirim Tautan Pemulihan</span>
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
                        @endif

                        <div class="sys-info">
                            PMS v2.0 &mdash; <span>secure connection</span> &mdash; &copy; 2025
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

            </div>
        </div>
    </div>
</x-guest-layout>
