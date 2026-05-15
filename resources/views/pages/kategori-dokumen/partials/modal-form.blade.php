<!-- Modal Form -->
<div class="modal fade m-dark m-cyan" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="m-hd">
                <div class="m-hd-title">
                    <i class="bi bi-tag-fill"></i>
                    <span id="modalTitle">Tambah Kategori Baru</span>
                </div>
                <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="mainForm">
                <div class="m-bd">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="fm-lbl">Nama Kategori <span class="req">*</span></label>
                            <input type="text" name="name" class="fmi" placeholder="Contoh: Dokumen Teknis" required>
                        </div>
                        <div class="col-12">
                            <label class="fm-lbl">Pilih Ikon <span class="req">*</span></label>
                            <input type="hidden" name="icon" id="selectedIcon" value="bi bi-file-earmark-text">
                            <div class="ico-sel-grid">
                                @php
                                    $icons = [
                                        'bi bi-file-earmark-text', 'bi bi-folder-fill', 'bi bi-tag-fill', 'bi bi-archive-fill', 
                                        'bi bi-book-fill', 'bi bi-journal-text', 'bi bi-briefcase-fill', 'bi bi-clipboard-data-fill', 
                                        'bi bi-cloud-arrow-up-fill', 'bi bi-code-square', 'bi bi-cpu-fill', 'bi bi-database-fill', 
                                        'bi bi-diagram-3-fill', 'bi bi-envelope-paper-fill', 'bi bi-gear-fill', 'bi bi-graph-up-arrow', 
                                        'bi bi-images', 'bi bi-info-circle-fill', 'bi bi-key-fill', 'bi bi-layers-fill', 
                                        'bi bi-link-45deg', 'bi bi-list-task', 'bi bi-lock-fill', 'bi bi-patch-check-fill', 
                                        'bi bi-pencil-square', 'bi bi-pin-angle-fill', 'bi bi-shield-fill-check', 'bi bi-sticky-fill', 
                                        'bi bi-terminal-fill', 'bi bi-tools', 'bi bi-trash3-fill', 'bi bi-wallet2'
                                    ];
                                @endphp
                                @foreach($icons as $ico)
                                    <div class="ico-item {{ $ico == 'bi bi-file-earmark-text' ? 'active' : '' }}" data-icon="{{ $ico }}" title="{{ $ico }}">
                                        <i class="{{ $ico }}"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="fm-lbl">Warna Aksen</label>
                            <input type="color" name="color" class="fmi p-1" style="height: 44px" value="#00c8ff">
                        </div>
                        <div class="col-12">
                            <label class="fm-lbl">Deskripsi</label>
                            <textarea name="description" class="fmta" placeholder="Jelaskan penggunaan kategori ini..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn-msave" id="btnSave">
                        <span><i class="bi bi-check-lg"></i> Simpan Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
