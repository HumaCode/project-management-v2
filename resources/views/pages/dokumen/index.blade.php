<x-master-layout>
    @section('title', $title)
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dokumen.css') }}">
        <style>
            .btn-reset {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid var(--bd);
                color: var(--muted);
                width: 40px;
                height: 40px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: 0.2s;
                cursor: pointer;
            }
            .btn-reset:hover {
                background: rgba(255, 255, 255, 0.1);
                color: var(--cyan);
                border-color: var(--cyan);
                transform: rotate(-45deg);
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
        <script>
            window.dataTableId = @json($dataTableId);
            window.urlData = @json($dataUrl);
            window.urlEdit = @json($editUrl ?? '#');
            window.urlShow = @json($showUrl ?? '#');
            window.urlDestroy = @json($destroyUrl ?? '#');
            window.urlBuilderBase = "{{ url('dokumen') }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/dokumen.js') }}?v={{ time() }}"></script>
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
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc c">
            <div class="sc-ico c"><i class="bi bi-files"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_dokumen }}">{{ $total_dokumen }}</div>
                <div class="sc-lbl">Total Dokumen</div>
                <div class="sc-tr up"><i class="bi bi-arrow-up-short"></i>+8 bulan ini</div>
            </div>
        </div>
        <div class="sc g">
            <div class="sc-ico g"><i class="bi bi-hdd-fill"></i></div>
            <div>
                <div class="sc-val">{{ $total_size }}</div>
                <div class="sc-lbl">Digunakan (MB)</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>dari 500 MB</div>
            </div>
        </div>
        <div class="sc w">
            <div class="sc-ico w"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $total_kategori }}">{{ $total_kategori }}</div>
                <div class="sc-lbl">Kategori</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>semua aktif</div>
            </div>
        </div>
        <div class="sc r">
            <div class="sc-ico r"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="sc-val count-up" data-target="{{ $new_this_month }}">{{ $new_this_month }}</div>
                <div class="sc-lbl">Revisi Bulan Ini</div>
                <div class="sc-tr dn"><i class="bi bi-arrow-up-short"></i>+3 minggu ini</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama dokumen..." />
        </div>
        <select class="select2" id="fKategori" style="min-width:160px">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" data-icon="{{ $cat->icon }}" data-color="{{ $cat->color }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select class="select2" id="fProject" style="min-width:180px">
            <option value="">Semua Project</option>
            @foreach($projects as $pj)
                <option value="{{ $pj->id }}">{{ $pj->name }}</option>
            @endforeach
        </select>
        <select class="select2" id="fType" style="min-width:160px">
            <option value="">Semua Tipe</option>
            <option value="file">File Tunggal</option>
            <option value="article">Koleksi / Manual Book</option>
            <option value="code">Dokumentasi Koding</option>
        </select>
        <select class="select2" id="tampilData" style="min-width:120px">
            <option value="10">10 Baris</option>
            <option value="25">25 Baris</option>
            <option value="50">50 Baris</option>
        </select>
        <button class="btn-reset" id="btnReset" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <div class="tbar-right">
            <button class="btn-add" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i> <span>Tambah Data</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th class="td-no">#</th>
                        <th>NAMA DOKUMEN</th>
                        <th>KATEGORI</th>
                        <th>PROJECT</th>
                        <th class="text-center">VERSI</th>
                        <th>UKURAN</th>
                        <th>TGL UPLOAD</th>
                        <th>DIUNGGAH OLEH</th>
                        <th style="text-align:center;width:116px">AKSI</th>
                    </tr>
                </thead>
                <tbody id="dataBody">
                    <!-- Data ditarik via AJAX -->
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>0</b> dari <b>0</b> data</div>
            <div class="pag">
                <!-- Diterjemahkan oleh custom-table.js -->
            </div>
        </div>
    </div>

    @push('modals')
        @include('pages.dokumen.partials.modal-create')
        @include('pages.dokumen.partials.modal-edit')
        @include('pages.dokumen.partials.modal-show')
        @include('pages.dokumen.partials.modal-delete')
    @endpush
</x-master-layout>
