<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/profile.css') }}">
    @endpush

    @push('js')
        <script>
            /* Profile tabs */
            document.querySelectorAll('.ptab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.ptab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');
                    var pane = this.dataset.pane;
                    document.querySelectorAll('.pane').forEach(function(p) {
                        p.classList.remove('active');
                    });
                    var el = document.getElementById('pane-' + pane);
                    if (el) {
                        el.classList.add('active');
                    }
                });
            });

            /* Password toggle */
            document.querySelectorAll('.pw-eye').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var inp = this.previousElementSibling;
                    if (inp.type === 'password') {
                        inp.type = 'text';
                        this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                    } else {
                        inp.type = 'password';
                        this.innerHTML = '<i class="bi bi-eye"></i>';
                    }
                });
            });

            /* Password strength */
            var pwNew = document.getElementById('pwNew');
            if (pwNew) {
                pwNew.addEventListener('input', function() {
                    var v = this.value,
                        bars = document.querySelectorAll('.pws-bar'),
                        lbl = document.getElementById('pwsLbl');
                    var score = 0;
                    if (v.length >= 8) score++;
                    if (/[A-Z]/.test(v)) score++;
                    if (/[0-9]/.test(v)) score++;
                    if (/[^A-Za-z0-9]/.test(v)) score++;
                    bars.forEach(function(b, i) {
                        b.classList.remove('weak', 'med', 'str');
                        if (score <= 1 && i < 1) b.classList.add('weak');
                        else if (score <= 2 && i < 2) b.classList.add('med');
                        else if (score >= 3 && i < score) b.classList.add('str');
                    });
                    var texts = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
                    var colors = ['', 'var(--err)', 'var(--warn)', 'var(--ok)', 'var(--cyan)'];
                    if (lbl) {
                        lbl.textContent = v ? texts[score] || texts[3] : '';
                        lbl.style.color = colors[score] || colors[3];
                    }
                });
            }

            /* Save feedback */
            document.querySelectorAll('.btn-save').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var orig = this.innerHTML;
                    this.classList.add('saved');
                    this.innerHTML = '<span><i class="bi bi-check-circle-fill"></i> Tersimpan!</span>';
                    var self = this;
                    setTimeout(function() {
                        self.classList.remove('saved');
                        self.innerHTML = orig;
                    }, 2400);
                });
            });

            /* Drain modals */
            function initDrain(mid, fid) {
                var m = document.getElementById(mid);
                if (!m) return;
                m.addEventListener('show.bs.modal', function() {
                    var f = document.getElementById(fid);
                    if (!f) return;
                    f.classList.remove('go');
                    void f.offsetWidth;
                    f.classList.add('go');
                });
                m.addEventListener('hidden.bs.modal', function() {
                    var f = document.getElementById(fid);
                    if (f) f.classList.remove('go');
                });
            }
            initDrain('delModal', 'drainDel');
            initDrain('logoutModal', 'drainLogout');

            /* Delete confirm typing */
            var delIn = document.getElementById('delConfirm'),
                delBtn = document.getElementById('btnDelAkun');
            if (delIn && delBtn) {
                delIn.addEventListener('input', function() {
                    delBtn.disabled = this.value !== 'HAPUS AKUN';
                });
            }

            /* Aktifkan semua notifikasi */
            var btnAA = document.getElementById('btnAktifAll');
            if (btnAA) {
                btnAA.addEventListener('click', function() {
                    document.querySelectorAll('#pane-notifikasi .sw-wrap input').forEach(function(c) {
                        c.checked = true;
                    });
                });
            }

            /* Avatar upload preview */
            var avUp = document.getElementById('avUpload');
            if (avUp) {
                avUp.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var img = document.getElementById('avImg');
                        if (img) {
                            img.src = ev.target.result;
                        } else {
                            var inner = document.getElementById('avInner');
                            if (inner) {
                                inner.innerHTML = '<img id="avImg" src="' + ev.target.result +
                                    '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';
                            }
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }

            /* Cover upload hint */
            document.querySelectorAll('.cover-edit').forEach(function(b) {
                b.addEventListener('click', function() {
                    var inp = document.createElement('input');
                    inp.type = 'file';
                    inp.accept = 'image/*';
                    inp.onchange = function(e) {
                        var f = e.target.files[0];
                        if (!f) return;
                        var r = new FileReader();
                        r.onload = function(ev) {
                            var cov = document.querySelector('.hero-cover');
                            if (cov) {
                                cov.style.backgroundImage = 'url(' + ev.target.result + ')';
                                cov.style.backgroundSize = 'cover';
                                cov.style.backgroundPosition = 'center';
                            }
                        };
                        r.readAsDataURL(f);
                    };
                    inp.click();
                });
            });
        </script>
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
        <div class="bc d-none d-xl-flex">
            <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
            <span class="sep"><i class="bi bi-chevron-right"></i></span>
            <span class="here">{{ $title }}</span>
        </div>
    </div>

    <!-- ══════════ HERO CARD ══════════ -->
    <div class="hero-card" data-aos="fade-up" data-aos-delay="30">
        <!-- Cover / Banner -->
        <div class="hero-cover">
            <div class="cover-pattern"></div>
            <div class="cover-grid"></div>
            <div class="cover-glow"></div>
            <button class="cover-edit"><i class="bi bi-camera-fill"></i> Ganti Cover</button>
        </div>
        <!-- Meta row -->
        <div class="hero-meta">
            <!-- Avatar -->
            <div class="av-wrap">
                <div class="av-ring">
                    <div class="av-inner" id="avInner">BS</div>
                </div>
                <div class="av-online" title="Online"></div>
                <label class="av-edit" for="avUpload" title="Ganti foto profil"><i
                        class="bi bi-camera-fill"></i></label>
                <input type="file" id="avUpload" accept="image/*" style="display:none" />
            </div>
            <!-- Names & badges -->
            <div class="hero-names">
                <div class="hero-fullname">Budi Santoso</div>
                <div class="hero-username">@budi_santoso &middot; budi@pmssystem.id</div>
                <div class="hero-badges">
                    <span class="hbadge hb-admin"><i class="bi bi-shield-fill-check"></i> Admin</span>
                    <span class="hbadge hb-active"><span class="hb-dot"></span> Online</span>
                    <span class="hbadge"
                        style="background:rgba(0,200,255,.08);color:var(--cyan);border:1px solid rgba(0,200,255,.2)"><i
                            class="bi bi-calendar3"></i> Bergabung Jan 2024</span>
                </div>
            </div>
            <!-- Actions -->
            <div class="hero-actions">
                <button class="btn-sec" onclick="document.querySelector('[data-pane=edit]').click()"><i
                        class="bi bi-pencil-fill"></i> Edit Profil</button>
                <button class="btn-p" onclick="document.querySelector('[data-pane=keamanan]').click()"><span><i
                            class="bi bi-shield-lock-fill"></i> Keamanan</span></button>
            </div>
        </div>
        <!-- Quick stats bar -->
        <div class="hero-qstats">
            <div class="hqs-item c">
                <div class="hqs-val">24</div>
                <div class="hqs-lbl">Proyek</div>
            </div>
            <div class="hqs-item g">
                <div class="hqs-val">186</div>
                <div class="hqs-lbl">Task Selesai</div>
            </div>
            <div class="hqs-item a">
                <div class="hqs-val">42</div>
                <div class="hqs-lbl">Dokumen</div>
            </div>
            <div class="hqs-item p">
                <div class="hqs-val">8</div>
                <div class="hqs-lbl">Tim</div>
            </div>
        </div>
    </div>

    <!-- ══════════ TABS ══════════ -->
    <div class="prof-tabs" data-aos="fade-up" data-aos-delay="50">
        <div class="ptab active" data-pane="edit"><i class="bi bi-person-fill"></i><span>Edit Profil</span></div>
        <div class="ptab" data-pane="keamanan"><i class="bi bi-shield-lock-fill"></i><span>Keamanan</span></div>
        <div class="ptab" data-pane="notifikasi"><i class="bi bi-bell-fill"></i><span>Notifikasi</span></div>
        <div class="ptab" data-pane="aktivitas"><i class="bi bi-clock-history"></i><span>Aktivitas</span></div>
        <div class="ptab" data-pane="sesi"><i class="bi bi-laptop"></i><span>Sesi Aktif</span></div>
        <div class="ptab" data-pane="bahaya"><i class="bi bi-exclamation-triangle-fill"></i><span>Zona Bahaya</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: EDIT PROFIL
      ══════════════════════════════════════════════ -->
    <div class="pane active" id="pane-edit">
        <div class="row g-4">
            <!-- Info Pribadi -->
            <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="60">
                <div class="pcard">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-c"><i class="bi bi-person-lines-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Informasi Pribadi</div>
                                <div class="pc-hd-sub">Data diri dan kontak pengguna</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body">
                        <div class="fsec">Identitas</div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Nama Depan <span class="req">*</span></label>
                                <input type="text" class="fmi" value="Budi" />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Nama Belakang <span class="req">*</span></label>
                                <input type="text" class="fmi" value="Santoso" />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Username <span class="req">*</span></label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-at fi"></i>
                                    <input type="text" class="fmi" value="budi_santoso" />
                                </div>
                                <div class="form-note">Huruf kecil, angka, dan underscore saja.</div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Jabatan</label>
                                <input type="text" class="fmi" value="Project Manager" />
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Bio / Tentang Saya</label>
                                <textarea class="fmta">Saya adalah Project Manager berpengalaman yang berfokus pada pengembangan sistem informasi pemerintahan dan solusi digital untuk instansi publik.</textarea>
                            </div>
                        </div>
                        <div class="fsec" style="margin-top:20px">Kontak</div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Email <span class="req">*</span></label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-envelope-fill fi"></i>
                                    <input type="email" class="fmi" value="budi@pmssystem.id" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">No. Telepon</label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-telephone-fill fi"></i>
                                    <input type="text" class="fmi" value="0813-5678-9012" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Kota / Lokasi</label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-geo-alt-fill fi"></i>
                                    <input type="text" class="fmi" value="Pekalongan, Jawa Tengah" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Website / LinkedIn</label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-link-45deg fi"></i>
                                    <input type="url" class="fmi" placeholder="https://..." />
                                </div>
                            </div>
                        </div>
                        <div class="save-row">
                            <button class="btn-cancel">Batal</button>
                            <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan
                                    Perubahan</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sidebar info -->
            <div class="col-12 col-lg-4">
                <!-- Foto profil -->
                <div class="pcard" data-aos="fade-up" data-aos-delay="80">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-c"><i class="bi bi-image-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Foto Profil</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body" style="text-align:center">
                        <div style="display:flex;justify-content:center;margin-bottom:16px">
                            <div class="av-ring" style="width:90px;height:90px;animation:avring 8s linear infinite">
                                <div class="av-inner" style="width:84px;height:84px;font-size:28px" id="avPreview">BS
                                </div>
                            </div>
                        </div>
                        <div style="font-size:12px;color:var(--muted);font-family:var(--mono);margin-bottom:14px">JPG,
                            PNG atau GIF &middot; Maks. 2MB</div>
                        <label for="avUpload" class="btn-p"
                            style="cursor:pointer;display:inline-flex;justify-content:center;width:100%">
                            <span><i class="bi bi-cloud-upload-fill"></i> Upload Foto</span>
                        </label>
                        <button class="btn-cancel"
                            style="width:100%;justify-content:center;margin-top:8px;height:36px"><i
                                class="bi bi-trash3"></i> Hapus Foto</button>
                    </div>
                </div>
                <!-- Preferensi -->
                <div class="pcard" data-aos="fade-up" data-aos-delay="100">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-p"><i class="bi bi-sliders"></i></div>
                            <div>
                                <div class="pc-hd-title">Preferensi</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="fm-lbl">Bahasa Antarmuka</label>
                                <select class="fmsel">
                                    <option selected>Bahasa Indonesia</option>
                                    <option>English</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Zona Waktu</label>
                                <select class="fmsel">
                                    <option selected>WIB (UTC+7)</option>
                                    <option>WITA (UTC+8)</option>
                                    <option>WIT (UTC+9)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Format Tanggal</label>
                                <select class="fmsel">
                                    <option selected>DD/MM/YYYY</option>
                                    <option>MM/DD/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                        <div class="save-row" style="margin-top:12px">
                            <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: KEAMANAN
      ══════════════════════════════════════════════ -->
    <div class="pane" id="pane-keamanan">
        <div class="row g-4">
            <div class="col-12 col-lg-7" data-aos="fade-up">
                <!-- Ganti Password -->
                <div class="pcard" style="margin-bottom:20px">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-a"><i class="bi bi-key-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Ganti Password</div>
                                <div class="pc-hd-sub">Gunakan password yang kuat dan unik</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="fm-lbl">Password Saat Ini <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" class="fmi"
                                        placeholder="Masukkan password saat ini..." />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Password Baru <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" class="fmi" id="pwNew"
                                        placeholder="Minimal 8 karakter..." />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                                <div class="pw-strength">
                                    <div class="pws-bar"></div>
                                    <div class="pws-bar"></div>
                                    <div class="pws-bar"></div>
                                    <div class="pws-bar"></div>
                                </div>
                                <div class="form-note" id="pwsLbl">&nbsp;</div>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Konfirmasi Password Baru <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" class="fmi" placeholder="Ulangi password baru..." />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="save-row">
                            <button class="btn-cancel">Batal</button>
                            <button class="btn-save"><span><i class="bi bi-key-fill"></i> Perbarui
                                    Password</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <!-- Keamanan akun -->
                <div class="pcard" data-aos="fade-up" data-aos-delay="40">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-g"><i class="bi bi-shield-fill-check"></i></div>
                            <div>
                                <div class="pc-hd-title">Status Keamanan</div>
                                <div class="pc-hd-sub">Konfigurasi keamanan akun</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-g"><i class="bi bi-shield-lock-fill"></i></div>
                                <div>
                                    <div class="sec-title">Two-Factor Auth</div>
                                    <div class="sec-desc">Lapisan keamanan tambahan via TOTP</div>
                                </div>
                            </div>
                            <div class="sec-right" style="display:flex;align-items:center;gap:10px">
                                <span class="sec-badge sb-off">Nonaktif</span>
                                <label class="sw-wrap sw-g"><input type="checkbox" /><span
                                        class="sw-track"></span></label>
                            </div>
                        </div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-c"><i class="bi bi-envelope-check-fill"></i></div>
                                <div>
                                    <div class="sec-title">Verifikasi Email</div>
                                    <div class="sec-desc">budi@pmssystem.id</div>
                                </div>
                            </div>
                            <div class="sec-right"><span class="sec-badge sb-on">Terverifikasi</span></div>
                        </div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-a"><i class="bi bi-bell-slash-fill"></i></div>
                                <div>
                                    <div class="sec-title">Alert Login Baru</div>
                                    <div class="sec-desc">Email jika login dari perangkat baru</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <label class="sw-wrap"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                        </div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-p"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <div class="sec-title">Session Timeout</div>
                                    <div class="sec-desc">Auto-logout setelah tidak aktif</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <select class="fmsel"
                                    style="width:auto;height:34px;font-size:12px;padding:0 28px 0 10px">
                                    <option>30 menit</option>
                                    <option selected>1 jam</option>
                                    <option>4 jam</option>
                                    <option>8 jam</option>
                                </select>
                            </div>
                        </div>
                        <div class="sec-item" style="border-bottom:none">
                            <div class="sec-left">
                                <div class="sec-ico pci-r"><i class="bi bi-incognito"></i></div>
                                <div>
                                    <div class="sec-title">Mode Privasi</div>
                                    <div class="sec-desc">Sembunyikan status online dari pengguna lain</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <label class="sw-wrap"><input type="checkbox" /><span
                                        class="sw-track"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: NOTIFIKASI
      ══════════════════════════════════════════════ -->
    <div class="pane" id="pane-notifikasi">
        <div class="row g-4">
            <div class="col-12 col-lg-8" data-aos="fade-up">
                <div class="pcard">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-c"><i class="bi bi-bell-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Preferensi Notifikasi</div>
                                <div class="pc-hd-sub">Atur notifikasi yang ingin Anda terima</div>
                            </div>
                        </div>
                        <button class="btn-sec" style="height:34px;font-size:12px;padding:0 12px" id="btnAktifAll"><i
                                class="bi bi-check-all"></i> Aktifkan Semua</button>
                    </div>
                    <div class="pc-body">
                        <div class="notif-group">
                            <div class="notif-group-title">Notifikasi Proyek</div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-c"><i class="bi bi-kanban-fill"></i></div>
                                    <div>
                                        <div class="notif-nm">Pembaruan Proyek</div>
                                        <div class="notif-desc">Notifikasi saat ada perubahan status proyek</div>
                                    </div>
                                </div>
                                <label class="sw-wrap "><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-g"><i class="bi bi-check2-circle"></i></div>
                                    <div>
                                        <div class="notif-nm">Task Selesai</div>
                                        <div class="notif-desc">Notifikasi saat anggota menyelesaikan task</div>
                                    </div>
                                </div>
                                <label class="sw-wrap sw-g"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-a"><i class="bi bi-alarm-fill"></i></div>
                                    <div>
                                        <div class="notif-nm">Deadline Mendekat</div>
                                        <div class="notif-desc">Peringatan 1 hari sebelum deadline</div>
                                    </div>
                                </div>
                                <label class="sw-wrap sw-a"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-p"><i class="bi bi-person-plus"></i></div>
                                    <div>
                                        <div class="notif-nm">Anggota Baru</div>
                                        <div class="notif-desc">Notifikasi saat ada anggota bergabung</div>
                                    </div>
                                </div>
                                <label class="sw-wrap sw-p"><input type="checkbox" /><span
                                        class="sw-track"></span></label>
                            </div>
                        </div>
                        <div class="notif-group">
                            <div class="notif-group-title">Notifikasi Sistem</div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-r"><i class="bi bi-shield-exclamation"></i></div>
                                    <div>
                                        <div class="notif-nm">Login Baru</div>
                                        <div class="notif-desc">Alert saat ada sesi login dari perangkat baru</div>
                                    </div>
                                </div>
                                <label class="sw-wrap sw-r"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-c"><i class="bi bi-envelope-fill"></i></div>
                                    <div>
                                        <div class="notif-nm">Email Digest</div>
                                        <div class="notif-desc">Ringkasan aktivitas harian via email</div>
                                    </div>
                                </div>
                                <label class="sw-wrap "><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                            <div class="notif-row">
                                <div class="notif-row-left">
                                    <div class="notif-ico ai-a"><i class="bi bi-megaphone-fill"></i></div>
                                    <div>
                                        <div class="notif-nm">Pengumuman</div>
                                        <div class="notif-desc">Notifikasi penting dari administrator</div>
                                    </div>
                                </div>
                                <label class="sw-wrap sw-a"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label>
                            </div>
                        </div>

                        <div class="save-row">
                            <button class="btn-cancel">Reset</button>
                            <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan
                                    Preferensi</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4" data-aos="fade-up" data-aos-delay="40">
                <div class="pcard">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-a"><i class="bi bi-send-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Saluran Pengiriman</div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-c"><i class="bi bi-app-indicator"></i></div>
                                <div>
                                    <div class="sec-title">In-App</div>
                                    <div class="sec-desc">Notifikasi di dalam dashboard</div>
                                </div>
                            </div>
                            <div class="sec-right"><label class="sw-wrap"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label></div>
                        </div>
                        <div class="sec-item">
                            <div class="sec-left">
                                <div class="sec-ico pci-a"><i class="bi bi-envelope-fill"></i></div>
                                <div>
                                    <div class="sec-title">Email</div>
                                    <div class="sec-desc">Kirim ke budi@pmssystem.id</div>
                                </div>
                            </div>
                            <div class="sec-right"><label class="sw-wrap"><input type="checkbox" checked /><span
                                        class="sw-track"></span></label></div>
                        </div>
                        <div class="sec-item" style="border-bottom:none">
                            <div class="sec-left">
                                <div class="sec-ico pci-g"><i class="bi bi-phone-fill"></i></div>
                                <div>
                                    <div class="sec-title">Push Mobile</div>
                                    <div class="sec-desc">Notifikasi ke aplikasi mobile</div>
                                </div>
                            </div>
                            <div class="sec-right"><label class="sw-wrap sw-g"><input type="checkbox" /><span
                                        class="sw-track"></span></label></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: AKTIVITAS
      ══════════════════════════════════════════════ -->
    <div class="pane" id="pane-aktivitas">
        <div class="pcard" data-aos="fade-up">
            <div class="pc-hd">
                <div class="pc-hd-left">
                    <div class="pc-hd-ico pci-c"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <div class="pc-hd-title">Riwayat Aktivitas</div>
                        <div class="pc-hd-sub">10 aktivitas terakhir di akun Anda</div>
                    </div>
                </div>
                <button class="btn-sec" style="height:34px;font-size:12px;padding:0 13px"><i
                        class="bi bi-download"></i> Export Log</button>
            </div>
            <div class="act-list">
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-c"><i class="bi bi-box-arrow-in-right"></i></div>
                    <div class="act-body">
                        <div class="act-title">Login berhasil</div>
                        <div class="act-desc">Masuk dari Chrome 121 · Windows 11</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>2 menit lalu <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-g"><i class="bi bi-pencil-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Profil diperbarui</div>
                        <div class="act-desc">Mengubah nomor telepon dan bio</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>1 jam lalu <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-a"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Export laporan PDF</div>
                        <div class="act-desc">Laporan proyek PPID Q1 2025</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>3 jam lalu <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-c"><i class="bi bi-kanban-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Proyek dibuat</div>
                        <div class="act-desc">Membuat proyek baru: Sistem Absensi v2</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>Kemarin 14:22 <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-p"><i class="bi bi-person-check-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Anggota ditambahkan</div>
                        <div class="act-desc">Menambahkan Nanda Pratiwi ke tim proyek</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>Kemarin 11:05 <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-a"><i class="bi bi-pencil-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Task diupdate</div>
                        <div class="act-desc">Mengubah status task menjadi 'In Review'</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>2 hari lalu <span
                                class="ip">10.0.0.1</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-r"><i class="bi bi-trash3-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Dokumen dihapus</div>
                        <div class="act-desc">Menghapus draft dokumen lama</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>3 hari lalu <span
                                class="ip">10.0.0.1</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-c"><i class="bi bi-shield-fill-check"></i></div>
                    <div class="act-body">
                        <div class="act-title">Password diubah</div>
                        <div class="act-desc">Kata sandi berhasil diganti</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>5 hari lalu <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-g"><i class="bi bi-bell-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Notifikasi dikirim</div>
                        <div class="act-desc">Mengirim reminder deadline ke tim</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>1 minggu lalu <span
                                class="ip">192.168.1.5</span></div>
                    </div>
                </div>
                <div class="act-item" data-aos="fade-up">
                    <div class="act-ico ai-p"><i class="bi bi-people-fill"></i></div>
                    <div class="act-body">
                        <div class="act-title">Role diperbarui</div>
                        <div class="act-desc">Role diubah dari Member menjadi Admin</div>
                        <div class="act-time"><i class="bi bi-clock" style="font-size:10px"></i>2 minggu lalu </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: SESI AKTIF
      ══════════════════════════════════════════════ -->
    <div class="pane" id="pane-sesi">
        <div class="pcard" data-aos="fade-up">
            <div class="pc-hd">
                <div class="pc-hd-left">
                    <div class="pc-hd-ico pci-p"><i class="bi bi-laptop"></i></div>
                    <div>
                        <div class="pc-hd-title">Sesi Login Aktif</div>
                        <div class="pc-hd-sub">Kelola perangkat yang sedang terautentikasi</div>
                    </div>
                </div>
                <button class="btn-sec"
                    style="height:34px;font-size:12px;padding:0 13px;color:var(--err);border-color:rgba(255,77,109,.2)"><i
                        class="bi bi-x-circle-fill"></i> Cabut Semua Lain</button>
            </div>
            <div class="session-item">
                <div class="sess-ico"><i class="bi bi-display"></i></div>
                <div style="flex:1;min-width:0">
                    <div class="sess-nm">Chrome 121 · Windows 11<span class="sess-badge sb-current"><i
                                class="bi bi-circle-fill" style="font-size:6px"></i>Sesi ini</span></div>
                    <div class="sess-detail">
                        <span><i class="bi bi-geo-alt"></i>Pekalongan, ID</span>
                        <span><i class="bi bi-hdd-network"></i>192.168.1.5</span>
                    </div>
                    <div class="sess-time"><i class="bi bi-clock" style="font-size:10px;margin-right:3px"></i>Aktif
                        sekarang</div>
                </div>

            </div>
            <div class="session-item">
                <div class="sess-ico"><i class="bi bi-phone"></i></div>
                <div style="flex:1;min-width:0">
                    <div class="sess-nm">Safari · iPhone 15 Pro<span class="sess-badge sb-inactive">Tidak aktif</span>
                    </div>
                    <div class="sess-detail">
                        <span><i class="bi bi-geo-alt"></i>Jakarta, ID</span>
                        <span><i class="bi bi-hdd-network"></i>10.0.0.18</span>
                    </div>
                    <div class="sess-time"><i class="bi bi-clock" style="font-size:10px;margin-right:3px"></i>30
                        menit lalu</div>
                </div>
                <button class="btn-revoke"><i class="bi bi-x-circle-fill"></i> Cabut</button>
            </div>
            <div class="session-item">
                <div class="sess-ico"><i class="bi bi-display"></i></div>
                <div style="flex:1;min-width:0">
                    <div class="sess-nm">Firefox 122 · Ubuntu 22<span class="sess-badge sb-inactive">Tidak
                            aktif</span></div>
                    <div class="sess-detail">
                        <span><i class="bi bi-geo-alt"></i>Semarang, ID</span>
                        <span><i class="bi bi-hdd-network"></i>172.16.0.5</span>
                    </div>
                    <div class="sess-time"><i class="bi bi-clock" style="font-size:10px;margin-right:3px"></i>2 jam
                        lalu</div>
                </div>
                <button class="btn-revoke"><i class="bi bi-x-circle-fill"></i> Cabut</button>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
           PANE: ZONA BAHAYA
      ══════════════════════════════════════════════ -->
    <div class="pane" id="pane-bahaya">
        <div class="pcard" data-aos="fade-up">
            <div class="pc-hd">
                <div class="pc-hd-left">
                    <div class="pc-hd-ico pci-r"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <div class="pc-hd-title">Zona Bahaya</div>
                        <div class="pc-hd-sub">Tindakan yang tidak dapat dibatalkan</div>
                    </div>
                </div>
            </div>
            <div class="pc-body">
                <!-- Deactivate -->
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid var(--bd);gap:12px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                        <div class="sec-ico pci-a"><i class="bi bi-person-dash-fill"></i></div>
                        <div>
                            <div class="sec-title">Nonaktifkan Akun</div>
                            <div class="sec-desc">Akun akan disembunyikan sementara. Anda dapat mengaktifkan kembali
                                kapan saja.</div>
                        </div>
                    </div>
                    <button class="btn-cancel"
                        style="border-color:rgba(245,158,11,.25);color:var(--warn);flex-shrink:0"><i
                            class="bi bi-person-dash-fill"></i> Nonaktifkan</button>
                </div>
                <!-- Export data -->
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid var(--bd);gap:12px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                        <div class="sec-ico pci-c"><i class="bi bi-cloud-download-fill"></i></div>
                        <div>
                            <div class="sec-title">Download Data Saya</div>
                            <div class="sec-desc">Unduh semua data akun, proyek, dan aktivitas dalam format JSON/CSV.
                            </div>
                        </div>
                    </div>
                    <button class="btn-cancel" style="flex-shrink:0"><i class="bi bi-cloud-download-fill"></i>
                        Download</button>
                </div>
                <!-- Delete -->
                <div
                    style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;gap:12px;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                        <div class="sec-ico pci-r"><i class="bi bi-person-x-fill"></i></div>
                        <div>
                            <div class="sec-title" style="color:var(--err)">Hapus Akun Permanen</div>
                            <div class="sec-desc">Semua data, proyek, catatan, dan riwayat akan dihapus selamanya tanpa
                                bisa dipulihkan.</div>
                        </div>
                    </div>
                    <button class="btn-sec"
                        style="border-color:rgba(255,77,109,.3);color:var(--err);background:rgba(255,77,109,.07);flex-shrink:0"
                        data-bs-toggle="modal" data-bs-target="#delModal"><i class="bi bi-person-x-fill"></i> Hapus
                        Akun</button>
                </div>
            </div>
        </div>
    </div>

</x-master-layout>
