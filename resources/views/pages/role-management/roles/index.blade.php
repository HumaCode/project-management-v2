<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
        <script>
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

            const dataTableId = "{{ $dataTableId }}";

            handleAction(dataTableId, function() {
                const $nameInput = $('#role_name');
                const $slugInput = $('#role_slug');

                // 1. Logika Auto Focus setelah modal benar-benar tampil
                // Kita cari modal terdekat dari inputan tersebut
                $('#role_name')?.focus();

                // 2. Logika Auto Slug
                $nameInput.on('input', function() {
                    const title = $(this).val();
                    const slug = title
                        .toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');

                    $slugInput.val(slug);
                });
            });

            // Handle delete
            handleDelete(dataTableId);
        </script>

        <script>
            /* ============================================================ TABLE STATE & CONFIGURATION ============================================================ */
            const tableState = {
                search: null,
                status: null,
                per_page: 10,
                page: 1,
                last_page: 1
            };

            let isLoading = false;

            /* ============================================================
               INITIAL LOAD
            ============================================================ */
            $(function() {
                // Beri sedikit delay agar AOS animation atau transition selesai
                setTimeout(() => {
                    loadData();
                }, 300);

                // Inisialisasi Handler Global
                handleAction(window.dataTableId, function() {
                    // Callback khusus saat modal Role terbuka
                    const $nameInput = $('#role_name');
                    const $slugInput = $('#role_slug');

                    // Auto Focus
                    $nameInput.closest('.modal').one('shown.bs.modal', function() {
                        $nameInput.trigger('focus');
                    });

                    // Auto Slug
                    $nameInput.on('input', function() {
                        const slug = $(this).val()
                            .toLowerCase()
                            .replace(/[^\w ]+/g, '')
                            .replace(/ +/g, '-');
                        $slugInput.val(slug);
                    });
                });

                handleDelete(window.dataTableId);
            });

            /* ============================================================
               CORE FUNCTIONS (LOAD & RENDER)
            ============================================================ */
            function loadData() {
                if (isLoading) return;
                isLoading = true;

                renderLoading(tableState.per_page);

                $.ajax({
                    url: window.urlData,
                    method: 'GET',
                    data: {
                        search: tableState.search,
                        status: $("#filterStatusAkun").val(),
                        row_per_page: tableState.per_page,
                        page: tableState.page
                    },
                    success(res) {
                        if (!res.success) {
                            renderError(res.message || 'Gagal memuat data');
                            return;
                        }

                        const rows = res.data.data;
                        const meta = res.data.meta;

                        tableState.last_page = meta.last_page;

                        renderTable(rows, meta);
                        renderInfo(meta);
                        renderPagination(meta);
                    },
                    error(xhr) {
                        let msg = 'Terjadi kesalahan sistem';
                        if (xhr.responseJSON) msg = xhr.responseJSON.message || msg;
                        renderError(msg);
                    },
                    complete() {
                        isLoading = false;
                    }
                });
            }

            function renderTable(rows, meta) {
                const $tbody = $('#dataBody');

                if (!rows || rows.length === 0) {
                    renderEmpty('Data role tidak ditemukan');
                    return;
                }

                let html = '';
                let no = meta.from;

                rows.forEach(row => {
                    const initial = row.name ? row.name.charAt(0).toUpperCase() : 'R';
                    const statusClass = row.is_active == 1 ? 'rs-aktif' : 'rs-non';
                    const statusText = row.is_active == 1 ? 'Aktif' : 'Non-Aktif';

                    // Actions Logic
                    let actions = '';

                    // Button Permission
                    if (window.canAkses) {
                        actions += `
                            <a href="${window.urlAkses.replace('__ID__', row.id)}" class="ibtn ib-p action" title="Konfigurasi Permission">
                                <i class="bi bi-shield-fill-check"></i>
                            </a>
                        `;
                    }

                    if (window.canUpdate) {
                        actions += `
                            <a href="${window.urlEdit.replace('__ID__', row.id)}" class="ibtn ib-e action" title="Edit Role">
                                <i class="bi bi-pencil"></i>
                            </a>
                        `;
                    }

                    if (window.canDelete) {
                        actions += `
                            <a href="${window.urlDestroy.replace('__ID__', row.id)}" class="ibtn ib-x delete" title="Hapus Role">
                                <i class="bi bi-trash3"></i>
                            </a>
                        `;
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
                            <td>
                                <div class="act-row">
                                    ${actions}
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $tbody.html(html);
            }

            /* ============================================================
               UI COMPONENTS (LOADING, EMPTY, ERROR)
            ============================================================ */
            function renderLoading(count = 5) {
                let html = '';
                for (let i = 0; i < count; i++) {
                    html += `
                        <tr>
                            <td colspan="10">
                                <div class="skeleton" style="height: 60px; margin: 5px 0; border-radius: 12px; opacity: 0.1; background: #ccc;"></div>
                            </td>
                        </tr>
                    `;
                }
                $('#dataBody').html(html);
            }

            function renderEmpty(message) {
                $('#dataBody').html(`
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 50px 0;">
                            <div class="empty-state">
                                <i class="bi bi-folder-x" style="font-size: 40px; color: #666;"></i>
                                <p style="margin-top: 10px; color: #888;">${message}</p>
                            </div>
                        </td>
                    </tr>
                `);
            }

            function renderError(message) {
                $('#dataBody').html(`
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 40px;">
                            <div class="alert alert-danger d-inline-block">
                                <i class="bi bi-exclamation-triangle-fill"></i> ${message}
                            </div>
                        </td>
                    </tr>
                `);
            }

            /* ============================================================
               PAGINATION & INFO
            ============================================================ */
            function renderInfo(meta) {
                $('.tbl-info').html(
                    `Menampilkan <b>${meta.from || 0}</b> - <b>${meta.to || 0}</b> dari <b>${meta.total || 0}</b> data`
                );
            }

            function renderPagination(meta) {
                const $pagination = $('.pag');
                $pagination.empty();

                const current = meta.current_page;
                const last = meta.last_page;
                const delta = 1; // Range halaman di sekitar active
                const range = [];

                // Tombol Previous
                $pagination.append(`
                    <button class="pb" data-page="prev" ${current === 1 ? 'disabled' : ''}>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                `);

                // Logika angka halaman
                for (let i = 1; i <= last; i++) {
                    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
                        range.push(i);
                    }
                }

                let prevPage;
                for (let i of range) {
                    if (prevPage) {
                        if (i - prevPage === 2) {
                            $pagination.append(`<button class="pb" data-page="${prevPage + 1}">${prevPage + 1}</button>`);
                        } else if (i - prevPage !== 1) {
                            $pagination.append(`<span class="pag-dot">&hellip;</span>`);
                        }
                    }
                    $pagination.append(`
                        <button class="pb ${i === current ? 'active' : ''}" data-page="${i}">${i}</button>
                    `);
                    prevPage = i;
                }

                // Tombol Next
                $pagination.append(`
                    <button class="pb" data-page="next" ${current === last ? 'disabled' : ''}>
                        <i class="bi bi-chevron-right"></i>
                    </button>
                `);
            }

            /* ============================================================
               EVENTS HANDLER (SEARCH, PAGINATION CLICK)
            ============================================================ */
            // Search dengan Debounce
            const debounceReload = _.debounce(() => {
                tableState.page = 1;
                loadData();
            }, 500);

            $('#searchInput').on('input', function() {
                tableState.search = $(this).val();
                debounceReload();
            });

            function applyFilter() {
                tableState.per_page = 10;
                debounceReload();
            }

            function resetFilter() {

                tableState.search = null;
                tableState.page = 1;

                $('#searchInput').val('');
                $("#filterStatusAkun").val('all')

                debounceReload();
            }

            // Click Pagination
            $(document).on('click', '.pag .pb', function() {
                const page = $(this).data('page');
                if (!page || $(this).prop('disabled') || $(this).hasClass('active')) return;

                if (page === 'prev') {
                    tableState.page--;
                } else if (page === 'next') {
                    tableState.page++;
                } else {
                    tableState.page = Number(page);
                }

                loadData();
            });

            // Change Per Page (Tampil Data)
            $('#tampilData').on('change', function() {
                tableState.per_page = $(this).val();
                tableState.page = 1;
                loadData();
            });
        </script>
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

    <!-- Info Banner -->
    <div class="info-banner" data-aos="fade-up" data-aos-delay="20">
        <i class="bi bi-info-circle-fill"></i>
        <p>Halaman ini untuk mengelola daftar role (nama, slug, guard). Untuk mengatur <strong>permission akses menu per
                role</strong>, gunakan halaman <a href="role.html">Konfigurasi Akses Role <i class="bi bi-arrow-right"
                    style="font-size:10px"></i></a></p>
    </div>


    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc p">
            <div class="sc-ico p"><i class="bi bi-shield-fill"></i></div>
            <div>
                <div class="sc-val">5</div>
                <div class="sc-lbl">Total Role</div>
            </div>
        </div>
        <div class="sc c">
            <div class="sc-ico c"><i class="bi bi-globe"></i></div>
            <div>
                <div class="sc-val">3</div>
                <div class="sc-lbl">Guard Web</div>
            </div>
        </div>
        <div class="sc g">
            <div class="sc-ico g"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="sc-val">10</div>
                <div class="sc-lbl">Total User</div>
            </div>
        </div>
        <div class="sc a">
            <div class="sc-ico a"><i class="bi bi-key-fill"></i></div>
            <div>
                <div class="sc-val">133</div>
                <div class="sc-lbl">Total Permission</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari data..." oninput="debounceReload()" />
        </div>

        <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
            <div style="flex: 1; min-width: 0;">
                <select class="nsel" style="min-width:128px" id="filterStatusAkun" onchange="applyFilter()">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>

            <button class="btn-reset" title="Reset Filter" onclick="resetFilter()">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </div>
        @can('create ' . $permissionAkses)
            <div class="tbar-right">
                <a href="{{ $createUrl }}" class="btn-add action">
                    <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
                </a>
            </div>
        @endcan
    </div>

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
