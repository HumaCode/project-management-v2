<x-master-layout>
    @push('css')
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
        </style>
    @endpush

    @push('js')
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
                    <span class="cat-count">5 pengaturan</span>
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
                    <span class="cat-count">8 pengaturan</span>
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
                    <span class="cat-count">6 pengaturan</span>
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
                    <span class="cat-count">4 pengaturan</span>
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
                            <input type="number" class="fi" value="8" min="6" max="32" />
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
                                    <input type="checkbox" checked />
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
                                    <input type="checkbox" checked />
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
                        <div class="sw-title">Wajibkan 2FA (Semua User)</div>
                        <div class="sw-sub">Paksa seluruh pengguna untuk mengaktifkan OTP via Email/App.</div>
                    </div>
                    <label class="sw-wrap">
                        <input type="checkbox" />
                        <span class="sw-track"></span>
                    </label>
                </div>
                <div class="sw-row">
                    <div class="sw-left">
                        <div class="sw-title">Izinkan Registrasi Publik</div>
                        <div class="sw-sub">Buka form pendaftaran untuk pengguna baru di halaman login.</div>
                    </div>
                    <label class="sw-wrap sw-ok">
                        <input type="checkbox" checked />
                        <span class="sw-track"></span>
                    </label>
                </div>
                <div class="sw-row">
                    <div class="sw-left">
                        <div class="sw-title">Approval Admin</div>
                        <div class="sw-sub">Setiap user baru yang mendaftar harus disetujui admin.</div>
                    </div>
                    <label class="sw-wrap sw-ok">
                        <input type="checkbox" checked />
                        <span class="sw-track"></span>
                    </label>
                </div>
                <div class="save-row">
                    <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan Keamanan</span></button>
                </div>
            </div>
        </div>
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

        <div class="sec-card" data-aos="fade-up">
            <div class="sec-card-hd">
                <div class="sec-card-title"><i class="bi bi-server"></i> SMTP Server Configuration</div>
            </div>
            <div class="sec-card-body">
                <div class="row g-3">
                    <div class="col-12 col-md-8">
                        <div class="fg">
                            <label class="fl">SMTP Host</label>
                            <input type="text" class="fi" value="smtp.pmssystem.id" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="fg">
                            <label class="fl">Port</label>
                            <input type="number" class="fi" value="587" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="fg">
                            <label class="fl">Username</label>
                            <input type="text" class="fi" value="notifications@pmssystem.id" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="fg">
                            <label class="fl">Password</label>
                            <div class="pw-wrap">
                                <input type="password" class="fi" value="••••••••••••" />
                                <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="fg">
                            <label class="fl">Encryption</label>
                            <select class="fsl">
                                <option>None</option>
                                <option>SSL</option>
                                <option selected>TLS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="fg">
                            <label class="fl">From Address</label>
                            <input type="email" class="fi" value="noreply@pmssystem.id" />
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
                    <input type="email" class="fi" placeholder="Masukkan email penerima..." />
                    <button class="btn-test"><i class="bi bi-send-fill"></i> Kirim Test</button>
                </div>
                <div class="save-row">
                    <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan SMTP</span></button>
                </div>
            </div>
        </div>
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

        <!-- Maintenance Mode -->
        <div class="sec-card" data-aos="fade-up">
            <div class="sec-card-hd">
                <div class="sec-card-title"><i class="bi bi-tools"></i> Maintenance Mode</div>
            </div>
            <div class="sec-card-body">
                <div class="maint-toggle">
                    <div class="maint-ico"><i class="bi bi-cone-striped"></i></div>
                    <div class="maint-info">
                        <div class="mt-title">Aktifkan Mode Pemeliharaan</div>
                        <div class="mt-sub">Aplikasi hanya dapat diakses oleh Admin.</div>
                    </div>
                    <label class="sw-big">
                        <input type="checkbox" id="maintToggle" />
                        <span class="sw-big-track"></span>
                    </label>
                </div>
                <div class="warn-box" id="maintWarn" style="display:none;margin-bottom:14px">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <p><strong>Perhatian:</strong> Pengguna umum akan dialihkan ke halaman "Maintenance" dan tidak dapat melakukan aktivitas apapun sampai mode ini dimatikan.</p>
                </div>
                <div class="fg">
                    <label class="fl">Pesan Maintenance kustom</label>
                    <textarea class="fta" placeholder="Contoh: Kami sedang melakukan pembaruan sistem rutin. Mohon kembali lagi nanti."></textarea>
                </div>
                <div class="save-row">
                    <button class="btn-save"><span><i class="bi bi-power"></i> Update Status</span></button>
                </div>
            </div>
        </div>

        <!-- Auto Backup -->
        <div class="sec-card" data-aos="fade-up" data-aos-delay="40">
            <div class="sec-card-hd">
                <div class="sec-card-title"><i class="bi bi-clock-history"></i> Automatic Database Backup</div>
                <button class="btn-save" data-bs-toggle="modal" data-bs-target="#backupModal" style="height:32px;padding:0 12px;font-size:11.5px">
                    <span><i class="bi bi-play-circle-fill"></i> Backup Sekarang</span>
                </button>
            </div>
            <div class="sec-card-body">
                <div class="sw-row">
                    <div class="sw-left">
                        <div class="sw-title">Jadwalkan Backup Harian</div>
                        <div class="sw-sub">Database akan di-backup setiap jam 02:00 WIB.</div>
                    </div>
                    <label class="sw-wrap sw-ok">
                        <input type="checkbox" checked />
                        <span class="sw-track"></span>
                    </label>
                </div>
                <div class="fg mt-3">
                    <label class="fl">Penyimpanan Cloud (S3/G Drive)</label>
                    <div class="info-box ib-cyan">
                        <i class="bi bi-info-circle-fill"></i>
                        <p>Fitur sinkronisasi ke Google Drive sedang dalam tahap pengembangan (v2.1).</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="fl">5 Backup Terakhir</label>
                    <div class="backup-card">
                        <div class="bk-ico"><i class="bi bi-file-earmark-zip-fill"></i></div>
                        <div>
                            <div class="bk-nm">db_backup_2026-05-15.sql.gz</div>
                            <div class="bk-meta">Kemarin, 02:00 &bull; 12.4 MB</div>
                        </div>
                        <div class="bk-actions">
                            <button class="btn-bk btn-bk-dl"><i class="bi bi-download"></i></button>
                            <button class="btn-bk btn-bk-rm"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    </div>
                    <div class="backup-card">
                        <div class="bk-ico"><i class="bi bi-file-earmark-zip-fill"></i></div>
                        <div>
                            <div class="bk-nm">db_backup_2026-05-14.sql.gz</div>
                            <div class="bk-meta">14 Mei 2026, 02:00 &bull; 12.2 MB</div>
                        </div>
                        <div class="bk-actions">
                            <button class="btn-bk btn-bk-dl"><i class="bi bi-download"></i></button>
                            <button class="btn-bk btn-bk-rm"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="sec-card" style="border-color:rgba(255,77,109,.25)" data-aos="fade-up" data-aos-delay="80">
            <div class="sec-card-hd" style="background:rgba(255,77,109,.05)">
                <div class="sec-card-title" style="color:var(--err)"><i class="bi bi-fire"></i> Danger Zone</div>
            </div>
            <div class="sec-card-body">
                <div class="sw-row">
                    <div class="sw-left">
                        <div class="sw-title">Reset Seluruh Sistem</div>
                        <div class="sw-sub">Menghapus seluruh data proyek, dokumen, dan riwayat. (Wajib backup dulu!)</div>
                    </div>
                    <button class="btn-danger" data-bs-toggle="modal" data-bs-target="#resetModal"><i class="bi bi-exclamation-octagon-fill"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Backup Modal -->
    <div class="modal fade m-dark" id="backupModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="m-hd" style="border-bottom-color:rgba(0,200,255,.1)">
                    <h5 class="m-hd-title" style="color:var(--cyan)"><i class="bi bi-database-fill-down"></i> Konfirmasi Backup</h5>
                    <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="m-bd">
                    <p style="font-size:14px;color:var(--dim);line-height:1.6">Sistem akan melakukan kompresi database dan menyimpannya ke server. Proses ini mungkin memakan waktu beberapa menit tergantung ukuran data.</p>
                    <div class="fg mt-3">
                        <label class="fl">Nama File (Opsional)</label>
                        <input type="text" class="fi" placeholder="Manual_Backup_{{ date('Y-m-d') }}" />
                    </div>
                </div>
                <div class="m-ft" style="border-top-color:rgba(0,200,255,.1)">
                    <button class="btn-mcancel" data-bs-dismiss="modal">Batal</button>
                    <button class="btn-save" id="btnBackupConfirm" style="background:linear-gradient(135deg,var(--blue),var(--cyan))">
                        <span><i class="bi bi-play-fill"></i> Mulai Backup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
