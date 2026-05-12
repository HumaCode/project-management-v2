<!-- Modal Detail -->
<div class="modal fade m-dark m-cyan" id="showModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="m-hd">
                <h5 class="m-hd-title"><i class="bi bi-info-circle-fill"></i> Detail Dokumen</h5>
                <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="m-bd">
                <div class="row g-4">
                    <!-- File Preview & Info -->
                    <div class="col-12 col-md-5">
                        <div class="detail-preview-card">
                            <div id="detailFileIcon" class="detail-icon-large pdf">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>
                            <div id="detailImagePreview" class="detail-img-box" style="display:none">
                                <img src="" alt="Preview">
                            </div>
                            <div class="mt-3 text-center">
                                <h6 class="mb-1 text-white" id="detailNama">Nama Dokumen</h6>
                                <p class="text-muted small" id="detailMeta">PDF &bull; 2.5 MB</p>
                                <a href="" id="btnDownloadDetail" class="btn btn-sm btn-outline-cyan w-100 mt-2" download>
                                    <i class="bi bi-download"></i> Unduh Dokumen
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="col-12 col-md-7">
                        <div class="detail-info-list">
                            <div class="info-item">
                                <span class="label">KATEGORI</span>
                                <span class="value"><span class="cat cat-s" id="detailKategori">Spesifikasi</span></span>
                            </div>
                            <div class="info-item">
                                <span class="label">PROJECT</span>
                                <span class="value text-cyan" id="detailProject">Project Name</span>
                            </div>
                            <div class="info-item">
                                <span class="label">VERSI</span>
                                <span class="value"><span class="vbadge" id="detailVersi">v1.0</span></span>
                            </div>
                            <div class="info-item">
                                <span class="label">TANGGAL UPLOAD</span>
                                <span class="value" id="detailTanggal">12 Mei 2026</span>
                            </div>
                            <div class="info-item">
                                <span class="label">DIUNGGAH OLEH</span>
                                <span class="value">
                                    <div class="td-usr">
                                        <div class="uav" id="detailUploaderAvatar" style="background:linear-gradient(135deg,#1e3a5f,#3d6080)">JD</div>
                                        <span id="detailUploaderName">John Doe</span>
                                    </div>
                                </span>
                            </div>
                            <div class="info-item border-0 mt-2 vertical">
                                <span class="label">KETERANGAN</span>
                                <p class="value-desc" id="detailKeterangan">Tidak ada keterangan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-ft">
                <button type="button" class="btn-mcancel w-100" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-preview-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--bd);
        border-radius: 15px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    .detail-icon-large {
        font-size: 80px;
        line-height: 1;
        margin-bottom: 15px;
        filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
    }
    .detail-img-box {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid var(--bd);
    }
    .detail-img-box img {
        width: 100%;
        height: auto;
        display: block;
    }
    .detail-info-list .info-item {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .detail-info-list .info-item.vertical {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    .detail-info-list .label {
        font-family: var(--mono);
        font-size: 11px;
        color: var(--cyan);
        opacity: 0.8;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .detail-info-list .value {
        font-weight: 600;
        color: #fff;
        font-size: 14px;
    }
    .value-desc {
        color: #ccc;
        font-size: 13px;
        line-height: 1.6;
        background: rgba(255,255,255,0.03);
        padding: 12px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.05);
        margin: 0;
        width: 100%;
    }
    .btn-outline-cyan {
        border-color: var(--cyan);
        color: var(--cyan);
        transition: 0.3s;
    }
    .btn-outline-cyan:hover {
        background: var(--cyan);
        color: #000;
    }
</style>
