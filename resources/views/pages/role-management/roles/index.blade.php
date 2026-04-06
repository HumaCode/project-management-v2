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
            window.urlAkses = @json($aksesUrl);
            window.urlShow = @json($showUrl);
            window.urlDestroy = @json($destroyUrl);
            window.canRead = @json(auth()->user()->can('read ' . $permissionAkses));
            window.canAkses = @json(auth()->user()->can('akses ' . $permissionAkses));
            window.canUpdate = @json(auth()->user()->can('update ' . $permissionAkses));
            window.canDelete = @json(auth()->user()->can('delete ' . $permissionAkses));

            /* ============================================================
               TABLE STATE & CONFIGURATION (SPESIFIK HALAMAN)
            ============================================================ */
            window.tableState = {
                search: null,
                status: null,
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
                    const statusClass = row.is_active == 1 ? 'rs-aktif' : 'rs-nonaktif';
                    const statusText = row.is_active == 1 ? 'Aktif' : 'Non-Aktif';

                    let actions = '';
                    if (window.canAkses) {
                        actions +=
                            `<a href="${window.urlAkses.replace('__ID__', row.id)}" class="ibtn ib-p action" title="Konfigurasi Permission"><i class="bi bi-shield-fill-check"></i></a>`;
                    }
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
                    <td>
                        <div class="td-role">
                            <div class="role-av" style="background:linear-gradient(135deg,#7f1d1d,#ff4d6d)">${initial}</div>
                            <div class="role-info">
                                <div class="role-nm">${row.name}</div>
                                <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i> ${row.slug}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="guard-badge rg-web">${row.guard_name}</span></td>
                    <td class="td-desc">${row.description || '-'}</td>
                    <td>
                        <div class="perm-wrap">
                            <span class="perm-count">${row.permission_count_label || 0}</span>
                            <div class="perm-bar-track">
                                <div class="perm-bar-fill" style="width:${row.permissions_percentage || 0}%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="user-count-wrap">
                            <span class="uc-num">${row.users_count_label || 0}</span>
                            <span class="uc-lbl">user</span>
                        </div>
                    </td>
                    <td><span class="rs-badge ${statusClass}">${statusText}</span></td>
                    <td class="td-dt">${row.created_at_indo || '-'}</td>
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
                window.tableState.status = $("#filterStatusAkun").val();
                window.tableState.per_page = 10;
                window.tableState.page = 1;
                window.loadData();
            };

            window.resetFilter = function() {
                window.tableState.search = null;
                window.tableState.status = null;
                window.tableState.page = 1;

                $('#searchInput').val('');
                $("#filterStatusAkun").val('all');

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
                    const $nameInput = $('#role_name');
                    const $slugInput = $('#role_slug');

                    $('#role_name')?.focus();

                    $nameInput.on('input', function() {
                        const slug = $(this).val().toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g,
                            '-');
                        $slugInput.val(slug);
                    });

                    $('.grp-toggle').on('click', function(e) {
                        e.preventDefault();
                        let grp = $(this).data('grp');
                        let icon = $(this).find('i');
                        $('.item-row[data-grp="' + grp + '"]').toggle();

                        if (icon.hasClass('bi-chevron-down')) {
                            icon.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                        } else {
                            icon.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                        }
                    });

                    $('.sw-all-cb').on('change', function() {
                        let target = $(this).data('target');
                        let isChecked = $(this).is(':checked');
                        $('input[id^="sw_' + target + '_"]:not(:disabled)').prop('checked', isChecked);
                    });

                    $('.item-row input[type="checkbox"]:not(.sw-all-cb)').on('change', function() {
                        let row = $(this).closest('.item-row');
                        let allCb = row.find('.sw-all-cb');
                        let itemCbs = row.find('input[type="checkbox"]:not(.sw-all-cb):not(:disabled)');
                        let allChecked = itemCbs.length > 0 && itemCbs.length === itemCbs.filter(
                            ':checked').length;
                        allCb.prop('checked', allChecked);
                    });
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

    <!-- Stat Cards -->
    @include('pages.role-management.roles.partials.stats-view')


    <!-- Filter Toolbar -->
    @include('pages.role-management.roles.partials.filter-view')

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead class="text-center">
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:180px">ROLE</th>
                        <th style="min-width:80px">GUARD</th>
                        <th style="min-width:200px">DESKRIPSI</th>
                        <th style="min-width:140px">PERMISSION</th>
                        <th style="min-width:80px">USER</th>
                        <th style="min-width:100px">STATUS</th>
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
