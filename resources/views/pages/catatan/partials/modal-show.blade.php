<!-- MODAL: LIHAT -->
<div class="modal fade m-dark m-cyan" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="m-hd">
                <h5 class="m-hd-title" id="viewTitle"><i class="bi bi-journal-text"></i> Detail Catatan</h5>
                <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="m-bd" style="padding:0">
                <div style="padding:18px 20px 14px">
                    <div id="viewBody" class="view-content"></div>
                </div>
                <div id="viewAttachments" style="padding:10px 20px 14px; border-top:1px solid rgba(255,255,255,0.08); display:none;"></div>
                <div class="view-meta" id="viewMeta"></div>
            </div>
            <div class="m-ft">
                <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                <button class="btn-msave"
                    onclick="bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();setTimeout(function(){new bootstrap.Modal(document.getElementById('editModal')).show();},200)"><span><i
                            class="bi bi-pencil-fill"></i> Edit</span></button>
            </div>
        </div>
    </div>
</div>
