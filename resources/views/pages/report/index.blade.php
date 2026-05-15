<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/report.css') }}?v={{ time() }}">
        <style>
            /* Ultimate Force Select2 Dark Mode */
            .select2-container--default .select2-selection--single {
                background: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                height: 45px !important;
                display: flex !important;
                align-items: center !important;
                border-radius: 12px !important;
                backdrop-filter: blur(10px) !important;
                transition: all 0.3s ease !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: rgba(255, 255, 255, 0.9) !important;
                padding-left: 15px !important;
                font-size: 14px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 42px !important;
                right: 10px !important;
            }

            .select2-dropdown {
                background: #071528 !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                backdrop-filter: blur(20px) !important;
                border-radius: 12px !important;
                margin-top: 5px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
            }

            .select2-results__option {
                color: rgba(255, 255, 255, 0.7) !important;
                padding: 10px 15px !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: var(--cyan) !important;
                color: #000 !important;
            }

            /* Header Polish */
            .pg-title {
                color: #fff !important;
                font-size: 22px !important;
            }

            .pg-sub {
                color: rgba(255, 255, 255, 0.5) !important;
            }

            .bc a {
                color: rgba(255, 255, 255, 0.5) !important;
                text-decoration: none !important;
            }

            .bc .sep {
                color: rgba(255, 255, 255, 0.3) !important;
            }

            .bc .here {
                color: var(--cyan) !important;
            }

            /* Filter Label */
            .flt-label {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 1.5px;
                color: rgba(255, 255, 255, 0.4);
                font-weight: 600;
                margin-bottom: 8px;
                display: block;
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script src="{{ asset('assets/auth/backend/js/report.js') }}?v={{ time() }}"></script>
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
                    <div id="assetEmpty" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-up-circle d-block mb-3" style="font-size: 40px; opacity: 0.3;"></i>
                        Pilih project terlebih dahulu
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
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="$('.canvas-item').remove(); ReportBuilder.updateEmptyState();">
                            <i class="bi bi-trash"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="rp-body custom-scroll" id="canvasBody">
                    <!-- Empty State -->
                    <div class="canvas-empty text-center py-5 text-muted">
                        <div class="mb-3">
                            <i class="bi bi-plus-square-dotted" style="font-size: 50px; opacity: 0.1;"></i>
                        </div>
                        <p>Belum ada dokumen yang dipilih.<br>Klik dokumen di panel kiri untuk menambahkan.</p>
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

</x-master-layout>
