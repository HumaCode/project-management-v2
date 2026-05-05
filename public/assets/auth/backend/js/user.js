/**
 * User Management Module
 * File: public/assets/auth/backend/js/user.js
 */

(function ($) {
    "use strict";

    let isLoading = false;

    /* ============================================================
       TABLE STATE & CONFIGURATION
    ============================================================ */
    window.tableState = {
        search: null,
        status: null,
        type: null,
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
                status: window.tableState.status,
                type: window.tableState.type,
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
            window.renderEmpty('Data user tidak ditemukan');
            return;
        }

        let html = '';
        let no = meta.from;

        rows.forEach(row => {
            const initial = row.name ? row.name.charAt(0).toUpperCase() : 'U';
            const statusClass = row.is_active == '1' ? 'tw-on' : 'tw-off';
            const statusText = row.is_active == '1' ? 'Aktif' : 'Non-Aktif';
            const emailVerifiedClass = row.email_verified_at !== null ? 'vr-yes' : 'vr-no';
            const emailVerified = row.email_verified_at !== null ? 'Ya' : 'Tidak';

            let actions = '';
            if (window.canActivated) {
                const disableApprove = row.is_active == 1 ? 'disabled' : '';
                const disableReject = row.is_active == 0 ? 'disabled' : '';

                actions += `
                    <button type="button" onclick="approveUser('${row.id}')" class="ibtn ib-e" title="Aktifkan" ${disableApprove} style="${disableApprove ? 'opacity: 0.4; cursor: not-allowed;' : ''}">
                        <i class="bi bi-check"></i>
                    </button>
                    <button type="button" onclick="rejectUser('${row.id}')" class="ibtn ib-f" title="Nonaktifkan" ${disableReject} style="${disableReject ? 'opacity: 0.4; cursor: not-allowed;' : ''}">
                        <i class="bi bi-x"></i>
                    </button>
                `;
            }

            if (window.canUpdate) {
                actions += `
                    <button type="button" onclick="resetPassword('${row.id}', '${row.name}', '${row.email}')" class="ibtn ib-s" title="Reset Password">
                        <i class="bi bi-key-fill"></i>
                    </button>
                `;
            }

            if (window.canShow) {
                actions += `<a href="${window.urlShow.replace('__ID__', row.id)}" class="ibtn ib-v action" title="Detail"><i class="bi bi-eye"></i></a>`;
            }

            if (window.canUpdate) {
                actions += `<a href="${window.urlEdit.replace('__ID__', row.id)}" class="ibtn ib-e action" title="Edit"><i class="bi bi-pencil"></i></a>`;
            }

            if (window.canDelete) {
                actions += `<a href="${window.urlDestroy.replace('__ID__', row.id)}" class="ibtn ib-x delete" title="Hapus"><i class="bi bi-trash3"></i></a>`;
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
                        <div class="usr-phone"><i class="bi bi-telephone" style="font-size:10px;opacity:.6;margin-right:4px"></i>${row.phone || 'N/A'}</div>
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
    window.applyFilter = function () {
        window.tableState.status = $("#filterStatusAkun").val();
        window.tableState.type = $("#filterTypeRole").val();
        window.tableState.per_page = 10;
        window.tableState.page = 1;
        window.loadData();
    };

    window.resetFilter = function () {
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
       PASSWORD OBSCURE (SHOW/HIDE)
    ============================================================ */
    window.togglePassword = function (id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
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
                // Focus logic if needed
            });
        }

        if (typeof handleDelete === 'function') {
            handleDelete(window.dataTableId);
        }
    });

})(jQuery);
