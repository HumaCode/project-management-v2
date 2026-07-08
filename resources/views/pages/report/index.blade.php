<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/report.css') }}?v={{ time() }}">
        <style>
            /* Ultimate Force Select2 Dark Mode */
            html:not([data-theme="light"]) .select2-container--default .select2-selection--single {
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                height: 45px !important;
                display: flex !important;
                align-items: center !important;
                border-radius: 12px !important;
                backdrop-filter: blur(10px) !important;
                transition: all 0.3s ease !important;
            }

            html:not([data-theme="light"]) .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: rgba(255, 255, 255, 0.9) !important;
                padding-left: 15px !important;
                font-size: 14px !important;
            }

            html:not([data-theme="light"]) .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 42px !important;
                right: 10px !important;
            }

            html:not([data-theme="light"]) .select2-dropdown {
                background: #071528 !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                backdrop-filter: blur(20px) !important;
                border-radius: 12px !important;
                margin-top: 5px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
            }

            html:not([data-theme="light"]) .select2-results__option {
                color: rgba(255, 255, 255, 0.7) !important;
                padding: 10px 15px !important;
            }

            html:not([data-theme="light"]) .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: var(--cyan) !important;
                color: #000 !important;
            }

            /* Header Polish */
            html:not([data-theme="light"]) .pg-title {
                color: #fff !important;
                font-size: 22px !important;
            }

            html:not([data-theme="light"]) .pg-sub {
                color: rgba(255, 255, 255, 0.5) !important;
            }

            html:not([data-theme="light"]) .bc a {
                color: rgba(255, 255, 255, 0.5) !important;
                text-decoration: none !important;
            }

            html:not([data-theme="light"]) .bc .sep {
                color: rgba(255, 255, 255, 0.3) !important;
            }

            html:not([data-theme="light"]) .bc .here {
                color: var(--cyan) !important;
            }

            /* Filter Label */
            html:not([data-theme="light"]) .flt-label {
                color: rgba(255, 255, 255, 0.4);
            }
            
            .flt-label {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                font-weight: 600;
                margin-bottom: 8px;
                display: block;
            }

            /* Cover Builder Theming */
            html:not([data-theme="light"]) .bg-dark-deep { background: #050c16 !important; }
            html:not([data-theme="light"]) .cover-tools { background: #071528 !important; }
            html:not([data-theme="light"]) .tool-btn { 
                background: rgba(255,255,255,0.03); 
                border: 1px solid rgba(255,255,255,0.1); 
                color: rgba(255,255,255,0.7);
            }
            .tool-btn {
                padding: 12px 15px;
                border-radius: 10px;
                transition: all 0.3s ease;
                font-size: 13px;
            }
            html:not([data-theme="light"]) .tool-btn:hover {
                background: rgba(0, 200, 255, 0.1);
                border-color: var(--cyan);
                color: var(--cyan);
                transform: translateX(5px);
            }
            .canvas-wrapper {
                background: #fff;
                box-shadow: 0 0 50px rgba(0,0,0,0.5) !important;
                border: 10px solid #1a1a1a;
                border-radius: 4px;
                transform-origin: center center;
                transition: transform 0.3s ease;
            }
            
            /* Responsive Canvas Scaling */
            @media (max-width: 1400px) { .canvas-wrapper { transform: scale(0.8); } }
            @media (max-width: 1200px) { .canvas-wrapper { transform: scale(0.7); } }
            @media (max-width: 992px) { .canvas-wrapper { transform: scale(0.6); } }
            @media (max-height: 800px) { .canvas-wrapper { transform: scale(0.65); } }
            @media (max-height: 700px) { .canvas-wrapper { transform: scale(0.55); } }

            html:not([data-theme="light"]) #modalCover .modal-header, 
            html:not([data-theme="light"]) #modalCover .modal-footer {
                background: #071528;
                border-color: rgba(255,255,255,0.05) !important;
            }
            html:not([data-theme="light"]) #modalCover .modal-content {
                color: #ffffff !important;
            }

            /* Stunning Empty State Styles */
            .pms-empty-state {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 40px 24px;
                border: 1px dashed rgba(0, 200, 255, 0.2) !important;
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.01) !important;
                transition: all 0.3s ease;
                min-height: 280px;
                margin: 40px auto;
                max-width: 320px;
            }

            html[data-theme="light"] .pms-empty-state {
                border-color: rgba(0, 142, 179, 0.25) !important;
                background: rgba(15, 23, 42, 0.01) !important;
            }

            .pms-empty-state:hover {
                border-color: var(--cyan) !important;
                box-shadow: 0 0 20px rgba(0, 200, 255, 0.08) !important;
                background: rgba(0, 200, 255, 0.02) !important;
            }

            html[data-theme="light"] .pms-empty-state:hover {
                box-shadow: 0 0 20px rgba(0, 142, 179, 0.05) !important;
                background: rgba(0, 142, 179, 0.02) !important;
            }

            .empty-icon-wrap {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                background: rgba(0, 200, 255, 0.1) !important;
                color: var(--cyan) !important;
                display: grid;
                place-items: center;
                font-size: 28px;
                margin-bottom: 20px;
                box-shadow: 0 0 15px rgba(0, 200, 255, 0.1) !important;
                animation: float-empty 3s ease-in-out infinite;
            }

            html[data-theme="light"] .empty-icon-wrap {
                background: rgba(0, 142, 179, 0.08) !important;
                box-shadow: 0 0 15px rgba(0, 142, 179, 0.05) !important;
            }

            .pms-empty-state h5 {
                font-size: 15px;
                font-weight: 700;
                margin-bottom: 8px;
                color: var(--txt) !important;
            }

            .pms-empty-state p {
                font-size: 12px;
                color: var(--dim) !important;
                line-height: 1.6;
                margin: 0;
            }

            @keyframes float-empty {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-8px); }
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
        <script src="{{ asset('assets/auth/backend/js/report.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('assets/auth/backend/js/cover-builder-v2.js') }}?v={{ time() }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down" data-aos-duration="500">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="{{ $icon }}"></i></div>
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

    <!-- Project & Category Selector (Filter Area) -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="50">
        <div class="col-12 col-md-6">
            <div class="flt-box p-3">
                <label class="flt-label">Pilih Project Utama</label>
                <select id="projectSelect" class="form-select select2-dark">
                    <option value="">Pilih Project...</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="flt-box p-3">
                <label class="flt-label">Kategori Dokumen</label>
                <select id="categorySelect" class="form-select select2-dark">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->slug }}">{{ $kategori->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Main Builder Area -->
    <div class="row g-4 report-wrap">
        <!-- Panel Kiri: Koleksi Dokumen (4) -->
        <div class="col-md-4" data-aos="fade-right" data-aos-delay="100">
            <div class="report-panel">
                <div class="rp-header">
                    <div class="rp-title"><i class="bi bi-collection-fill"></i>Koleksi Dokumen</div>
                </div>
                <div class="rp-body custom-scroll">
                    <!-- Placeholder state -->
                    <div id="assetEmpty" class="pms-empty-state">
                        <div class="empty-icon-wrap">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <h5>Pilih Project Utama</h5>
                        <p>Silakan tentukan project terlebih dahulu pada dropdown di atas untuk memuat koleksi dokumen.</p>
                    </div>

                    <div class="asset-grid" id="assetGrid" style="display: none;">
                        <!-- Assets loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Susunan Laporan (8) -->
        <div class="col-md-8" data-aos="fade-left" data-aos-delay="200">
            <div class="report-panel">
                <div class="rp-header">
                    <div class="rp-title"><i class="bi bi-layers-fill"></i>Susunan Laporan</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-info" onclick="CoverBuilder.open()">
                            <i class="bi bi-palette"></i> Custom Cover
                        </button>
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="$('.canvas-item').remove(); ReportBuilder.updateEmptyState();">
                            <i class="bi bi-trash"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="rp-body custom-scroll" id="canvasBody">
                    <!-- Empty State -->
                    <div class="canvas-empty pms-empty-state">
                        <div class="empty-icon-wrap">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>
                        <h5>Belum Ada Dokumen</h5>
                        <p>Klik dokumen di panel kiri untuk mulai menyusun laporan PDF Anda.</p>
                    </div>

                    <!-- Items will be added here -->
                </div>
                <div class="p-3 border-top bg-black-20">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button class="btn btn-preview w-100 py-2 fw-bold" onclick="ReportBuilder.preview()">
                                <i class="bi bi-eye"></i> PREVIEW LAPORAN
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary w-100 py-2 fw-bold" onclick="ReportBuilder.generate()">
                                <i class="bi bi-file-earmark-pdf"></i> GENERATE PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Section -->
    <div class="row mt-5" data-aos="fade-up" data-aos-delay="300">
        <div class="col-12">
            <div class="crd">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-clock-history"></i> Riwayat Laporan Terakhir</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="filter-wrap">
                            <i class="bi bi-calendar3"></i>
                            <input type="date" id="historyDateFilter" class="filter-input" onchange="ReportHistory.load()" style="width: 150px; min-width: unset;">
                        </div>
                        <button class="btn-act" onclick="ReportHistory.reset()" title="Reset & Segarkan Data">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-pms mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Judul Laporan</th>
                                <th>Project</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="historyBody">
                            <!-- Loaded via AJAX -->
                            <tr>
                                <td colspan="5" class="text-center py-5 opacity-50">Mengambil data riwayat...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="pagi" id="historyPagination">
                    <!-- Pagination info and buttons -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cover Builder -->
    <div class="modal fade" id="modalCover" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark-deep">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="bi bi-palette-fill me-2"></i>Cover Builder (WYSIWYG)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 d-flex overflow-hidden">
                    <!-- Tools Sidebar -->
                    <div class="col-md-4 col-lg-3 border-end tools-column custom-scroll" style="max-height: 85vh; overflow-y: auto; padding: 20px;">
                        <div class="mb-4">
                            <label class="flt-label mb-3">Tambah Elemen</label>
                            <div class="d-grid gap-2">
                                <button class="btn tool-btn text-start" onclick="CoverBuilder.addText('JUDUL BARU', true)">
                                    <span class="me-2 opacity-50">H1</span> Tambah Judul
                                </button>
                                <button class="btn tool-btn text-start" onclick="CoverBuilder.addText('Teks deskripsi baru...')">
                                    <span class="me-2 opacity-50">Aa</span> Tambah Teks
                                </button>
                                <button class="btn tool-btn text-start" onclick="document.getElementById('coverLogoInput').click()">
                                    <i class="bi bi-image me-2 text-cyan"></i> Unggah Gambar/Logo
                                </button>
                                <input type="file" id="coverLogoInput" hidden accept="image/*" onchange="CoverBuilder.handleImageUpload(this)">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flt-label mb-3">Pilih Template Cover (16 Gaya)</label>
                            <div class="row g-2">
                                <!-- Existing 6 -->
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('modern')">
                                        <div style="height: 30px; background: #071528; border-bottom: 2px solid var(--cyan); border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Modern</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('minimalist')">
                                        <div style="height: 30px; background: #fff; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Minimal</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('elegant')">
                                        <div style="height: 30px; background: #1a1a1a; border-top: 4px solid #333; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Elegant</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('corporate')">
                                        <div style="height: 30px; border-left: 4px solid #0088cc; background: #f8f9fa; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Corp</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('creative')">
                                        <div style="height: 30px; background: linear-gradient(135deg, #ff5f00, #ff0055); border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Creative</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('luxury')">
                                        <div style="height: 30px; background: #000; border: 1px solid #d4af37; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Luxury</span>
                                    </button>
                                </div>
                                <!-- New 10 -->
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('futuristic')">
                                        <div style="height: 30px; background: #000; border: 1px solid #0f0; box-shadow: 0 0 5px #0f0; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Future</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('retro')">
                                        <div style="height: 30px; background: #f4a261; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Retro</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('geometric')">
                                        <div style="height: 30px; background: #e9ecef; border-radius: 4px; position: relative; overflow: hidden; margin-bottom: 3px;">
                                            <div style="position: absolute; width: 20px; height: 20px; background: #333; transform: rotate(45deg); top: -10px; right: -10px;"></div>
                                        </div>
                                        <span style="font-size: 8px;">Geo</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('blueprint')">
                                        <div style="height: 30px; background: #004a99; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Blueprt</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('soft')">
                                        <div style="height: 30px; background: #f8edeb; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Soft</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('midnight')">
                                        <div style="height: 30px; background: #121212; border-right: 8px solid #3d3d3d; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Midnigt</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('impact')">
                                        <div style="height: 30px; background: #ffea00; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Impact</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('scientific')">
                                        <div style="height: 30px; background: #fff; border: 1px solid #000; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Scien</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('abstract')">
                                        <div style="height: 30px; background: #6d597a; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Abstrac</span>
                                    </button>
                                </div>
                                <div class="col-4">
                                    <button class="btn tool-btn w-100 p-1 text-center" onclick="CoverBuilder.applyTemplate('ocean')">
                                        <div style="height: 30px; background: #0077b6; border-radius: 4px; margin-bottom: 3px;"></div>
                                        <span style="font-size: 8px;">Ocean</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flt-label mb-2">Warna Background</label>
                            <input type="color" class="form-control form-control-color w-100 bg-transparent border-secondary" 
                                id="coverBgColor" value="#ffffff" style="height: 45px; border-radius: 10px;"
                                onchange="CoverBuilder.setBgColor(this.value)">
                        </div>

                        <div class="mb-4" id="objectControls" style="display: none;">
                            <label class="flt-label mb-2">Edit Objek Terpilih</label>
                            <div class="d-grid gap-2">
                                <div id="textSpecificControls" style="display: none;">
                                    <button class="btn btn-cyan btn-sm w-100 mb-2 text-dark fw-bold" onclick="CoverBuilder.editText()">
                                        <i class="bi bi-pencil-square me-2"></i> Edit Teks Sekarang
                                    </button>
                                    <div class="d-flex gap-1 mb-3">
                                        <button class="btn btn-dark btn-sm flex-grow-1 border-secondary" onclick="CoverBuilder.toggleStyle('bold')" title="Bold">
                                            <i class="bi bi-type-bold"></i>
                                        </button>
                                        <button class="btn btn-dark btn-sm flex-grow-1 border-secondary" onclick="CoverBuilder.toggleStyle('italic')" title="Italic">
                                            <i class="bi bi-type-italic"></i>
                                        </button>
                                        <button class="btn btn-dark btn-sm flex-grow-1 border-secondary" onclick="CoverBuilder.toggleStyle('underline')" title="Underline">
                                            <i class="bi bi-type-underline"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span style="font-size: 11px; color: #888;">Warna:</span>
                                    <input type="color" class="form-control form-control-color flex-grow-1 bg-transparent border-secondary" 
                                        id="objColor" value="#000000" style="height: 35px; border-radius: 8px;"
                                        onchange="CoverBuilder.setObjectColor(this.value)">
                                </div>
                                <button class="btn btn-outline-danger btn-sm" style="border-radius: 8px;" onclick="CoverBuilder.deleteSelected()">
                                    <i class="bi bi-trash me-2"></i> Hapus Objek
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-info py-2 mb-0" style="font-size: 11px; background: rgba(0, 200, 255, 0.05); border: 1px solid rgba(0, 200, 255, 0.2); color: rgba(255,255,255,0.6);">
                                <i class="bi bi-info-circle me-1"></i> Klik objek di kanvas untuk mengedit atau menggeser.
                            </div>
                        </div>
                    </div>

                    <!-- Canvas Area -->
                    <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-black-20 p-2 p-md-4" style="overflow: hidden; background: #0b1119;">
                        <div class="canvas-wrapper shadow-lg bg-white" style="width: 595px; height: 842px; flex-shrink: 0;"> <!-- A4 Ratio -->
                            <canvas id="coverCanvas"></canvas>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="CoverBuilder.save()">
                        <i class="bi bi-check-circle me-1"></i> Gunakan Cover Ini
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-master-layout>
