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
                </div>
            </div>
        </div>

        <!-- Grid Container -->
        <div class="row g-4 mb-5 position-relative" style="min-height: 200px;">
            <!-- Loading State Overlay -->
            <template x-if="isLoading">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="z-index: 10; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(2px); border-radius: 12px;">
                    <div class="spinner-border text-info" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
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
                <div class="col-12 text-center py-5">
                    <img src="{{ asset('assets/auth/backend/images/illustration/empty.svg') }}" alt="Empty" style="max-width:200px; opacity:0.6" onerror="this.style.display='none'">
                    <h5 class="mt-4 text-muted">Tidak ada diagram ditemukan</h5>
                    <p class="text-muted">Coba ubah filter pencarian Anda atau buat diagram baru.</p>
                </div>
            </template>
        </div>

        <!-- Modal Create Diagram -->
        <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow diagram-modal">
                    <div class="modal-header border-bottom border-secondary pb-3">
                        <h5 class="modal-title fw-bold text-white">Buat Diagram Baru</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="submitCreate">
                        <div class="modal-body" wire:ignore>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">Pilih Proyek <span class="text-danger">*</span></label>
                                <select class="form-select" id="selectProjectModal" required>
                                    <option value="">-- Pilih Proyek --</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">Nama Diagram <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-transparent text-white" x-model="form.name" placeholder="Misal: Flowchart Login" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">Tipe Diagram <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="type_diag" id="type_flowchart" value="flowchart" x-model="form.type" checked>
                                        <label class="btn btn-outline-primary w-100 p-2 d-flex flex-column align-items-center justify-content-center h-100" for="type_flowchart">
                                            <i class="bi bi-diagram-3-fill fs-4 mb-1"></i> <span>Flowchart</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="type_diag" id="type_erd" value="erd" x-model="form.type">
                                        <label class="btn btn-outline-success w-100 p-2 d-flex flex-column align-items-center justify-content-center h-100" for="type_erd">
                                            <i class="bi bi-database-fill fs-4 mb-1"></i> <span>ERD</span>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="type_diag" id="type_dfd" value="dfd" x-model="form.type">
                                        <label class="btn btn-outline-info w-100 p-2 d-flex flex-column align-items-center justify-content-center h-100" style="color:#a78bfa; border-color:#a78bfa" for="type_dfd">
                                            <i class="bi bi-arrow-left-right fs-4 mb-1"></i> <span>DFD</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top border-secondary pt-3">
                            <button type="button" class="btn btn-outline-secondary text-white" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" :disabled="isSubmitting">
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
