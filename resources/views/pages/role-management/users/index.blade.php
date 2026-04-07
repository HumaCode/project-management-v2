<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
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
            window.canShow = @json(auth()->user()->can('show ' . $permissionAkses));
            window.canActivated = @json(auth()->user()->can('activate ' . $permissionAkses));
            window.canUpdate = @json(auth()->user()->can('update ' . $permissionAkses));
            window.canDelete = @json(auth()->user()->can('delete ' . $permissionAkses));

            /* ============================================================
               TABLE STATE & CONFIGURATION (SPESIFIK HALAMAN)
            ============================================================ */
            window.tableState = {
                search: null,
                status: null,
                type: null,
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
                        status: window.tableState.status, // Payload custom filter
                        type: window.tableState.type, // Payload custom filter
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
                    window.renderEmpty('Data role tidak ditemukan');
                    return;
                }

                let html = '';
                let no = meta.from;

                rows.forEach(row => {
                    const initial = row.name ? row.name.charAt(0).toUpperCase() : 'R';
                    const statusClass = row.is_active == '1' ? 'tw-on' : 'tw-off';
                    const statusText = row.is_active == '1' ? 'Aktif' : 'Non-Aktif';

                    const emailVerified = row.email_verified_at !== null ? 'Ya' : 'Tidak';
                    const emailVerifiedClass = row.email_verified_at !== null ? 'vr-yes' : 'vr-no';

                    let actions = '';
                    if (window.canActivated) {
                        // Jika is_active bernilai 1, tombol approve di-disable. Jika tidak, kosongkan.
                        const disableApprove = row.is_active == 1 ? 'disabled' : '';

                        // Jika is_active bernilai 0, tombol reject di-disable. Jika tidak, kosongkan.
                        const disableReject = row.is_active == 0 ? 'disabled' : '';

                        actions += `
                            <button type="button" onclick="approveUser('${row.id}')" class="ibtn ib-e " id="btnActivate-${row.id}" title="Aktifkan" ${disableApprove} style="${disableApprove ? 'opacity: 0.4; cursor: not-allowed;' : ''}">
                                <i class="bi bi-check"></i>
                            </button>
                        `;

                        actions += `
                            <button type="button" onclick="rejectUser('${row.id}')" class="ibtn ib-f " id="btnDeactivate-${row.id}" title="Nonaktifkan" ${disableReject} style="${disableReject ? 'opacity: 0.4; cursor: not-allowed;' : ''}">
                                <i class="bi bi-x"></i>
                            </button>
                        `;
                    }

                    if (window.canUpdate) {
                        actions +=
                            `<button type="button" onclick="resetPassword('${row.id}', '${row.name}', '${row.email}')" class="ibtn ib-s" title="Reset Password">
                                <i class="bi bi-key-fill"></i>
                            </button>`;
                    }

                    if (window.canShow) {
                        actions +=
                            `<a href="${window.urlShow.replace('__ID__', row.id)}" class="ibtn ib-v action" title="Detail"><i class="bi bi-eye"></i></a>`;
                    }
                    if (window.canUpdate) {
                        actions +=
                            `<a href="${window.urlEdit.replace('__ID__', row.id)}" class="ibtn ib-e action" title="Edit"><i class="bi bi-pencil"></i></a>`;
                    }

                    if (window.canDelete) {
                        actions +=
                            `<a href="${window.urlDestroy.replace('__ID__', row.id)}" class="ibtn ib-x delete" title="Hapus"><i class="bi bi-trash3"></i></a>`;
                    }

                    html += `
                        <tr>
                            <td class="td-no">${String(no++).padStart(2, '0')}</td>

                            <td>
                                <div class="td-user">
                                <div class="usr-av" style="background:linear-gradient(135deg,#0072c6,#00c8ff)">${initial}</div>
                                <div class="usr-info">
                                    <div class="usr-nm">${row.name}</div>
                                    <div class="usr-em"><i class="bi bi-envelope" style="font-size:9px"></i> ${row.email || 'N/A'}</div>
                                </div>
                                </div>
                            </td>

                            <td>
                                <div class="usr-phone"><i class="bi bi-telephone"
                                    style="font-size:10px;opacity:.6;margin-right:4px"></i>${row.phone || 'N/A'}</div>
                            </td>
                            <td><span class="role-badge ru-${row.role_name}">${row.role_name}</span></td> 
                             <td>
                                <div class="ver-wrap">
                                <i class="bi bi-patch-check-fill ${emailVerifiedClass}" title="Verifikasi Email: ${emailVerified}"></i>
                                    <span class="${emailVerifiedClass}" style="font-size:11.5px;font-family:var(--mono)">${emailVerified}</span>
                                </div>
                            </td>
                            <td>
                                <div class="two-fa-wrap">
                                <span class="tw-badge ${statusClass}">${statusText}</span>
                                </div>
                            </td>
                           
                            <td class="td-dt">${row.created_at_indo || '-'}</td>
                            <td class="td-dt">${row.updated_at_indo || '-'}</td>

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
                window.tableState.status = $("#filterStatusAkun").val();
                window.tableState.type = $("#filterTypeRole").val();
                window.tableState.per_page = 10;
                window.tableState.page = 1;

                // console.log(window.tableState.type);
                window.loadData();
            };

            window.resetFilter = function() {
                window.tableState.search = null;
                window.tableState.status = null;
                window.tableState.type = null;
                window.tableState.page = 1;

                $('#searchInput').val('');
                $("#filterStatusAkun").val('all');
                $("#filterTypeRole").val('all');

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
                <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>


    <!-- Stat Cards -->
    @include('pages.role-management.users.partials.stats-view')


    <!-- Filter Toolbar -->
    @include('pages.role-management.users.partials.filter-view')

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead class="text-center">
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:210px">USER</th>
                        <th style="min-width:150px">TELEPON</th>
                        <th>ROLE</th>
                        <th style="min-width:100px">VERIFIKASI</th>
                        <th style="min-width:100px">STATUS</th>
                        <th style="min-width:100px">TGL. DAFTAR</th>
                        <th style="min-width:100px">TGL. UPDATE</th>
                        <th style="text-align:center;width:100px">AKSI</th>
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
