/**
 * Permission Management Module
 * File: public/assets/auth/backend/js/permission.js
 */

(function ($) {
    "use strict";

    let isLoading = false;

    /* ============================================================
       TABLE STATE & CONFIGURATION
    ============================================================ */
    window.tableState = {
        search: null,
        per_page: 10,
        page: 1,
        last_page: 1
    };

    /* ============================================================
       CORE FUNCTIONS (LOAD & RENDER)
    ============================================================ */
    window.loadData = function () {
        if (isLoading) return;
        isLoading = true;

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
            window.renderEmpty('Data permission tidak ditemukan');
            return;
        }

        let html = '';
        let no = meta.from;

        rows.forEach(row => {
            let actions = '';
            if (window.canUpdate) {
                actions += `<a href="${window.urlEdit.replace('__ID__', row.id)}" class="ibtn ib-e action" title="Edit Permission"><i class="bi bi-pencil"></i></a>`;
            }
            if (window.canDelete) {
                actions += `<a href="${window.urlDestroy.replace('__ID__', row.id)}" class="ibtn ib-x delete" title="Hapus Permission"><i class="bi bi-trash3"></i></a>`;
            }

            html += `
                <tr>
                    <td class="td-no">${String(no++).padStart(2, '0')}</td>
                    <td class="td-dt">
                        <div style="font-weight:600; color:var(--txt)">${row.name}</div>
                    </td>
                    <td class="text-center">
                        <span class="guard-badge rg-${row.guard_name}">${row.guard_name}</span>
                    </td>
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
    window.applyFilter = function () {
        window.tableState.per_page = 10;
        window.tableState.page = 1;
        window.loadData();
    };

    window.resetFilter = function () {
        window.tableState.search = null;
        window.tableState.page = 1;
        $('#searchInput').val('');
        window.loadData();
    };

    /* ============================================================
       INITIAL LOAD & EVENTS
    ============================================================ */
    $(function () {
        setTimeout(() => {
            window.loadData();
        }, 300);

        if (typeof handleAction === 'function') {
            handleAction(window.dataTableId, function () {
                setTimeout(() => {
                    $('#permission_name').focus();
                }, 200);
            });
        }

        if (typeof handleDelete === 'function') {
            handleDelete(window.dataTableId);
        }
    });

})(jQuery);
