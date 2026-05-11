/* ─────────────────────────────────────
     SPECIFIC JS FOR DOCUMENTS MODULE
     Note: Layout & Global logic inherited from global-js.js
   ───────────────────────────────────── */

$(function() {
    // 1. Initial State
    window.tableState = {
        search: '',
        kategori: '',
        project_id: '',
        page: 1,
        per_page: 10
    };

    // 2. Global Load Data Function
    window.loadData = function() {
        if (typeof renderLoading === 'function') renderLoading();
        
        $.ajax({
            url: window.urlData,
            data: {
                search: window.tableState.search,
                kategori: window.tableState.kategori,
                project_id: window.tableState.project_id,
                page: window.tableState.page,
                row_per_page: window.tableState.per_page
            },
            success: function(res) {
                if (res.success) {
                    renderTable(res.data.data);
                    if (typeof renderPagination === 'function') renderPagination(res.data.meta);
                    if (typeof renderInfo === 'function') renderInfo(res.data.meta);
                } else {
                    if (typeof renderError === 'function') renderError(res.message);
                }
            },
            error: function() {
                if (typeof renderError === 'function') renderError();
            }
        });
    };

    // 3. Start Loading Data
    window.loadData();

    // 4. Filter Handlers
    $('#fKategori').on('change', function() {
        window.tableState.kategori = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    });

    $('#fProject').on('change', function() {
        window.tableState.project_id = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    });

    // 5. Modal Behaviors
    initDrain('delModal', 'drainDel');

    // Inject document name to delete modal
    $(document).on('click', '.ib-x', function() {
        $('#delDocName').text($(this).data('nm') || 'ini');
    });

    // Drop zone
    initDropZone();

    // Select2 init
    $('#addModal').on('shown.bs.modal', function() {
        if (!$('#sel2Kat').hasClass('select2-hidden-accessible')) {
            initSelect2();
        }
    });
});

/* ── Table Rendering ── */
function renderTable(data) {
    if (data.length === 0) {
        if (typeof renderEmpty === 'function') renderEmpty('Tidak ada dokumen ditemukan.');
        return;
    }

    let html = '';
    data.forEach((item, index) => {
        const rowNum = (window.tableState.page - 1) * window.tableState.per_page + index + 1;
        const ext = item.file_info.extension;
        const iconClass = getFileIconClass(ext);
        const icon = getFileIcon(ext);
        const catClass = item.kategori || 's';

        const builderUrl = `${window.urlBuilderBase}/${item.id}/builder`;

        html += `
            <tr>
                <td class="td-no">${rowNum.toString().padStart(2, '0')}</td>
                <td>
                    <div class="td-file">
                        <div class="f-ico ${iconClass}"><i class="${icon}"></i></div>
                        <div>
                            <div class="f-nm">${item.nama}</div>
                            <div class="f-meta">${ext.toUpperCase()} &bull; ${item.file_info.size}</div>
                        </div>
                    </div>
                </td>
                <td><span class="cat cat-${catClass}"><i class="bi bi-file-text"></i> ${item.kategori_label}</span></td>
                <td><span class="proj-chip"><i class="bi bi-kanban" style="font-size:10px;opacity:.6"></i> ${item.project.name}</span></td>
                <td class="text-center"><span class="vbadge">${item.versi || '-'}</span></td>
                <td class="td-sz">${item.file_info.size}</td>
                <td class="td-dt">${item.tanggal_upload}</td>
                <td>
                    <div class="td-usr">
                        <div class="uav" style="background:linear-gradient(135deg,#1e3a5f,#3d6080)">${getInitials(item.uploader.name)}</div>
                        ${item.uploader.name}
                    </div>
                </td>
                <td>
                    <div class="act-row">
                        ${item.type !== 'file' ? `<a href="${builderUrl}" class="ibtn ib-v" title="Open Builder" style="background:rgba(168,85,247,0.1); color:#a855f7;"><i class="bi bi-magic"></i></a>` : ''}
                        <button class="ibtn ib-v" title="Lihat"><i class="bi bi-eye"></i></button>
                        <button class="ibtn ib-d" title="Unduh"><i class="bi bi-download"></i></button>
                        <button class="ibtn ib-e" title="Edit"><i class="bi bi-pencil-square"></i></button>
                        <button class="ibtn ib-x" title="Hapus" data-nm="${item.nama}" data-bs-toggle="modal" data-bs-target="#delModal">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    $('#dataBody').html(html);
}

/* ── UI Helpers ── */
function initDrain(mid, fid) {
    var m = document.getElementById(mid);
    if (!m) return;
    m.addEventListener('show.bs.modal', function() {
        var f = document.getElementById(fid);
        if (f) { f.classList.remove('go'); void f.offsetWidth; f.classList.add('go'); }
    });
}

function initDropZone() {
    var dz = document.getElementById('dropZone');
    if (!dz) return;
    dz.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('drag'); });
    dz.addEventListener('dragleave', function() { this.classList.remove('drag'); });
    dz.addEventListener('drop', function(e) { e.preventDefault(); this.classList.remove('drag'); });
}

function initSelect2() {
    var opts = { dropdownParent: $('#addModal'), placeholder: '-- Pilih --', allowClear: true, theme: 'default' };
    $('#sel2Type').select2($.extend({}, opts, { minimumResultsForSearch: Infinity })).on('change', function() {
        if ($(this).val() === 'file') {
            $('#dropZone').slideDown(300);
        } else {
            $('#dropZone').slideUp(300);
        }
    });
    $('#sel2Kat, #sel2Proj, #sel2User').select2(opts);
}

function getFileIconClass(ext) {
    const map = { pdf: 'pdf', xls: 'xls', xlsx: 'xls', doc: 'doc', docx: 'doc', zip: 'zip' };
    return map[ext.toLowerCase()] || 'doc';
}

function getFileIcon(ext) {
    const map = { pdf: 'bi bi-file-earmark-pdf-fill', xls: 'bi bi-file-earmark-spreadsheet-fill', xlsx: 'bi bi-file-earmark-spreadsheet-fill', doc: 'bi bi-file-earmark-word-fill', docx: 'bi bi-file-earmark-word-fill', zip: 'bi bi-file-zip-fill' };
    return map[ext.toLowerCase()] || 'bi bi-file-earmark-fill';
}

function getInitials(name) {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}
