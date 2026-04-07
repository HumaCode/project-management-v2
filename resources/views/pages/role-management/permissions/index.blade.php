<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        <script>
            // Konfigurasi Global
            window.dataTableId = @json($dataTableId);
            window.urlData = @json($dataUrl);
            window.urlEdit = @json($editUrl);
            window.urlShow = @json($showUrl);
            window.urlDestroy = @json($destroyUrl);
            window.canRead = @json(auth()->user()->can('read ' . $permissionAkses));
            window.canUpdate = @json(auth()->user()->can('update ' . $permissionAkses));
            window.canDelete = @json(auth()->user()->can('delete ' . $permissionAkses));

            /* ============================================================
               TABLE STATE & CONFIGURATION (SPESIFIK HALAMAN)
            ============================================================ */
            window.tableState = {
                search: null,
                per_page: 10,
                page: 1,
                last_page: 1
            };

            let isLoading = false;

            /* ============================================================
               CORE FUNCTIONS (LOAD & RENDER)
            ============================================================ */
            window.loadData = function() {
                if (isLoading) return;
                isLoading = true;

                // Panggil helper dari custom-table.js
                window.renderLoading(window.tableState.per_page);

                $.ajax({
                    url: window.urlData,
                    method: 'GET',
                    data: {
                        search: window.tableState.search,
                        row_per_page: window.tableState.per_page,
                        page: window.tableState.page
                    },
                    success(res) {
                        if (!res.success) {
                            window.renderError(res.message || 'Gagal memuat data');
                            return;
                        }

                        const rows = res.data.data;
                        const meta = res.data.meta;
                        window.tableState.last_page = meta.last_page;

                        renderTable(rows, meta);
                        window.renderInfo(meta);
                        window.renderPagination(meta);
                    },
                    error(xhr) {
                        let msg = 'Terjadi kesalahan sistem';
                        if (xhr.responseJSON) msg = xhr.responseJSON.message || msg;
                        window.renderError(msg);
                    },
                    complete() {
                        isLoading = false;
                    }
                });
            };

            function renderTable(rows, meta) {
                const $tbody = $('#dataBody');

                if (!rows || rows.length === 0) {
                    window.renderEmpty('Data tidak ditemukan');
                    return;
                }

                let html = '';
                let no = meta.from;

                rows.forEach(row => {

                    let actions = '';

                    if (window.canUpdate) {
                        actions +=
                            `<a href="${window.urlEdit.replace('__ID__', row.id)}" class="ibtn ib-e action" title="Edit Role"><i class="bi bi-pencil"></i></a>`;
                    }
                    if (window.canDelete) {
                        actions +=
                            `<a href="${window.urlDestroy.replace('__ID__', row.id)}" class="ibtn ib-x delete" title="Hapus Role"><i class="bi bi-trash3"></i></a>`;
                    }

                    html += `
                    <tr>
                        <td class="td-no">${String(no++).padStart(2, '0')}</td>
                        <td class="text-center td-dt">${row.name}</td>
                        <td class="text-center"><span class="guard-badge rg-web">${row.guard_name}</span></td>
                    
                        <td class="text-center td-dt">${row.created_at_indo || '-'}</td>
                        <td class="text-center td-dt">${row.updated_at_indo || '-'}</td>
                        <td><div class="act-row">${actions}</div></td>
                    </tr>
                `;
                });

                $tbody.html(html);
            }

            /* ============================================================
               FILTER DINAMIS
            ============================================================ */
            window.applyFilter = function() {
                window.tableState.per_page = 10;
                window.tableState.page = 1;
                window.loadData();
            };

            window.resetFilter = function() {
                window.tableState.search = null;
                window.tableState.page = 1;

                $('#searchInput').val('');

                window.loadData();
            };

            /* ============================================================
               INITIAL LOAD & CUSTOM PAGE EVENTS
            ============================================================ */
            $(function() {
                setTimeout(() => {
                    window.loadData();
                }, 300);

                handleAction(window.dataTableId, function() {

                });

                if (typeof handleDelete === 'function') {
                    handleDelete(window.dataTableId);
                }
            });
        </script>

        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
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
                <a href="dashboard.html"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>



    <!-- Filter Toolbar -->
    @include('pages.role-management.permissions.partials.filter-view')

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead class="text-center">
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:180px">NAMA</th>
                        <th style="min-width:80px">GUARD</th>
                        <th style="min-width:110px">DIBUAT</th>
                        <th style="min-width:110px">DIPERBARUI</th>
                        <th style="text-align:center;width:110px">AKSI</th>
                    </tr>
                </thead>
                <tbody id="dataBody">

                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>0</b> dari <b>0</b> data</div>
            <div class="pag">
                <button class="pb" disabled><i class="bi bi-chevron-left"></i></button>
                <button class="pb active">1</button>
                <button class="pb">2</button>
                <button class="pb">3</button>
                <span class="pag-dot">&hellip;</span>
                <button class="pb">4</button>
                <button class="pb"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>

</x-master-layout>
