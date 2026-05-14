/**
 * Kategori Dokumen Module
 * File: public/assets/auth/backend/js/kategori-dokumen.js
 */

(function ($) {
    "use strict";

    let isLoading = false;
    let editId = null;

    /* ============================================================
       TABLE STATE & CONFIGURATION
    ============================================================ */
    window.tableState = {
        search: '',
        per_page: 10,
        page: 1,
    };

    /* ============================================================
       CORE FUNCTIONS (LOAD & RENDER)
    ============================================================ */
    window.loadData = function () {
        if (isLoading) return;
        isLoading = true;

        const $tbody = $('#dataBody');
        const $empty = $('#emptyState');

        window.renderLoading(window.tableState.per_page);
        $empty.addClass('d-none');

        $.ajax({
            url: window.urlData,
            method: 'GET',
            data: {
                search: window.tableState.search,
                rowPerPage: window.tableState.per_page,
                page: window.tableState.page
            },
            success(res) {
                if (!res.success) {
                    window.renderError(res.message || 'Gagal memuat data');
                    return;
                }

                // Update Stats
                if (res.data.stats) {
                    $('#statTotal').text(res.data.stats.total || 0);
                    $('#statUsed').text(res.data.stats.used || 0);
                }

                const data = res.data.list.data;
                const meta = res.data.list.meta || res.data.list;

                if (!data || data.length === 0) {
                    $tbody.empty();
                    $empty.removeClass('d-none');
                } else {
                    $empty.addClass('d-none');
                    renderTable(data, meta);
                }

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
        let html = '';
        let no = meta.from || 1;

        rows.forEach(row => {
            html += `
                <tr>
                    <td class="td-no" style="text-align:center">${String(no++).padStart(2, '0')}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px">
                            <div class="ico-box" style="background:${row.color || '#00c8ff'}">
                                <i class="${row.icon || 'bi bi-tag-fill'}"></i>
                            </div>
                            <div style="font-weight:600; color:var(--txt); font-size:14px">${row.name}</div>
                        </div>
                    </td>
                    <td><code style="font-size:11px; color:var(--cyan)">${row.slug}</code></td>
                    <td>
                        <div class="text-truncate" style="max-width:300px; font-size:12.5px; color:var(--dim)" title="${row.description || '-'}">
                            ${row.description || '<span class="opacity-50 italic">Tidak ada deskripsi</span>'}
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px; font-weight:500">${row.creator ? row.creator.name : 'System'}</div>
                    </td>
                    <td class="td-dt" style="font-family:var(--mono); font-size:12px">${new Date(row.created_at).toLocaleDateString('id-ID')}</td>
                    <td>
                        <div class="act-row" style="display:flex; justify-content:center; gap:8px">
                            <button type="button" onclick="editKategori('${row.id}')" class="ibtn ib-e" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button type="button" onclick="deleteKategori('${row.id}', '${row.name}')" class="ibtn ib-x" title="Hapus"><i class="bi bi-trash3"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        });

        $tbody.html(html);
    }

    /* ============================================================
       EVENTS & SEARCH
    ============================================================ */
    $('#searchInput').on('input', _.debounce(function () {
        window.tableState.search = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    }, 500));

    $('#btnReset').on('click', function () {
        $('#searchInput').val('');
        window.tableState.search = '';
        window.tableState.page = 1;
        window.loadData();
    });

    /* ============================================================
       ICON SELECTION
    ============================================================ */
    $(document).on('click', '.ico-item', function() {
        const icon = $(this).data('icon');
        $('#selectedIcon').val(icon);
        $('.ico-item').removeClass('active');
        $(this).addClass('active');
    });

    /* ============================================================
       ACTIONS (ADD, EDIT, DELETE)
    ============================================================ */
    $('#btnAdd').on('click', function () {
        editId = null;
        $('#modalTitle').text('Tambah Kategori Baru');
        $('#mainForm')[0].reset();
        
        // Reset icon to default
        $('#selectedIcon').val('bi bi-file-earmark-text');
        $('.ico-item').removeClass('active');
        $(`.ico-item[data-icon="bi bi-file-earmark-text"]`).addClass('active');
        
        $('#modalForm').modal('show');
    });

    window.editKategori = function (id) {
        editId = id;
        $('#modalTitle').text('Edit Kategori');
        
        SCA.loading({
            title: "Memuat Kategori",
            message: "Sedang mengambil data kategori..."
        });
        
        $.ajax({
            url: `${window.urlBase}/${id}/edit`,
            method: 'GET',
            success(res) {
                if (res.success) {
                    const data = res.data;
                    $('input[name="name"]').val(data.name);
                    $('input[name="color"]').val(data.color);
                    $('textarea[name="description"]').val(data.description);
                    
                    // Set icon
                    const icon = data.icon || 'bi bi-file-earmark-text';
                    $('#selectedIcon').val(icon);
                    $('.ico-item').removeClass('active');
                    $(`.ico-item[data-icon="${icon}"]`).addClass('active');

                    $('#modalForm').modal('show');
                }
            },
            complete() {
                SCA.close();
            }
        });
    };

    $('#mainForm').on('submit', function (e) {
        e.preventDefault();
        const $btn = $('#btnSave');
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<span><i class="bi bi-hourglass-split"></i> Menyimpan...</span>');

        const formData = $(this).serialize();
        const url = editId ? `${window.urlBase}/${editId}` : window.urlStore;
        const method = editId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success(res) {
                if (res.success) {
                    $('#modalForm').modal('hide');
                    SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                    window.loadData();
                } else {
                    SCA.toast({ type: 'error', title: 'Gagal', message: res.message });
                }
            },
            error(xhr) {
                let msg = 'Terjadi kesalahan sistem';
                if (xhr.responseJSON) msg = xhr.responseJSON.message || msg;
                SCA.toast({ type: 'error', title: 'Error', message: msg });
            },
            complete() {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    window.deleteKategori = function (id, name) {
        SCA.deleteConfirm(name).then(confirmed => {
            if (confirmed) {
                SCA.loading({
                    title: "Menghapus...",
                    message: "Mohon tunggu sebentar"
                });

                $.ajax({
                    url: `${window.urlBase}/${id}`,
                    method: 'DELETE',
                    success(res) {
                        if (res.success) {
                            SCA.toast({ type: 'success', title: 'Terhapus', message: res.message });
                            window.loadData();
                        } else {
                            SCA.toast({ type: 'error', title: 'Gagal', message: res.message });
                        }
                    },
                    complete() {
                        SCA.close();
                    }
                });
            }
        });
    };

    /* ============================================================
       INITIAL LOAD
    ============================================================ */
    $(function () {
        window.loadData();
    });

})(jQuery);
