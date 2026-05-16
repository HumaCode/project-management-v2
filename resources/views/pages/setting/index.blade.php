<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/setting.css') }}?v={{ time() }}">
        <style>
            .pg-hd { display: flex !important; justify-content: space-between !important; align-items: center !important; flex-wrap: wrap; gap: 20px; margin-bottom: 30px; }
            .pg-hd-left { display: flex !important; align-items: center !important; gap: 18px; }
            .pg-ico { 
                width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0; 
                background: linear-gradient(135deg, rgba(0, 200, 255, 0.15), rgba(0, 114, 198, 0.15)); 
                border: 1px solid rgba(0, 200, 255, 0.25); 
                display: grid; place-items: center; font-size: 24px; color: var(--cyan); 
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2), inset 0 0 10px rgba(0, 200, 255, 0.1);
                transition: all 0.3s ease;
            }
            .pg-hd:hover .pg-ico { transform: scale(1.05) rotate(5deg); border-color: rgba(0, 200, 255, 0.5); box-shadow: 0 10px 25px rgba(0, 200, 255, 0.2); }
            .pg-title { font-size: 22px !important; font-weight: 800 !important; color: #fff; letter-spacing: -0.5px; line-height: 1.1; margin-bottom: 4px; }
            .pg-sub { font-size: 13px !important; color: var(--muted) !important; font-family: var(--font); opacity: 0.8; }
            .pg-actions { margin-left: auto; }
            .bc { display: flex !important; align-items: center !important; gap: 8px; font-family: var(--mono); font-size: 11px; color: var(--muted); background: rgba(255,255,255,0.03); padding: 6px 14px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05); }
            .bc i { font-size: 13px; }
            .bc .sep { opacity: 0.4; font-size: 10px; margin: 0 2px; }
            .bc .here { color: var(--cyan); font-weight: 600; }
            .bc a { color: var(--muted); text-decoration: none; transition: color 0.2s; }
            .bc a:hover { color: var(--cyan); }
            
            .input-with-icon { position: relative; }
            .input-with-icon i { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
            .fi.datetimepicker { padding-right: 40px !important; }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script src="{{ asset('assets/auth/backend/js/setting.js') }}?v={{ time() }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-md-flex">
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i> Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">Pengaturan</span>
            </div>
        </div>
    </div>

    <!-- ══════════════ OVERVIEW GRID ══════════════ -->
    <div id="overview">
        <div class="cat-grid">

            <!-- Profil Sistem -->
            <div class="cat-card cc-cyan" onclick="showPane('pane-profil')" data-target="pane-profil" data-aos="fade-up" data-aos-delay="0">
                <div class="cat-ico-wrap ci-cyan"><i class="bi bi-globe2"></i></div>
                <div class="cat-title">Profil Sistem</div>
                <div class="cat-desc">Kelola identitas aplikasi, zona waktu, bahasa antarmuka, dan informasi dasar platform.</div>
                <div class="cat-tags">
                    <span class="cat-tag">Nama App</span>
                    <span class="cat-tag">Logo</span>
                    <span class="cat-tag">Timezone</span>
                    <span class="cat-tag">Bahasa</span>
                    <span class="cat-tag">Deskripsi</span>
                </div>
                <div class="cat-footer">
                    <span class="cat-count">{{ $counts['profile'] ?? 0 }} pengaturan</span>
                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>

            <!-- Keamanan -->
            <div class="cat-card cc-ok" onclick="showPane('pane-keamanan')" data-target="pane-keamanan" data-aos="fade-up" data-aos-delay="60">
                <div class="cat-ico-wrap ci-ok"><i class="bi bi-shield-fill-check"></i></div>
                <div class="cat-title">Keamanan</div>
                <div class="cat-desc">Atur kebijakan password, batas percobaan login, session timeout, dan autentikasi dua faktor global.</div>
                <div class="cat-tags">
                    <span class="cat-tag">Password Policy</span>
                    <span class="cat-tag">2FA Global</span>
                    <span class="cat-tag">Session</span>
                    <span class="cat-tag">Login Attempt</span>
                </div>
                <div class="cat-footer">
                    <span class="cat-count">{{ $counts['security'] ?? 0 }} pengaturan</span>
                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>

            <!-- Email / SMTP -->
            <div class="cat-card cc-warn" onclick="showPane('pane-email')" data-target="pane-email" data-aos="fade-up" data-aos-delay="120">
                <div class="cat-ico-wrap ci-warn"><i class="bi bi-envelope-fill"></i></div>
                <div class="cat-title">Email / SMTP</div>
                <div class="cat-desc">Konfigurasi server email outgoing dan pengaturan template notifikasi otomatis ke pengguna.</div>
                <div class="cat-tags">
                    <span class="cat-tag">SMTP Server</span>
                    <span class="cat-tag">Port</span>
                    <span class="cat-tag">Auth</span>
                    <span class="cat-tag">Template</span>
                </div>
                <div class="cat-footer">
                    <span class="cat-count">{{ $counts['email'] ?? 0 }} pengaturan</span>
                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>

            <!-- Backup & Maintenance -->
            <div class="cat-card cc-purple" onclick="showPane('pane-backup')" data-target="pane-backup" data-aos="fade-up" data-aos-delay="180">
                <div class="cat-ico-wrap ci-purple"><i class="bi bi-database-fill-gear"></i></div>
                <div class="cat-title">Backup & Maintenance</div>
                <div class="cat-desc">Jadwalkan backup database otomatis, aktifkan mode maintenance, dan pantau log aktivitas sistem.</div>
                <div class="cat-tags">
                    <span class="cat-tag">Auto Backup</span>
                    <span class="cat-tag">Maintenance Mode</span>
                    <span class="cat-tag">Log Sistem</span>
                </div>
                <div class="cat-footer">
                    <span class="cat-count">{{ $counts['maintenance'] ?? 0 }} pengaturan</span>
                    <div class="cat-arrow"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>

        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         PANE: PROFIL SISTEM
    ══════════════════════════════════════════════ -->
    <div class="detail-pane" id="pane-profil">
        <div class="back-bar"><i class="bi bi-arrow-left"></i> Kembali ke Pengaturan</div>
        <div class="det-hd" style="--det-grad:linear-gradient(90deg,transparent,var(--cyan),transparent)">
            <div class="det-hd-ico ci-cyan"><i class="bi bi-globe2"></i></div>
            <div>
                <div class="det-hd-title">Profil Sistem</div>
                <div class="det-hd-sub">Identitas dan konfigurasi dasar aplikasi</div>
            </div>
            <span class="det-hd-badge"><i class="bi bi-circle-fill" style="font-size:6px;color:var(--ok);margin-right:4px"></i>Aktif</span>
        </div>

        <form id="formProfile" enctype="multipart/form-data">
            @csrf
            <!-- Identitas Aplikasi -->
            <div class="sec-card" data-aos="fade-up">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-app-indicator"></i> Identitas Aplikasi</div>
                </div>
                <div class="sec-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Nama Aplikasi <span class="req">*</span></label>
                                <input type="text" name="app_name" class="fi" value="{{ $settings['app_name'] ?? 'Project Management System' }}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Versi</label>
                                <input type="text" name="app_version" class="fi" value="{{ $settings['app_version'] ?? 'v2.0.0' }}" readonly style="opacity:.6;cursor:not-allowed" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="fg">
                                <label class="fl">Deskripsi Singkat</label>
                                <textarea name="app_description" class="fta">{{ $settings['app_description'] ?? 'Platform manajemen proyek terpadu...' }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">URL Aplikasi</label>
                                <div class="fi-wrap">
                                    <i class="bi bi-link-45deg fi-ico"></i>
                                    <input type="url" name="app_url" class="fi" value="{{ $settings['app_url'] ?? url('/') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Email Kontak Admin</label>
                                <div class="fi-wrap">
                                    <i class="bi bi-envelope-fill fi-ico"></i>
                                    <input type="email" name="app_email" class="fi" value="{{ $settings['app_email'] ?? 'admin@pmssystem.id' }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo & Favicon -->
            <div class="sec-card" data-aos="fade-up" data-aos-delay="40">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-image-fill"></i> Logo & Favicon</div>
                </div>
                <div class="sec-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Logo Utama</label>
                                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(0,200,255,.04);border:1px solid var(--bd);border-radius:var(--rs);margin-bottom:8px">
                                    <div id="logoWrapper" style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:grid;place-items:center;font-size:20px;color:#fff;flex-shrink:0;overflow:hidden">
                                        <img src="{{ $logo ?? '' }}" id="previewLogo" style="width:100%;height:100%;object-fit:contain;{{ $logo ? '' : 'display:none' }}" />
                                        <i class="bi bi-image" id="iconLogo" style="{{ $logo ? 'display:none' : '' }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600" id="nameLogo">{{ $logo ? 'logo-pms.png' : 'Belum ada file' }}</div>
                                        <div style="font-family:var(--mono);font-size:11px;color:var(--muted)">PNG &bull; Maks 500KB</div>
                                    </div>
                                </div>
                                <input type="file" name="logo" accept="image/*" style="display:none" id="logoUpload" />
                                <button type="button" class="btn-cancel" onclick="document.getElementById('logoUpload').click()" style="width:100%;justify-content:center"><i class="bi bi-cloud-upload-fill"></i> Ganti Logo</button>
                                <div class="fg-note">PNG/SVG transparan. Rekomendasi: 280×80px.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Favicon</label>
                                <div style="display:flex;align-items:center;gap:12px;padding:12px;background:rgba(0,200,255,.04);border:1px solid var(--bd);border-radius:var(--rs);margin-bottom:8px">
                                    <div id="favWrapper" style="width:32px;height:32px;border-radius:6px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:grid;place-items:center;font-size:14px;color:#fff;flex-shrink:0;overflow:hidden">
                                        <img src="{{ $favicon ?? '' }}" id="previewFav" style="width:100%;height:100%;object-fit:contain;{{ $favicon ? '' : 'display:none' }}" />
                                        <i class="bi bi-app" id="iconFav" style="{{ $favicon ? 'display:none' : '' }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600" id="nameFav">{{ $favicon ? 'favicon.ico' : 'Belum ada file' }}</div>
                                        <div style="font-family:var(--mono);font-size:11px;color:var(--muted)">ICO/PNG &bull; 32×32px</div>
                                    </div>
                                </div>
                                <input type="file" name="favicon" accept=".ico,image/*" style="display:none" id="favUpload" />
                                <div style="display:flex;gap:8px">
                                    <button type="button" class="btn-cancel" onclick="document.getElementById('favUpload').click()" style="flex:1;justify-content:center"><i class="bi bi-cloud-upload-fill"></i> Ganti Favicon</button>
                                    <a href="https://favicon.io/" target="_blank" class="btn-cancel" style="width:42px;padding:0;justify-content:center;color:var(--cyan)" title="Penjelasan Favicon & Resource"><i class="bi bi-info-circle"></i></a>
                                </div>
                                <div class="fg-note">Format .ico atau PNG 32×32px.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lokalisasi -->
            <div class="sec-card" data-aos="fade-up" data-aos-delay="80">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-translate"></i> Lokalisasi</div>
                </div>
                <div class="sec-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Bahasa Default</label>
                                <select name="language" class="fsl">
                                    <option value="id" {{ ($settings['language'] ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="en" {{ ($settings['language'] ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Zona Waktu</label>
                                <select name="timezone" class="fsl">
                                    <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                    <option value="Asia/Makassar" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                    <option value="Asia/Jayapura" {{ ($settings['timezone'] ?? 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (UTC+9)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Format Tanggal</label>
                                <select name="date_format" class="fsl">
                                    <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                    <option value="m/d/Y" {{ ($settings['date_format'] ?? 'd/m/Y') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                    <option value="Y-m-d" {{ ($settings['date_format'] ?? 'd/m/Y') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="save-row">
                        <button type="reset" class="btn-cancel">Reset</button>
                        <button type="submit" class="btn-save" id="btnSaveProfile"><span><i class="bi bi-floppy-fill"></i> Simpan Profil</span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════
         PANE: KEAMANAN
    ══════════════════════════════════════════════ -->
    <div class="detail-pane" id="pane-keamanan">
        <div class="back-bar"><i class="bi bi-arrow-left"></i> Kembali ke Pengaturan</div>
        <div class="det-hd" style="--det-grad:linear-gradient(90deg,transparent,var(--ok),transparent)">
            <div class="det-hd-ico ci-ok"><i class="bi bi-shield-fill-check"></i></div>
            <div>
                <div class="det-hd-title">Keamanan</div>
                <div class="det-hd-sub">Kebijakan autentikasi dan akses sistem</div>
            </div>
            <span class="det-hd-badge" style="color:var(--ok)"><i class="bi bi-circle-fill" style="font-size:6px;color:var(--ok);margin-right:4px"></i>Terkonfigurasi</span>
        </div>

        <form id="formSecurity">
            @csrf
            <!-- Password Policy -->
            <div class="sec-card" data-aos="fade-up">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-key-fill"></i> Kebijakan Password</div>
                </div>
                <div class="sec-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Panjang Minimum</label>
                                <input type="number" name="password_min_length" class="fi" value="{{ $settings['password_min_length'] ?? '8' }}" min="6" max="32" />
                                <div class="fg-note">Minimal 6 karakter</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Karakter Spesial</label>
                                <div class="sw-row">
                                    <div class="sw-left">
                                        <div class="sw-title">Wajibkan Simbol</div>
                                    </div>
                                    <label class="sw-wrap sw-ok">
                                        <input type="checkbox" name="password_require_symbol" value="1" {{ ($settings['password_require_symbol'] ?? '1') == '1' ? 'checked' : '' }} />
                                        <span class="sw-track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Kombinasi Angka</label>
                                <div class="sw-row">
                                    <div class="sw-left">
                                        <div class="sw-title">Wajibkan Angka</div>
                                    </div>
                                    <label class="sw-wrap sw-ok">
                                        <input type="checkbox" name="password_require_number" value="1" {{ ($settings['password_require_number'] ?? '1') == '1' ? 'checked' : '' }} />
                                        <span class="sw-track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Auth -->
            <div class="sec-card" data-aos="fade-up" data-aos-delay="40">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-shield-lock-fill"></i> Global Authentication</div>
                </div>
                <div class="sec-card-body">
                    <div class="sw-row">
                        <div class="sw-left">
                            <div class="sw-title">Wajibkan 2FA / OTP Login</div>
                            <div class="sw-sub">Gunakan verifikasi kode melalui Email untuk setiap login.</div>
                        </div>
                        <label class="sw-wrap sw-ok">
                            <input type="checkbox" name="enable_otp" value="1" {{ ($settings['enable_otp'] ?? '0') == '1' ? 'checked' : '' }} />
                            <span class="sw-track"></span>
                        </label>
                    </div>
                    <div class="sw-row">
                        <div class="sw-left">
                            <div class="sw-title">Login via Google</div>
                            <div class="sw-sub">Izinkan pengguna masuk menggunakan akun Google (Socialite).</div>
                        </div>
                        <label class="sw-wrap sw-ok">
                            <input type="checkbox" name="enable_google_login" value="1" {{ ($settings['enable_google_login'] ?? '1') == '1' ? 'checked' : '' }} />
                            <span class="sw-track"></span>
                        </label>
                    </div>
                    <div class="sw-row">
                        <div class="sw-left">
                            <div class="sw-title">Izinkan Registrasi Publik</div>
                            <div class="sw-sub">Buka form pendaftaran untuk pengguna baru di halaman login.</div>
                        </div>
                        <label class="sw-wrap sw-ok">
                            <input type="checkbox" name="allow_registration" value="1" {{ ($settings['allow_registration'] ?? '1') == '1' ? 'checked' : '' }} />
                            <span class="sw-track"></span>
                        </label>
                    </div>
                    <div class="sw-row">
                        <div class="sw-left">
                            <div class="sw-title">Approval Admin</div>
                            <div class="sw-sub">Setiap user baru yang mendaftar harus disetujui admin sebelum aktif.</div>
                        </div>
                        <label class="sw-wrap sw-ok">
                            <input type="checkbox" name="admin_approval" value="1" {{ ($settings['admin_approval'] ?? '1') == '1' ? 'checked' : '' }} />
                            <span class="sw-track"></span>
                        </label>
                    </div>
                    <div class="save-row">
                        <button type="submit" class="btn-save" id="btnSaveSecurity"><span><i class="bi bi-floppy-fill"></i> Simpan Keamanan</span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════
         PANE: EMAIL / SMTP
    ══════════════════════════════════════════════ -->
    <div class="detail-pane" id="pane-email">
        <div class="back-bar"><i class="bi bi-arrow-left"></i> Kembali ke Pengaturan</div>
        <div class="det-hd" style="--det-grad:linear-gradient(90deg,transparent,var(--warn),transparent)">
            <div class="det-hd-ico ci-warn"><i class="bi bi-envelope-fill"></i></div>
            <div>
                <div class="det-hd-title">Email / SMTP</div>
                <div class="det-hd-sub">Konfigurasi pengiriman notifikasi sistem</div>
            </div>
            <span class="det-hd-badge" style="color:var(--warn)"><i class="bi bi-circle-fill" style="font-size:6px;color:var(--ok);margin-right:4px"></i>Connected</span>
        </div>

        <form id="formEmail">
            @csrf
            <div class="sec-card" data-aos="fade-up">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-server"></i> SMTP Server Configuration</div>
                </div>
                <div class="sec-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <div class="fg">
                                <label class="fl">SMTP Host</label>
                                <input type="text" name="mail_host" class="fi" value="{{ $settings['mail_host'] ?? '' }}" placeholder="e.g. smtp.mailtrap.io" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="fg">
                                <label class="fl">Port</label>
                                <input type="number" name="mail_port" class="fi" value="{{ $settings['mail_port'] ?? '2525' }}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Username</label>
                                <input type="text" name="mail_username" class="fi" value="{{ $settings['mail_username'] ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Password</label>
                                <div class="pw-wrap">
                                    <input type="password" name="mail_password" class="fi" value="{{ $settings['mail_password'] ?? '' }}" />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">Encryption</label>
                                <select name="mail_encryption" class="fsl">
                                    <option value="none" {{ ($settings['mail_encryption'] ?? 'tls') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="fg">
                                <label class="fl">From Address</label>
                                <input type="email" name="mail_from_address" class="fi" value="{{ $settings['mail_from_address'] ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="fg">
                                <label class="fl">From Name</label>
                                <input type="text" name="mail_from_name" class="fi" value="{{ $settings['mail_from_name'] ?? 'Project Management System' }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sec-card" data-aos="fade-up" data-aos-delay="40">
                <div class="sec-card-hd">
                    <div class="sec-card-title"><i class="bi bi-send-check-fill"></i> Test Email Delivery</div>
                </div>
                <div class="sec-card-body">
                    <p style="font-size:12.5px;color:var(--dim);margin-bottom:12px">Kirim email percobaan untuk memastikan konfigurasi SMTP Anda sudah benar.</p>
                    <div class="smtp-test-row">
                        <input type="email" id="testEmailRecipient" class="fi" placeholder="Masukkan email penerima..." />
                        <button type="button" class="btn-test" id="btnTestMail"><i class="bi bi-send-fill"></i> Kirim Test</button>
                    </div>
                    <div class="save-row">
                        <button type="submit" class="btn-save" id="btnSaveEmail"><span><i class="bi bi-floppy-fill"></i> Simpan SMTP</span></button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════
         PANE: BACKUP & MAINTENANCE
    ══════════════════════════════════════════════ -->
    <div class="detail-pane" id="pane-backup">
        <div class="back-bar"><i class="bi bi-arrow-left"></i> Kembali ke Pengaturan</div>
        <div class="det-hd" style="--det-grad:linear-gradient(90deg,transparent,var(--purple),transparent)">
            <div class="det-hd-ico ci-purple"><i class="bi bi-database-fill-gear"></i></div>
            <div>
                <div class="det-hd-title">Backup & Maintenance</div>
                <div class="det-hd-sub">Keamanan data dan pemeliharaan platform</div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <!-- Backup Otomatis -->
                <div class="sec-card" data-aos="fade-up">
                    <div class="sec-card-hd">
                        <div class="sec-card-title"><i class="bi bi-database-fill-down"></i> Backup Otomatis</div>
                        <button class="btn-save" id="btnRunBackupManual" style="height:34px;padding:0 14px;font-size:12.5px" data-url="{{ route('settings.run-backup') }}">
                            <span><i class="bi bi-database-fill-down"></i> Backup Sekarang</span>
                        </button>
                    </div>
                    <div class="sec-card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <div class="fg">
                                    <label class="fl">Frekuensi Backup</label>
                                    <select class="fsl">
                                        <option>Setiap jam</option>
                                        <option selected>Setiap hari</option>
                                        <option>Setiap minggu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fg">
                                    <label class="fl">Jam Backup</label>
                                    <input type="time" class="fi" value="02:00" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fg">
                                    <label class="fl">Simpan Backup Selama</label>
                                    <select class="fsl">
                                        <option>7 hari</option>
                                        <option selected>30 hari</option>
                                        <option>90 hari</option>
                                        <option>Selamanya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fg">
                                    <label class="fl">Lokasi Penyimpanan</label>
                                    <select class="fsl">
                                        <option selected>Server Lokal</option>
                                        <option>Google Drive</option>
                                        <option>S3 / MinIO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="sw-row">
                            <div class="sw-left">
                                <div class="sw-title">Backup Otomatis Aktif</div>
                                <div class="sw-sub">Jalankan backup sesuai jadwal yang ditentukan</div>
                            </div>
                            <label class="sw-wrap sw-ok"><input type="checkbox" checked /><span class="sw-track"></span></label>
                        </div>
                        <div class="sw-row">
                            <div class="sw-left">
                                <div class="sw-title">Notifikasi Email setelah Backup</div>
                                <div class="sw-sub">Kirim laporan hasil backup ke admin</div>
                            </div>
                            <label class="sw-wrap sw-ok"><input type="checkbox" checked /><span class="sw-track"></span></label>
                        </div>
                        <div class="save-row">
                            <button class="btn-cancel">Reset</button>
                            <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan Jadwal</span></button>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Backup -->
                <div class="sec-card" data-aos="fade-up" data-aos-delay="40">
                    <div class="sec-card-hd">
                        <div class="sec-card-title"><i class="bi bi-clock-history"></i> Riwayat Backup</div>
                        <span class="det-hd-badge">{{ count($backups) }} file</span>
                    </div>
                    <div class="sec-card-body p-3" id="backupHistoryContainer" style="max-height: 400px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--cyan) rgba(255,255,255,0.05);">
                        @include('pages.setting.partials._backup_list')
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="sec-card" data-aos="fade-up">
                    <div class="sec-card-hd">
                        <div class="sec-card-title"><i class="bi bi-cone-striped"></i> Mode Maintenance</div>
                    </div>
                    <div class="sec-card-body">
                        <form id="formMaintenance">
                            @csrf
                            <div class="maint-toggle">
                                <div class="maint-ico"><i class="bi bi-cone-striped"></i></div>
                                <div class="maint-info">
                                    <div class="mt-title">Mode Maintenance</div>
                                    <div class="mt-sub">Redirect semua user ke halaman maintenance</div>
                                </div>
                                <label class="sw-big">
                                    <input type="checkbox" name="maintenance_mode" id="maintToggle" {{ ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }} />
                                    <span class="sw-big-track"></span>
                                </label>
                            </div>
                            <div class="fg">
                                <label class="fl">Pesan Maintenance</label>
                                <textarea class="fta" name="maintenance_message" placeholder="Sistem sedang dalam pemeliharaan. Kami akan segera kembali. Terima kasih atas kesabaran Anda.">{{ $settings['maintenance_message'] ?? '' }}</textarea>
                            </div>
                            <div class="fg">
                                <label class="fl">Estimasi Selesai</label>
                                <div class="input-with-icon">
                                    <input type="text" class="fi datetimepicker" id="maintEndTime" name="maintenance_end_time" value="{{ $settings['maintenance_end_time'] ?? '' }}" placeholder="Pilih tanggal dan jam..." />
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                            </div>
                            <div class="save-row">
                                <button type="submit" class="btn-save" id="btnSaveMaintenance"><span><i class="bi bi-floppy-fill"></i> Simpan</span></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Zona Bahaya -->
                <div class="sec-card danger-card" data-aos="fade-up" data-aos-delay="40">
                    <div class="sec-card-hd">
                        <div class="sec-card-title text-danger"><i class="bi bi-fire"></i> Zona Bahaya</div>
                    </div>
                    <div class="sec-card-body">
                        <div class="warn-box mb-3">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <p>Tindakan di bawah bersifat <strong>permanen dan tidak dapat dibatalkan</strong>. Lakukan hanya jika benar-benar diperlukan.</p>
                        </div>
                        <div class="sw-row border-0">
                            <div class="sw-left">
                                <div class="sw-title">Hapus Cache Sistem</div>
                                <div class="sw-sub">Bersihkan cache aplikasi & query</div>
                            </div>
                            <button class="btn-bk btn-bk-rm" style="height:32px;padding:0 12px"><i class="bi bi-trash3-fill"></i> Hapus Cache</button>
                        </div>
                        <div class="sw-row border-0">
                            <div class="sw-left">
                                <div class="sw-title">Reset ke Default</div>
                                <div class="sw-sub">Kembalikan semua pengaturan</div>
                            </div>
                            <button class="btn-bk btn-bk-rm" style="height:32px;padding:0 12px"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Aktivitas Sistem -->
        <div class="tbl-card" data-aos="fade-up" data-aos-delay="80" style="margin-top: 30px;">
            <div class="sec-card-hd" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="sec-card-title"><i class="bi bi-journal-code"></i> Log Aktivitas Sistem</div>
                <div class="bk-actions">
                    <button type="button" class="btn-bk" id="btnToggleFilter"><i class="bi bi-filter"></i> Filter</button>
                    <button type="button" class="btn-bk" id="btnExportLog" data-url="{{ route('settings.activities.export') }}"><i class="bi bi-download"></i> Export</button>
                </div>
            </div>

            <!-- Filter Bar -->
            <div id="filterBar" class="p-3" style="display: none; background: rgba(0,200,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="row g-2">
                    <div class="col-md-4">
                        <div class="input-with-icon">
                            <input type="text" class="fi datetimepicker" id="filterDate" placeholder="Pilih Rentang Tanggal..." readonly>
                            <i class="bi bi-calendar3"></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="filter-select w-100" id="filterEvent" style="height: 38px;">
                            <option value="">Semua Aksi</option>
                            <option value="created">Menambahkan</option>
                            <option value="updated">Mengubah</option>
                            <option value="deleted">Menghapus</option>
                            <option value="login">Login</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="filter-select w-100" id="filterModule" style="height: 38px;">
                            <option value="">Semua Modul</option>
                            <option value="default">System Log</option>
                            <option value="project">Project</option>
                            <option value="document">Document</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn-bk w-100 btn-bk-rm" id="btnResetFilter" style="height: 38px;"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
                    </div>
                </div>
            </div>

            <!-- Export Bar -->
            <div id="exportBar" class="p-3" style="display: none; background: rgba(167, 139, 250, 0.03); border-bottom: 1px solid rgba(255,255,255,0.05);">
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-with-icon">
                            <input type="text" class="fi datetimepicker" id="exportDate" placeholder="Pilih Rentang Tanggal untuk Export (Kosongkan = Semua)..." readonly>
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn-bk w-100" id="btnDownloadExcel" style="height: 38px; background: rgba(0, 229, 160, 0.1); border-color: rgba(0, 229, 160, 0.2); color: var(--ok);"><i class="bi bi-file-earmark-excel"></i> Download Excel</button>
                    </div>
                </div>
            </div>

            <div id="systemActivitiesContainer" data-url="{{ route('settings.activities') }}" style="transition: opacity .3s ease;">
                <div class="text-center py-5">
                    <div class="spinner-border text-cyan spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2" style="font-size: 12px; color: var(--dim); font-family: var(--mono);">SYNCHRONIZING LOGS...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Backup Modal removed for SCA.confirm -->

    <!-- Reset Modal -->
    <div class="modal fade m-dark" id="resetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-drain"><div class="drain-fill" id="drainReset"></div></div>
                <div class="m-hd">
                    <h5 class="m-hd-title"><i class="bi bi-exclamation-triangle-fill"></i> Reset Seluruh Data?</h5>
                    <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="m-bd">
                    <div class="warn-box mb-3">
                        <i class="bi bi-shield-lock-fill"></i>
                        <p>Aksi ini akan menghapus <strong>seluruh database</strong>. Semua proyek, dokumen, dan user (kecuali admin utama) akan hilang permanen.</p>
                    </div>
                    <div class="fg">
                        <label class="fl">Ketik "RESET SISTEM" untuk konfirmasi</label>
                        <input type="text" class="fi" id="resetConfirmInput" placeholder="Tulis di sini..." autocomplete="off" />
                    </div>
                </div>
                <div class="m-ft">
                    <button class="btn-mcancel" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-mdel" id="btnResetConfirm" disabled><i class="bi bi-trash-fill"></i> RESET SEKARANG</button>
                </div>
            </div>
        </div>
    </div>

</x-master-layout>
