<!-- MODAL: PREVIEW MEDIA (GAMBAR & PDF) -->
<div class="modal fade m-dark m-cyan" id="mediaPreviewModal" tabindex="-1" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="background:var(--card);border:1px solid var(--bd);border-radius:16px;overflow:hidden">
            <div class="m-hd d-flex align-items-center justify-content-between" style="padding:14px 20px;border-bottom:1px solid var(--bd)">
                <h5 class="m-hd-title m-0" id="mediaPreviewTitle" style="font-size:15px;font-weight:700;color:var(--txt)">
                    <i class="bi bi-file-earmark"></i> Preview Media
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <a id="mediaPreviewDownload" href="#" target="_blank" class="btn btn-sm btn-outline-info" style="font-size:12px;padding:4px 14px;border-radius:8px">
                        <i class="bi bi-download"></i> Unduh File
                    </a>
                    <button class="m-close" data-bs-dismiss="modal" style="background:none;border:none;color:var(--muted);font-size:18px;cursor:pointer"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
            <div class="m-bd text-center p-3" id="mediaPreviewBody" style="min-height:480px;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.3)">
                <!-- Preview Content Injected Here -->
            </div>
        </div>
    </div>
</div>
