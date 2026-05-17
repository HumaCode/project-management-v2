<!-- Modal Form -->
<div class="modal fade m-dark m-cyan" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="m-hd">
                <div class="m-hd-title">
                    <i class="bi bi-people-fill"></i>
                    <span id="modalTitle">Tambah Tim Baru</span>
                </div>
                <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="teamForm">
                <div class="m-bd">
                    <div class="m-section" style="border-top:none;padding-top:0;margin-top:0">Informasi Tim</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="fm-lbl">Nama Tim <span class="req">*</span></label>
                            <input type="text" name="name" class="fmi" placeholder="Contoh: Tim Pengembang Core" required>
                        </div>
                        <div class="col-12">
                            <label class="fm-lbl">Deskripsi</label>
                            <textarea name="description" class="fmta" placeholder="Jelaskan tujuan atau tugas tim ini..."></textarea>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--bd); padding-bottom: 8px; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                                <div class="m-section" style="border: none; padding: 0; margin: 0;">Pilih Anggota Tim</div>
                                <div class="modal-search-wrap" style="position: relative; width: 220px;">
                                    <i class="bi bi-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 12px; color: var(--muted); opacity: 0.7;"></i>
                                    <input type="text" id="memberSearchInput" class="fmi" style="height: 28px; font-size: 11px; padding-left: 28px; border-radius: 6px; background: rgba(255,255,255,0.03);" placeholder="Cari nama anggota...">
                                </div>
                            </div>
                            <div class="row g-2" id="userSelectionWrap" style="max-height: 250px; overflow-y: auto; padding: 5px; margin-top: 5px;">
                                <!-- User list loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn-msave" id="btnSave">
                        <span><i class="bi bi-check-lg"></i> Simpan Tim</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
