<!-- MODAL: HAPUS -->
<div class="modal fade m-dark m-red" id="delModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="m-hd">
                <h5 class="m-hd-title"><i class="bi bi-trash3-fill"></i> Hapus Catatan</h5>
                <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="m-bd">
                <div class="warn-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <p>Anda akan menghapus catatan <strong id="delNm">ini</strong>. Tindakan ini tidak dapat
                        dibatalkan.</p>
                </div>
                <p style="font-size:12px;color:var(--muted);font-family:var(--mono)"><i
                        class="bi bi-info-circle"></i>&nbsp;Catatan dihapus permanen dari sistem.</p>
            </div>
            <div class="m-ft">
                <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
                <button class="btn-mdel"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
            </div>
            <div class="modal-drain">
                <div class="drain-fill" id="drainDel"></div>
            </div>
        </div>
    </div>
</div>
