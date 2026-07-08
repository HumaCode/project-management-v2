<x-master-layout>
    @section('title', 'Diagram Sistem')

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/diagram.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Select2 Dark Theme Overrides khusus untuk Diagram Panel */
            .select2-container--default .select2-selection--single {
                background-color: rgba(255, 255, 255, 0.05) !important;
                border: 1px solid #334155 !important;
                height: 38px !important;
                border-radius: 8px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #ffffff !important;
                line-height: 36px !important;
                padding-left: 14px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px !important;
            }
            
            /* Warna Panel Dropdown (Opsi) */
            .select2-dropdown {
                background-color: #1e293b !important;
                border: 1px solid #3b82f6 !important;
                box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
            }
            
            /* Warna Kotak Pencarian dalam Dropdown */
            .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: #0f172a !important;
                color: #ffffff !important;
                border: 1px solid #334155 !important;
                border-radius: 4px;
            }
            
            /* Warna Teks Masing-Masing Opsi */
            .select2-container--default .select2-results__option {
                color: #ffffff !important; /* Putih terang */
                padding: 10px 14px !important;
                list-style: none !important; /* Hilangkan peluru (bullet) */
            }
            
            /* Warna Opsi Saat di Hover atau Dipilih */
            .select2-container--default .select2-results__option--highlighted[aria-selected],
            .select2-container--default .select2-results__option--highlighted {
                background-color: rgba(59, 130, 246, 0.4) !important;
                color: #ffffff !important;
            }
            
            /* Warna Opsi yang Sudah Dipilih/Aktif */
            .select2-container--default .select2-results__option[aria-selected="true"] {
                background-color: rgba(59, 130, 246, 0.2) !important;
                color: #60a5fa !important;
            }

            /* Premium Loader Styles */
            .loader-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                z-index: 100;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                border-radius: 12px;
                background: rgba(5, 12, 22, 0.65) !important;
                transition: all 0.3s ease;
            }
            
            html[data-theme="light"] .loader-overlay {
                background: rgba(244, 247, 252, 0.65) !important;
            }

            .loader-spinner-container {
                position: relative;
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .premium-loader-ring {
                position: absolute;
                width: 100%;
                height: 100%;
                border: 3px solid transparent;
                border-top-color: var(--cyan, #00c8ff) !important;
                border-bottom-color: var(--cyan, #00c8ff) !important;
                border-radius: 50%;
                animation: premium-spin 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
                filter: drop-shadow(0 0 6px rgba(0, 200, 255, 0.3));
            }

            .premium-loader-ring-inner {
                position: absolute;
                width: 75%;
                height: 75%;
                border: 3px solid transparent;
                border-left-color: rgba(0, 200, 255, 0.3) !important;
                border-right-color: rgba(0, 200, 255, 0.3) !important;
                border-radius: 50%;
                animation: premium-spin-reverse 1.2s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
            }

            .premium-loader-icon {
                font-size: 22px;
                color: var(--cyan, #00c8ff) !important;
                animation: pulse-icon 1.5s ease-in-out infinite;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 2;
            }

            .loader-text {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 1.5px;
                color: var(--cyan, #00c8ff) !important;
                text-transform: uppercase;
                animation: pulse-text 1.5s ease-in-out infinite;
            }

            @keyframes premium-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @keyframes premium-spin-reverse {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(-360deg); }
            }

            @keyframes pulse-icon {
                0%, 100% { transform: scale(1); opacity: 0.8; }
                50% { transform: scale(1.15); opacity: 1; filter: drop-shadow(0 0 8px rgba(0, 200, 255, 0.5)); }
            }

            @keyframes pulse-text {
                0%, 100% { opacity: 0.6; }
                50% { opacity: 1; }
            }

            /* Stunning Premium Modal styling */
            .diagram-modal {
                background-color: var(--crd-bg) !important;
                border: 1px solid var(--bd) !important;
                border-radius: 18px !important;
                box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35) !important;
                overflow: hidden;
            }

            .diagram-modal .modal-header {
                border-bottom: 1px solid var(--bd) !important;
                padding: 22px 28px !important;
                background: rgba(255, 255, 255, 0.01) !important;
            }

            html[data-theme="light"] .diagram-modal .modal-header {
                background: rgba(15, 23, 42, 0.01) !important;
            }

            .diagram-modal .modal-title {
                color: var(--txt) !important;
                font-size: 18px !important;
                font-weight: 700 !important;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .diagram-modal .modal-title i {
                color: var(--cyan);
                font-size: 20px;
            }

            .diagram-modal .modal-body {
                padding: 28px !important;
                color: var(--txt) !important;
            }

            .diagram-modal .form-label {
                color: var(--txt) !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                letter-spacing: 0.5px;
                text-transform: uppercase;
                margin-bottom: 8px !important;
            }

            /* Adaptive Input fields */
            .diagram-modal .form-control,
            .diagram-modal .form-select {
                background-color: rgba(255, 255, 255, 0.02) !important;
                border: 1px solid var(--bd) !important;
                color: var(--txt) !important;
                border-radius: 10px !important;
                padding: 0.65rem 1rem !important;
                font-size: 0.9rem !important;
                transition: all 0.3s ease !important;
            }

            html[data-theme="light"] .diagram-modal .form-control,
            html[data-theme="light"] .diagram-modal .form-select {
                background-color: #ffffff !important;
            }

            .diagram-modal .form-control::placeholder {
                color: var(--dim) !important;
                opacity: 0.5 !important;
            }

            .diagram-modal .form-control:focus,
            .diagram-modal .form-select:focus {
                border-color: var(--cyan) !important;
                box-shadow: 0 0 0 3px rgba(0, 200, 255, 0.15) !important;
                background-color: rgba(255, 255, 255, 0.04) !important;
            }

            html[data-theme="light"] .diagram-modal .form-control:focus,
            html[data-theme="light"] .diagram-modal .form-select:focus {
                background-color: #ffffff !important;
            }

            /* Dynamic Close Button */
            html:not([data-theme="light"]) .diagram-modal .btn-close {
                filter: invert(1) grayscale(100%) brightness(200%) !important;
            }
            html[data-theme="light"] .diagram-modal .btn-close {
                filter: none !important;
            }

            /* Choice Cards styling inside Modal */
            .type-card-label {
                cursor: pointer;
                display: block;
                height: 100%;
            }

            .type-card {
                background: rgba(255, 255, 255, 0.01);
                border: 1px solid var(--bd) !important;
                border-radius: 12px;
                padding: 16px 10px;
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 8px;
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                height: 100%;
                position: relative;
                overflow: hidden;
            }

            html[data-theme="light"] .type-card {
                background: rgba(15, 23, 42, 0.01);
            }

            .type-icon-wrapper {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                display: grid;
                place-items: center;
                font-size: 18px;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.03);
                color: var(--dim);
            }

            html[data-theme="light"] .type-icon-wrapper {
                background: rgba(15, 23, 42, 0.03);
            }

            .type-name {
                font-size: 12.5px;
                font-weight: 700;
                color: var(--txt);
                transition: all 0.3s ease;
            }

            .type-desc {
                font-size: 9.5px;
                color: var(--dim);
                line-height: 1.3;
                transition: all 0.3s ease;
            }

            /* Hover states */
            .type-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            /* Checkbox selected states */
            input[type="radio"]:checked + .type-card {
                transform: scale(1.02);
            }

            /* Flowchart Color Scheme (Blue) */
            .type-flowchart-card:hover,
            input[type="radio"][value="flowchart"]:checked + .type-flowchart-card {
                border-color: #3b82f6 !important;
            }
            input[type="radio"][value="flowchart"]:checked + .type-flowchart-card {
                background: rgba(59, 130, 246, 0.08) !important;
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.15) !important;
            }
            input[type="radio"][value="flowchart"]:checked + .type-flowchart-card .type-icon-wrapper {
                background: #3b82f6;
                color: #fff;
            }
            input[type="radio"][value="flowchart"]:checked + .type-flowchart-card .type-name {
                color: #3b82f6;
            }

            /* ERD Color Scheme (Green) */
            .type-erd-card:hover,
            input[type="radio"][value="erd"]:checked + .type-erd-card {
                border-color: #10b981 !important;
            }
            input[type="radio"][value="erd"]:checked + .type-erd-card {
                background: rgba(16, 185, 129, 0.08) !important;
                box-shadow: 0 0 15px rgba(16, 185, 129, 0.15) !important;
            }
            input[type="radio"][value="erd"]:checked + .type-erd-card .type-icon-wrapper {
                background: #10b981;
                color: #fff;
            }
            input[type="radio"][value="erd"]:checked + .type-erd-card .type-name {
                color: #10b981;
            }

            /* DFD Color Scheme (Purple) */
            .type-dfd-card:hover,
            input[type="radio"][value="dfd"]:checked + .type-dfd-card {
                border-color: #8b5cf6 !important;
            }
            input[type="radio"][value="dfd"]:checked + .type-dfd-card {
                background: rgba(139, 92, 246, 0.08) !important;
                box-shadow: 0 0 15px rgba(139, 92, 246, 0.15) !important;
            }
            input[type="radio"][value="dfd"]:checked + .type-dfd-card .type-icon-wrapper {
                background: #8b5cf6;
                color: #fff;
            }
            input[type="radio"][value="dfd"]:checked + .type-dfd-card .type-name {
                color: #8b5cf6;
            }

            /* Footer styles */
            .diagram-modal .modal-footer {
                border-top: 1px dashed var(--bd) !important;
                padding: 20px 28px !important;
                background: rgba(255, 255, 255, 0.005) !important;
            }

            .diagram-modal .btn-cancel {
                border: 1px solid var(--bd) !important;
                color: var(--dim) !important;
                background: transparent !important;
                border-radius: 10px !important;
                padding: 10px 20px !important;
                font-size: 13.5px !important;
                font-weight: 700 !important;
                transition: all 0.2s ease !important;
            }

            .diagram-modal .btn-cancel:hover {
                background: rgba(255, 255, 255, 0.05) !important;
                color: var(--txt) !important;
            }

            html[data-theme="light"] .diagram-modal .btn-cancel:hover {
                background: rgba(15, 23, 42, 0.04) !important;
            }

            .diagram-modal .btn-submit {
                background: var(--cyan) !important;
                border-color: var(--cyan) !important;
                color: #ffffff !important;
                border-radius: 10px !important;
                padding: 10px 24px !important;
                font-size: 13.5px !important;
                font-weight: 700 !important;
                transition: all 0.2s ease !important;
            }

            html:not([data-theme="light"]) .diagram-modal .btn-submit {
                color: #000000 !important;
            }

            .diagram-modal .btn-submit:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 5px 15px rgba(0, 200, 255, 0.3) !important;
            }
        </style>
    @endpush

    @push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down" data-aos-duration="500">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="bi bi-diagram-2-fill"></i></div>
            <div>
                <div class="pg-title">Diagram Sistem</div>
                <div class="pg-sub">Kelola Arsitektur, Flowchart, dan ERD Proyek Anda</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-md-flex">
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">Diagram Sistem</span>
            </div>
        </div>
    </div>

    <!-- Alpine App for Diagrams -->
    <div x-data="diagramApp()" x-init="loadData()" class="mt-4">
        
        <!-- Filter Bar -->
        <div class="filter-section" data-aos="fade-up" wire:ignore>
            <div class="filter-group">
                <select class="form-select select2-diagram" x-ref="selectProject">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select class="form-select select2-diagram" x-ref="selectType">
                    <option value="">Semua Tipe Diagram</option>
                    <option value="flowchart">Flowchart (Alur Kerja)</option>
                    <option value="erd">ERD (Desain Database)</option>
                    <option value="dfd">DFD (Data Flow)</option>
                </select>
            </div>
            <div class="filter-group">
                <div class="input-group">
                    <span class="input-group-text border-end-0 bg-transparent"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0 bg-transparent" placeholder="Cari diagram..." x-model="filter.search" @input.debounce.500ms="loadData()">
                    <button class="btn btn-danger px-3 border-0" type="button" @click="resetFilter" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid Container -->
        <div class="row g-4 mb-5 position-relative" style="min-height: 200px;">
            <!-- Loading State Overlay -->
            <template x-if="isLoading">
                <div class="loader-overlay">
                    <div class="loader-spinner-container">
                        <div class="premium-loader-ring"></div>
                        <div class="premium-loader-ring-inner"></div>
                        <div class="premium-loader-icon">
                            <i class="bi bi-diagram-2"></i>
                        </div>
                    </div>
                    <div class="loader-text mt-3">Memuat diagram...</div>
                </div>
            </template>

            <!-- Create New Card -->
            <div class="col-12 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="50">
                <div class="diagram-card card-create" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="bi bi-plus-circle"></i>
                    <h5 class="mb-0">Buat Diagram Baru</h5>
                    <small>Flowchart, ERD, atau DFD</small>
                </div>
            </div>

            <!-- Diagram List -->
            <template x-for="(item, index) in diagrams" :key="item.id">
                <div class="col-12 col-md-6 col-lg-4 col-xl-3" data-aos="fade-up" :data-aos-delay="100 + (index * 50)">
                    <div class="diagram-card" :class="'type-' + item.type">
                        <div class="diagram-header">
                            <div class="diagram-icon">
                                <i class="bi" :class="{
                                    'bi-diagram-3-fill': item.type === 'flowchart',
                                    'bi-database-fill': item.type === 'erd',
                                    'bi-arrow-left-right': item.type === 'dfd'
                                }"></i>
                            </div>
                            <span class="badge" :class="{
                                'bg-primary-subtle text-primary': item.type === 'flowchart',
                                'bg-success-subtle text-success': item.type === 'erd',
                                'bg-purple-subtle text-purple': item.type === 'dfd'
                            }" style="border-radius:20px; font-weight:500; font-size:11px" x-text="item.type.toUpperCase()"></span>
                        </div>
                        
                        <div class="diagram-title" x-text="item.name"></div>
                        <div class="diagram-project mt-2">
                            <i class="bi bi-kanban"></i> 
                            <span class="text-truncate" x-text="item.project ? item.project.name : 'Unknown Project'"></span>
                        </div>

                        <div class="diagram-footer">
                            <div class="diagram-date">
                                <i class="bi bi-calendar3"></i> <span x-text="formatDate(item.created_at)"></span>
                            </div>
                            <div class="diagram-actions">
                                <button type="button" class="btn-action btn-delete" @click="deleteDiagram(item.id)" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <a :href="'/diagrams/' + item.id + '/builder'" class="btn-action btn-open" title="Buka Builder">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Empty State -->
            <template x-if="!isLoading && diagrams.length === 0">
                <div class="col-12 text-center py-5" data-aos="fade-up">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%; background: rgba(0, 142, 179, 0.08); border: 1px solid rgba(0, 142, 179, 0.18); color: var(--cyan); font-size: 36px; animation: float 3s ease-in-out infinite;">
                        <i class="bi bi-diagram-2"></i>
                    </div>
                    <h5 class="mt-2" style="color: var(--txt); font-weight: 700;">Tidak ada diagram ditemukan</h5>
                    <p class="mb-0" style="color: var(--dim); font-size: 14px;">Coba ubah filter pencarian Anda atau buat diagram baru.</p>
                </div>
            </template>
        </div>

        <!-- Modal Create Diagram -->
        <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow diagram-modal">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-diagram-2-fill"></i>Buat Diagram Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="submitCreate">
                        <div class="modal-body" wire:ignore>
                            <div class="mb-3">
                                <label class="form-label">Pilih Proyek <span class="text-danger">*</span></label>
                                <select class="form-select" id="selectProjectModal" required>
                                    <option value="">-- Pilih Proyek --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Diagram <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" x-model="form.name" placeholder="Misal: Flowchart Login" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Tipe Diagram <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <!-- Flowchart Option -->
                                    <div class="col-4">
                                        <label class="type-card-label w-100" for="type_flowchart">
                                            <input type="radio" class="d-none" name="type_diag" id="type_flowchart" value="flowchart" x-model="form.type" checked>
                                            <div class="type-card type-flowchart-card">
                                                <div class="type-icon-wrapper">
                                                    <i class="bi bi-diagram-3-fill"></i>
                                                </div>
                                                <div class="type-name">Flowchart</div>
                                                <div class="type-desc">Alur Kerja & Logika</div>
                                            </div>
                                        </label>
                                    </div>
                                    <!-- ERD Option -->
                                    <div class="col-4">
                                        <label class="type-card-label w-100" for="type_erd">
                                            <input type="radio" class="d-none" name="type_diag" id="type_erd" value="erd" x-model="form.type">
                                            <div class="type-card type-erd-card">
                                                <div class="type-icon-wrapper">
                                                    <i class="bi bi-database-fill"></i>
                                                </div>
                                                <div class="type-name">ERD</div>
                                                <div class="type-desc">Skema & Relasi DB</div>
                                            </div>
                                        </label>
                                    </div>
                                    <!-- DFD Option -->
                                    <div class="col-4">
                                        <label class="type-card-label w-100" for="type_dfd">
                                            <input type="radio" class="d-none" name="type_diag" id="type_dfd" value="dfd" x-model="form.type">
                                            <div class="type-card type-dfd-card">
                                                <div class="type-icon-wrapper">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </div>
                                                <div class="type-name">DFD</div>
                                                <div class="type-desc">Aliran Data Sistem</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-submit" :disabled="isSubmitting">
                                <span x-show="!isSubmitting">Buat & Lanjut</span>
                                <span x-show="isSubmitting"><span class="spinner-border spinner-border-sm"></span> Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('diagramApp', () => ({
                diagrams: [],
                isLoading: false,
                isSubmitting: false,
                filter: {
                    project_id: '',
                    type: '',
                    search: ''
                },
                form: {
                    project_id: '',
                    name: '',
                    type: 'flowchart'
                },
                
                resetFilter() {
                    this.filter = { project_id: '', type: '', search: '' };
                    $(this.$refs.selectProject).val('').trigger('change.select2');
                    $(this.$refs.selectType).val('').trigger('change.select2');
                    this.loadData();
                },
                
                init() {
                    // Initialize Select2
                    let self = this;
                    $(this.$refs.selectProject).select2({
                        width: '100%'
                    }).on('change', function() {
                        self.filter.project_id = $(this).val();
                        self.loadData();
                    });

                    $(this.$refs.selectType).select2({
                        width: '100%',
                        minimumResultsForSearch: -1
                    }).on('change', function() {
                        self.filter.type = $(this).val();
                        self.loadData();
                    });

                    // Modal Select2
                    $('#selectProjectModal').select2({
                        dropdownParent: $('#modalCreate'),
                        width: '100%'
                    }).on('change', function() {
                        self.form.project_id = $(this).val();
                    });
                    
                    this.loadData();
                },

                loadData() {
                    this.isLoading = true;
                    axios.get('{{ route("diagrams.pagination") }}', { params: this.filter })
                        .then(res => {
                            if(res.data.status === 'success') {
                                this.diagrams = res.data.data.data;
                            }
                        })
                        .catch(err => console.error(err))
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
                
                submitCreate() {
                    if(!this.form.project_id || !this.form.name || !this.form.type) return;
                    
                    this.isSubmitting = true;
                    
                    let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                    
                    axios.post('{{ route("diagrams.store") }}', this.form, {
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                        .then(res => {
                            if(res.data.status === 'success') {
                                let myModalEl = document.getElementById('modalCreate');
                                let modal = bootstrap.Modal.getInstance(myModalEl);
                                modal.hide();
                                
                                // Reset form
                                this.form.name = '';
                                
                                SCA.toast({type: 'success', title: 'Berhasil', message: 'Diagram berhasil dibuat.'});
                                
                                // Buka builder page
                                window.location.href = '/diagrams/' + res.data.data.id + '/builder';
                            } else {
                                SCA.dialog({type: 'danger', title: 'Error', message: res.data.message || 'Gagal menyimpan data.'});
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            SCA.dialog({type: 'danger', title: 'Error', message: 'Terjadi kesalahan saat menyimpan data. Pastikan koneksi stabil.'});
                        })
                        .finally(() => {
                            this.isSubmitting = false;
                        });
                },
                
                deleteDiagram(id) {
                    SCA.confirm(
                        "Hapus Diagram?",
                        "Data yang dihapus tidak dapat dikembalikan!"
                    ).then((result) => {
                        if (result) {
                            let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                            
                            showLoading(true, { title: "Menghapus...", message: "Mohon tunggu sebentar" });
                            
                            axios.delete('/diagrams/' + id, {
                                headers: { 'X-CSRF-TOKEN': csrfToken }
                            })
                                .then(res => {
                                    showLoading(false);
                                    if(res.data.status === 'success') {
                                        SCA.toast({type: 'success', title: 'Terhapus!', message: 'Diagram berhasil dihapus.'});
                                        this.loadData();
                                    }
                                })
                                .catch(err => {
                                    showLoading(false);
                                    console.error(err);
                                    SCA.toast({type: 'danger', title: 'Error', message: 'Gagal menghapus data.'});
                                });
                        }
                    });
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                }
            }));
        });
    </script>
    @endpush
</x-master-layout>
