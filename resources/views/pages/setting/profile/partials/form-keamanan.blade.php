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

                <form data-url="{{ route('profil.update-password', $profile->id) }}" id="form-keamanan">

                    <div class="pc-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="fm-lbl">Password Saat Ini <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" class="fmi" name="current_password"
                                        placeholder="Masukkan password saat ini..." />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Password Baru <span class="req">*</span></label>
                                <div class="pw-wrap">
                                    <input type="password" class="fmi" name="new_password" id="pwNew"
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
                                    <input type="password" class="fmi" name="new_password_confirmation"
                                        placeholder="Ulangi password baru..." />
                                    <button type="button" class="pw-eye"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="save-row">
                            <button type="submit" class="btn-save"><span><i class="bi bi-key-fill"></i> Perbarui
                                    Password</span></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <!-- Informasi Login & Sesi -->
            <div class="pcard" data-aos="fade-up" data-aos-delay="40">
                <div class="pc-hd">
                    <div class="pc-hd-left">
                        <div class="pc-hd-ico pci-g"><i class="bi bi-shield-fill-check"></i></div>
                        <div>
                            <div class="pc-hd-title">Informasi Login & Sesi</div>
                            <div class="pc-hd-sub">Deteksi koneksi aktif saat ini</div>
                        </div>
                    </div>
                </div>
                
                @php
                    $userAgent = request()->header('User-Agent');
                    
                    // Parse Browser
                    $browser = 'Browser Tidak Dikenal';
                    if (preg_match('/Chrome/i', $userAgent)) {
                        $browser = 'Google Chrome';
                    } elseif (preg_match('/Safari/i', $userAgent) && !preg_match('/Chrome/i', $userAgent)) {
                        $browser = 'Apple Safari';
                    } elseif (preg_match('/Firefox/i', $userAgent)) {
                        $browser = 'Mozilla Firefox';
                    } elseif (preg_match('/Edge/i', $userAgent)) {
                        $browser = 'Microsoft Edge';
                    } elseif (preg_match('/Opera/i', $userAgent) || preg_match('/OPR/i', $userAgent)) {
                        $browser = 'Opera';
                    }
                    
                    // Parse OS / Platform
                    $platform = 'Sistem Operasi Tidak Dikenal';
                    if (preg_match('/Windows/i', $userAgent)) {
                        $platform = 'Windows OS';
                    } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
                        $platform = 'macOS';
                    } elseif (preg_match('/Linux/i', $userAgent)) {
                        $platform = 'Linux';
                    } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
                        $platform = 'iOS Device';
                    } elseif (preg_match('/Android/i', $userAgent)) {
                        $platform = 'Android';
                    }
                @endphp

                <div>
                    <div class="sec-item">
                        <div class="sec-left">
                            <div class="sec-ico pci-g"><i class="bi bi-router-fill"></i></div>
                            <div>
                                <div class="sec-title">Alamat IP</div>
                                <div class="sec-desc">{{ request()->ip() }}</div>
                            </div>
                        </div>
                        <div class="sec-right">
                            <span class="sec-badge sb-on">Sesi Ini</span>
                        </div>
                    </div>
                    <div class="sec-item">
                        <div class="sec-left">
                            <div class="sec-ico pci-c"><i class="bi bi-compass-fill"></i></div>
                            <div>
                                <div class="sec-title">Browser</div>
                                <div class="sec-desc">{{ $browser }}</div>
                            </div>
                        </div>
                        <div class="sec-right">
                            <span class="sec-badge" style="background:rgba(0, 200, 255, 0.08);color:var(--cyan);border:1px solid rgba(0, 200, 255, 0.15)">Aktif</span>
                        </div>
                    </div>
                    <div class="sec-item">
                        <div class="sec-left">
                            <div class="sec-ico pci-a"><i class="bi bi-laptop-fill"></i></div>
                            <div>
                                <div class="sec-title">Sistem Operasi</div>
                                <div class="sec-desc">{{ $platform }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sec-item">
                        <div class="sec-left">
                            <div class="sec-ico pci-p"><i class="bi bi-envelope-check-fill"></i></div>
                            <div>
                                <div class="sec-title">Verifikasi Akun</div>
                                <div class="sec-desc" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="sec-right">
                            @if(auth()->user()->email_verified_at)
                                <span class="sec-badge sb-on">Terverifikasi</span>
                            @else
                                <span class="sec-badge sb-off">Belum Verifikasi</span>
                            @endif
                        </div>
                    </div>
                    <div class="sec-item" style="border-bottom:none">
                        <div class="sec-left">
                            <div class="sec-ico pci-r"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <div class="sec-title">Waktu Akses</div>
                                <div class="sec-desc">Hari ini, {{ now()->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                            </div>
                        </div>
                        <div class="sec-right">
                            <span class="sec-badge" style="background:rgba(0, 229, 160, 0.08);color:var(--ok);border:1px solid rgba(0, 229, 160, 0.15)">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
