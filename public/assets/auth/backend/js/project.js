$(function () {
    // AOS init
    if (typeof AOS !== 'undefined') {
        AOS.init({ once: true, easing: 'ease-out-cubic', duration: 600 });
    }

    // State
    window.tableState = {
        search: '',
        status: '',
        page: 1,
        per_page: 10,
        sort_col: '',
        sort_dir: 'desc'
    };

    // Helpers
    const fmtDate = d => {
        if (!d) return '—';
        // handle Y-m-d format
        const parts = d.split(' ');
        const dateParts = parts[0].split('-');
        if (dateParts.length !== 3) return d;
        const [y, m, dd] = dateParts;
        return `${dd}/${m}/${y}`;
    };

    function deadlineHTML(dl, status) {
        if (status === 'done') return `<span class="td-dl-done"><i class="bi bi-check-circle"></i> Selesai</span>`;
        if (!dl) return `<span class="td-dl-ok">—</span>`;
        
        const targetDate = new Date(dl);
        const today = new Date();
        today.setHours(0,0,0,0);
        
        const diffTime = targetDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays < 0) return `<span class="td-dl-late"><i class="bi bi-exclamation-circle-fill"></i> ${fmtDate(dl)}<br><small>terlambat</small></span>`;
        if (diffDays === 0) return `<span class="td-dl-soon"><i class="bi bi-clock-fill"></i> Hari ini</span>`;
        if (diffDays <= 7) return `<span class="td-dl-soon"><i class="bi bi-clock"></i> ${fmtDate(dl)}<br><small>H-${diffDays}</small></span>`;
        
        return `<span class="td-dl-ok">${fmtDate(dl)}</span>`;
    }

    function appTypeBadge(type) {
        if (!type) return '—';
        const m = {
            website: ['website', 'Website'],
            android: ['android', 'Android'],
            website_android: ['website_android', 'Android & Website']
        };
        const [c, l] = m[type] || [type, type];
        return `<span class="badge-app ${c}">${l}</span>`;
    }

    function statusBadge(s) {
        const m = {
            to_do: ['bs-todo', 'To Do'],
            in_progress: ['bs-progress', 'In Progress'],
            done: ['bs-done', 'Done']
        };
        const [c, l] = m[s] || m.to_do;
        return `<span class="badge-status ${c}"><span class="dot"></span>${l}</span>`;
    }

    function progressHTML(p, status) {
        return `<div class="prog-cell"><div class="prog-bar"><div class="prog-fill${status === 'done' ? ' done' : ''}" style="width:${p}%"></div></div><div class="prog-pct">${p}%</div></div>`;
    }

    function avatarHTML(pics) {
        if (!pics || pics.length === 0) return '—';
        const show = pics.slice(0, 3);
        const extra = pics.length - 3;
        return '<div class="av-group">' +
            show.map(p => {
                const content = p.avatar ? `<img src="${p.avatar}" alt="${p.initials}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">` : p.initials;
                return `<div class="av-item" title="${p.name}">${content}</div>`;
            }).join('') +
            (extra > 0 ? `<div class="av-item av-more" title="Lainnya">+${extra}</div>` : '') +
            '</div>';
    }

    // Load Data
    window.loadData = function () {
        const $tbody = $('#dataBody');
        const $empty = $('#emptyState');
        
        // Show Skeleton / Loading (optional, using custom-table.js helper if it exists)
        if (typeof window.renderLoading === 'function') {
            window.renderLoading(window.tableState.per_page);
        }

        $.ajax({
            url: window.urlData,
            method: 'GET',
            data: {
                search: window.tableState.search,
                status: window.tableState.status,
                row_per_page: window.tableState.per_page,
                page: window.tableState.page,
                sort_col: window.tableState.sort_col,
                sort_dir: window.tableState.sort_dir
            },
            success: function (res) {
                if (res.success) {
                    const data = res.data.data;
                    const meta = res.data.meta;

                    $('#totalBadge').text(meta.total);

                    // Update UI Sort
                    $('.th-sort').removeClass('sort-asc sort-desc');
                    if (window.tableState.sort_col) {
                        $(`.th-sort[data-col="${window.tableState.sort_col}"]`).addClass('sort-' + window.tableState.sort_dir);
                    }

                    if (data.length === 0) {
                        $tbody.empty();
                        $empty.removeClass('d-none');
                        $('.tbl-info').html('Tidak ada data');
                        $('.pag').empty();
                    } else {
                        $empty.addClass('d-none');
                        let html = '';
                        data.forEach((p, i) => {
                            const rowIndex = String((meta.current_page - 1) * meta.per_page + i + 1).padStart(2, '0');
                            const showUrl = window.urlShow.replace('__ID__', p.id);
                            const editUrl = window.urlEdit.replace('__ID__', p.id);
                            const destroyUrl = window.urlDestroy.replace('__ID__', p.id);

                            html += `
                                <tr>
                                    <td class="td-no">${rowIndex}</td>
                                    <td>
                                        <div class="td-project">
                                            <div class="prj-img" style="background: ${p.color}">
                                                ${p.thumbnail ? `<img src="${p.thumbnail}" alt="Thumbnail">` : `<i class="bi ${p.icon}"></i>`}
                                            </div>
                                            <div class="prj-info">
                                                <div class="prj-nm">${p.name}</div>
                                                <div class="prj-desc">${p.description || '-'}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${appTypeBadge(p.app_type)}</td>
                                    <td>${statusBadge(p.status)}</td>
                                    <td>${progressHTML(p.progress, p.status)}</td>
                                    <td>
                                        <div class="td-team-cell">
                                            <div class="td-team-name">${p.team_name}</div>
                                            ${avatarHTML(p.pics)}
                                        </div>
                                    </td>
                                    <td>${fmtDate(p.start_date)}</td>
                                    <td>${deadlineHTML(p.deadline, p.status)}</td>
                                    <td class="td-mono">${p.created_by}</td>
                                    <td class="td-center">
                                        <div class="act-row">
                                            ${window.canRead ? `<button class="ibtn ib-v btn-show" data-url="${showUrl}" title="Detail"><i class="bi bi-eye"></i></button>` : ''}
                                            ${window.canUpdate ? `<button class="ibtn ib-e btn-edit" data-url="${editUrl}" title="Edit"><i class="bi bi-pencil"></i></button>` : ''}
                                            ${window.canDelete ? `<button class="ibtn ib-x btn-destroy" data-url="${destroyUrl}" title="Hapus"><i class="bi bi-trash3"></i></button>` : ''}
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                        $tbody.html(html);

                        // Render Meta Info & Pagination (from custom-table.js)
                        if (typeof window.renderInfo === 'function') window.renderInfo(meta);
                        if (typeof window.renderPagination === 'function') window.renderPagination(meta);
                    }
                }
            },
            error: function (err) {
                console.error(err);
                if (typeof window.renderError === 'function') window.renderError();
            }
        });
    };

    // Sort Listeners
    $('.th-sort').on('click', function () {
        const col = $(this).data('col');
        if (window.tableState.sort_col === col) {
            window.tableState.sort_dir = window.tableState.sort_dir === 'asc' ? 'desc' : 'asc';
        } else {
            window.tableState.sort_col = col;
            window.tableState.sort_dir = 'asc';
        }
        window.tableState.page = 1;
        window.loadData();
    });

    // Filter Listeners
    $('#fStatus').on('change', function () {
        window.tableState.status = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    });

    $('#btnReset').on('click', function () {
        $('#searchInput').val('');
        $('#fStatus').val('');
        window.tableState.search = '';
        window.tableState.status = '';
        window.tableState.sort_col = '';
        window.tableState.sort_dir = 'desc';
        window.tableState.page = 1;
        window.loadData();
    });

    // Action Listeners
    $(document).on('click', '.btn-show', function () {
        window.location.href = $(this).data('url');
    });

    $(document).on('click', '.btn-edit', function () {
        window.location.href = $(this).data('url');
    });

    $(document).on('click', '.btn-destroy', function () {
        const url = $(this).data('url');
        
        SCA.dialog({
            type: "danger",
            title: "Hapus Project?",
            message: "Project yang dihapus tidak dapat dikembalikan.",
            confirmText: "Ya, Hapus",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            SCA.loading({
                title: "Menghapus...",
                message: "Mohon tunggu sebentar"
            });

            axios.delete(url)
                .then(res => {
                    SCA.toast({
                        type: "success",
                        title: "Berhasil!",
                        message: res.data?.message || "Project berhasil dihapus.",
                        position: "top-right",
                    });
                    window.loadData();
                })
                .catch(err => {
                    SCA.toast({
                        type: "error",
                        title: "Error!",
                        message: err.response?.data?.message || err.message || "Gagal menghapus project.",
                        position: "top-right",
                    });
                })
                .finally(() => {
                    SCA.close();
                });
        });
    });

    // FAB
    const $fab = $('#fab');
    $(window).on('scroll', _.throttle(function () {
        $fab.toggleClass('visible', $(window).scrollTop() > 300);
    }, 200));

    window.scrollToTop = function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // Init
    window.loadData();
});
