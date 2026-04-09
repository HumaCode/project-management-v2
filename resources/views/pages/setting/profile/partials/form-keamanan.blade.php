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
                                <input type="password" class="fmi" placeholder="Masukkan password saat ini..." />
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
                            <label class="sw-wrap sw-g"><input type="checkbox" /><span class="sw-track"></span></label>
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
                            <label class="sw-wrap"><input type="checkbox" /><span class="sw-track"></span></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
