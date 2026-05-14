<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
        <style>
            .ico-box {
                width: 36px; height: 36px; border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-size: 18px; color: #fff;
            }
            .text-dim { color: var(--dim) !important; }
            .dot-color {
                width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px;
            }
            .ico-sel-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(44px, 1fr));
                gap: 8px;
                max-height: 200px;
                overflow-y: auto;
                padding: 10px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 12px;
            }
            .ico-item {
                width: 44px; height: 44px;
                display: flex; align-items: center; justify-content: center;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                color: var(--dim);
                cursor: pointer;
                transition: all 0.2s;
                font-size: 18px;
            }
            .ico-item:hover {
                background: rgba(0, 200, 255, 0.1);
                color: var(--cyan);
                border-color: var(--cyan);
                transform: translateY(-2px);
            }
            .ico-item.active {
                background: var(--cyan);
                color: #fff;
                border-color: var(--cyan);
                box-shadow: 0 0 15px rgba(0, 200, 255, 0.4);
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
        <script>
            window.urlBase = "{{ url('kategori-dokumen') }}";
            window.urlData = "{{ route('kategori-dokumen.getData') }}";
            window.urlStore = "{{ route('kategori-dokumen.store') }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/kategori-dokumen.js') }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down" data-aos-duration="500">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-md-flex">
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="50">
        <div class="col-md-6">
            <div class="sc c w-100 m-0">
                <div class="sc-ico c"><i class="bi bi-tag-fill"></i></div>
                <div>
                    <div class="sc-val" id="statTotal">{{ $stats['total'] }}</div>
                    <div class="sc-lbl">Total Kategori</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="sc g w-100 m-0">
                <div class="sc-ico g"><i class="bi bi-file-earmark-check-fill"></i></div>
                <div>
                    <div class="sc-val" id="statUsed">{{ $stats['used'] }}</div>
                    <div class="sc-lbl">Jenis Kategori Terpakai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama kategori atau deskripsi..." />
        </div>

        <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
            <button class="btn-reset" id="btnReset" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </div>

        <div class="tbar-right">
            <button class="btn-add" id="btnAdd">
                <span><i class="bi bi-plus-lg"></i> Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Table card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl" id="tblKategori">
                <thead>
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:200px">Nama Kategori</th>
                        <th style="min-width:150px">Slug</th>
                        <th style="min-width:250px">Deskripsi</th>
                        <th style="min-width:150px">Dibuat Oleh</th>
                        <th style="min-width:120px">Tanggal</th>
                        <th style="text-align:center;width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataBody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="tbl-empty d-none">
            <i class="bi bi-tag-fill"></i>
            <div class="et">Belum Ada Kategori</div>
            <div class="es">Buat kategori baru untuk mengelompokkan dokumen proyek Anda.</div>
        </div>

        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info" id="pagiInfo">
                Menampilkan <span>0</span> – <span>0</span> dari <span>0</span> kategori
            </div>
            <div class="pag" id="pagiBtns">
                <!-- Buttons generated by JS -->
            </div>
        </div>
    </div>

    @push('modals')
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
    @endpush

</x-master-layout>
