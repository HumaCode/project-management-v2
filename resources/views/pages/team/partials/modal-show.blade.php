<!-- Modal Detail -->
<div class="modal fade m-dark m-cyan" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="m-hd">
                <div class="m-hd-title">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Detail Tim</span>
                </div>
                <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="m-bd">
                <div class="text-center mb-4">
                    <div class="mb-2" style="display:inline-flex; align-items:center; justify-content:center; width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg, var(--blue), var(--cyan)); color:#fff; font-size:28px; box-shadow:0 8px 24px rgba(0, 200, 255, 0.3)">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 id="detName" class="mb-1" style="font-weight:800; color:var(--txt)">-</h4>
                    <p id="detCreator" class="text-muted small mb-0" style="font-family:var(--mono)">Dibuat oleh: <span>-</span></p>
                </div>

                <div class="m-section">Deskripsi Tim</div>
                <p id="detDesc" class="text-dim mb-4" style="line-height:1.6; font-size:13.5px">-</p>

                <div class="m-section d-flex justify-content-between align-items-center">
                    <span>Daftar Anggota</span>
                    <span id="detCount" class="badge bg-cyan-soft text-cyan" style="font-size:10px; padding:4px 8px">0 Orang</span>
                </div>
                <div id="detMemberList" class="row g-2 mt-2">
                    <!-- Member list loaded via JS -->
                </div>
            </div>
            <div class="m-ft">
                <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
