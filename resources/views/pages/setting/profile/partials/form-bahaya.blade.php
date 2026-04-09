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
                <button class="btn-cancel" style="border-color:rgba(245,158,11,.25);color:var(--warn);flex-shrink:0"><i
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
