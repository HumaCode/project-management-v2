<x-master-layout>
    @section('title', $title)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/catatan.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            window.catatanUrl = "{{ $dataUrl }}";
            window.storeUrl = "{{ $storeUrl }}";
            window.tableId = "{{ $dataTableId }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/catatan.js') }}?v={{ time() }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-xl-flex">
                <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>



    </div>

    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc">
            <div class="sc-ico c"><i class="bi bi-journal-text"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_catatan }}">{{ $total_catatan }}</div>
                <div class="sc-lbl">Total Catatan</div>
                <div class="sc-tr up"><i class="bi bi-arrow-up-short"></i>+0 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico r"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_high_priority }}">{{ $total_high_priority }}</div>
                <div class="sc-lbl">Prioritas Tinggi</div>
                <div class="sc-tr dn"><i class="bi bi-arrow-up-short"></i>+0 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico g"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_categories }}">{{ $total_categories }}</div>
                <div class="sc-lbl">Kategori</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>semua aktif</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico w"><i class="bi bi-kanban-fill"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_projects_related }}">{{ $total_projects_related }}</div>
                <div class="sc-lbl">Project Terkait</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>aktif</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari judul catatan..." />
        </div>
        <select class="nsel" id="filterCategory" style="min-width:140px">
            <option value="">Semua Kategori</option>
            <option value="Personal">Personal</option>
            <option value="Project">Project</option>
            <option value="Meeting">Meeting</option>
            <option value="Technical">Technical</option>
            <option value="Task">Task</option>
            <option value="Penting">Penting</option>
        </select>
        <select class="nsel select2" id="filterProject">
            <option value="">Semua Project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <select class="nsel" id="filterPriority" style="min-width:128px">
            <option value="">Semua Prioritas</option>
            <option value="tinggi">Tinggi</option>
            <option value="sedang">Sedang</option>
            <option value="rendah">Rendah</option>
        </select>
        <button class="btn-refresh" id="btnResetFilter" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <div class="tbar-right">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl" id="{{ $dataTableId }}">
                <thead>
                    <tr>
                        <th style="width:42px;text-align:center">#</th>
                        <th style="min-width:280px">JUDUL CATATAN</th>
                        <th>KATEGORI</th>
                        <th>PROJECT</th>
                        <th>PRIORITAS</th>
                        <th>DIBUAT OLEH</th>
                        <th>TGL DIBUAT</th>
                        <th>DIPERBARUI</th>
                        <th style="text-align:center;width:100px">AKSI</th>
                    </tr>
                </thead>
                <tbody id="dataBody">
                    <!-- Data loaded via AJAX -->
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="spinner-border text-cyan" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2" style="font-family:var(--mono);font-size:12px;color:var(--muted)">Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info"></div>
            <div class="pag"></div>
        </div>
    </div>

    @push('modals')
        @include('pages.catatan.partials.modal-create')
        @include('pages.catatan.partials.modal-edit')
        @include('pages.catatan.partials.modal-show')
        @include('pages.catatan.partials.modal-delete')
    @endpush
</x-master-layout>
