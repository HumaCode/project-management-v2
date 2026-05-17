<div class="pane" id="pane-aktivitas">
    <div class="row g-4">
        <!-- Kolom Kiri: Riwayat Timeline (AJAX Paginated) -->
        <div class="col-12 col-lg-8" data-aos="fade-up">
            <div class="pcard">
                <div class="pc-hd">
                    <div class="pc-hd-left">
                        <div class="pc-hd-ico pci-c"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <div class="pc-hd-title">Riwayat Aktivitas</div>
                            <div class="pc-hd-sub">Semua aktivitas autentikasi dan perubahan data akun Anda</div>
                        </div>
                    </div>
                    <a href="{{ route('profil.activities.export') }}" class="btn-sec" style="height:34px;font-size:12px;padding:0 13px; display:inline-flex; align-items:center; gap:6px; text-decoration:none;"><i class="bi bi-download"></i>
                        Export Log</a>
                </div>
                <div id="activity-container" style="position: relative; min-height: 200px; transition: opacity 0.25s ease;">
                    @include('pages.setting.profile.partials._activity_list', ['activities' => $activities])
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan & Statistik Aktivitas -->
        <div class="col-12 col-lg-4" data-aos="fade-up" data-aos-delay="40">
            <div class="pcard">
                <div class="pc-hd">
                    <div class="pc-hd-left">
                        <div class="pc-hd-ico pci-g"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <div class="pc-hd-title">Statistik Aktivitas</div>
                            <div class="pc-hd-sub">Ringkasan kontribusi terdaftar Anda</div>
                        </div>
                    </div>
                </div>
                <div class="pc-body">
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        
                        <!-- Total Aktivitas -->
                        <div class="sec-item" style="padding: 12px 0; margin-bottom: 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                            <div class="sec-left" style="display: flex; align-items: center; gap: 12px;">
                                <div class="sec-ico pci-c" style="width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; font-size: 16px;"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <div class="sec-title" style="font-size: 14px; font-weight: 600;">Total Aktivitas</div>
                                    <div class="sec-desc" style="font-size: 11px; opacity: 0.7;">Seluruh riwayat log</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <span class="sec-badge sb-on" style="background: rgba(0, 200, 255, 0.12); color: var(--cyan); border: 1px solid rgba(0, 200, 255, 0.2); font-family: var(--mono); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">{{ $activityStats['total'] }}</span>
                            </div>
                        </div>

                        <!-- Data Dibuat -->
                        <div class="sec-item" style="padding: 12px 0; margin-bottom: 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                            <div class="sec-left" style="display: flex; align-items: center; gap: 12px;">
                                <div class="sec-ico pci-g" style="width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; font-size: 16px;"><i class="bi bi-plus-circle"></i></div>
                                <div>
                                    <div class="sec-title" style="font-size: 14px; font-weight: 600;">Data Dibuat</div>
                                    <div class="sec-desc" style="font-size: 11px; opacity: 0.7;">Penambahan proyek/catatan</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <span class="sec-badge sb-on" style="background: rgba(0, 229, 160, 0.12); color: #00e5a0; border: 1px solid rgba(0, 229, 160, 0.2); font-family: var(--mono); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">{{ $activityStats['created'] }}</span>
                            </div>
                        </div>

                        <!-- Data Diperbarui -->
                        <div class="sec-item" style="padding: 12px 0; margin-bottom: 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                            <div class="sec-left" style="display: flex; align-items: center; gap: 12px;">
                                <div class="sec-ico pci-p" style="width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; font-size: 16px;"><i class="bi bi-pencil"></i></div>
                                <div>
                                    <div class="sec-title" style="font-size: 14px; font-weight: 600;">Data Diperbarui</div>
                                    <div class="sec-desc" style="font-size: 11px; opacity: 0.7;">Penyuntingan & modifikasi</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <span class="sec-badge sb-on" style="background: rgba(167, 139, 250, 0.12); color: #a78bfa; border: 1px solid rgba(167, 139, 250, 0.2); font-family: var(--mono); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">{{ $activityStats['updated'] }}</span>
                            </div>
                        </div>

                        <!-- Data Dihapus -->
                        <div class="sec-item" style="padding: 12px 0; margin-bottom: 0; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between;">
                            <div class="sec-left" style="display: flex; align-items: center; gap: 12px;">
                                <div class="sec-ico pci-r" style="width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; font-size: 16px;"><i class="bi bi-trash3"></i></div>
                                <div>
                                    <div class="sec-title" style="font-size: 14px; font-weight: 600;">Data Dihapus</div>
                                    <div class="sec-desc" style="font-size: 11px; opacity: 0.7;">Pembersihan data/berkas</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <span class="sec-badge sb-on" style="background: rgba(255, 77, 109, 0.12); color: #ff4d6d; border: 1px solid rgba(255, 77, 109, 0.2); font-family: var(--mono); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">{{ $activityStats['deleted'] }}</span>
                            </div>
                        </div>

                        <!-- Sesi & Autentikasi -->
                        <div class="sec-item" style="padding: 12px 0; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between;">
                            <div class="sec-left" style="display: flex; align-items: center; gap: 12px;">
                                <div class="sec-ico pci-a" style="width: 36px; height: 36px; border-radius: 8px; display: grid; place-items: center; font-size: 16px;"><i class="bi bi-shield-lock"></i></div>
                                <div>
                                    <div class="sec-title" style="font-size: 14px; font-weight: 600;">Sesi & Keamanan</div>
                                    <div class="sec-desc" style="font-size: 11px; opacity: 0.7;">Riwayat login & logout</div>
                                </div>
                            </div>
                            <div class="sec-right">
                                <span class="sec-badge sb-on" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); font-family: var(--mono); font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">{{ $activityStats['auth'] }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Tangkap klik link pagination di dalam kontainer aktivitas secara dinamis
    $(document).on('click', '#activity-container .dashboard-pagination a', function(e) {
        e.preventDefault(); // Cegah reload halaman default browser
        
        let url = $(this).attr('href');
        if (!url || url === '#') return;
        
        // Berikan efek transisi redup halus pada kontainer saat proses loading
        $('#activity-container').css('opacity', '0.4');
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    // Update isi kontainer secara dinamis dengan render HTML baru
                    $('#activity-container').html(res.html);
                    
                    // Scroll perlahan kembali ke atas area kartu aktivitas agar posisi fokus pas
                    $('html, body').animate({
                        scrollTop: $('#pane-aktivitas').offset().top - 120
                    }, 400);
                } else {
                    if (window.SCA && typeof SCA.toast === 'function') {
                        SCA.toast({
                            type: "error",
                            title: "Gagal!",
                            message: "Gagal memuat riwayat aktivitas.",
                            position: "top-right"
                        });
                    } else {
                        alert('Gagal memuat riwayat aktivitas.');
                    }
                }
            },
            error: function(xhr) {
                console.error(xhr);
                if (window.SCA && typeof SCA.toast === 'function') {
                    SCA.toast({
                        type: "danger",
                        title: "Peringatan!",
                        message: "Terjadi kesalahan koneksi saat mengambil data.",
                        position: "top-right"
                    });
                }
            },
            complete: function() {
                // Kembalikan tingkat kecerahan kontainer setelah loading selesai
                $('#activity-container').css('opacity', '1');
            }
        });
    });
});
</script>
