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
        type: '',
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
                type: window.tableState.type,
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
    setTimeout(() => {
        window.loadData();
    }, 300);

    initSelect2();
    initDropZone();
    initDropZoneEdit();

    // Inisialisasi submit form menggunakan helper global dari main.js
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit("#formDokumen").onSuccess(function(res) {
            $('#addModal').modal('hide');
            $('#formDokumen')[0].reset();
            $('#previewContainer').hide();
            $('#dropZoneContent').show();
            $('#fileName').html('PDF, DOCX, XLSX, PPTX, ZIP, PNG &mdash; Maks. 50 MB');
            window.loadData();
        }).init();

        handleFormSubmit("#formEditDokumen").onSuccess(function(res) {
            $('#editModal').modal('hide');
            window.loadData();
        }).init();
    }

    /* ── Filter Handlers ── */
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

    $('#fType').on('change', function() {
        window.tableState.type = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    });

    $('#btnReset').on('click', function() {
        window.tableState.search = '';
        window.tableState.kategori = '';
        window.tableState.project_id = '';
        window.tableState.type = '';
        window.tableState.page = 1;

        $('#searchInput').val('');
        $('#fKategori').val('').trigger('change.select2');
        $('#fProject').val('').trigger('change.select2');
        $('#fType').val('').trigger('change.select2');

        window.loadData();
    });

    $('#searchInput').on('keyup', _.debounce(function() {
        window.tableState.search = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    }, 500));

    $('#tampilData').on('change', function() {
        window.tableState.per_page = $(this).val();
        window.tableState.page = 1;
        window.loadData();
    });

    /* ── Modal Behaviors ── */
    initDrain('delModal', 'drainDel');

    let deleteId = null;
    $(document).on('click', '.ib-x', function() {
        deleteId = $(this).data('id');
        $('#delDocName').text($(this).data('nm') || 'ini');
    });

    $(document).on('click', '.btn-mdel', function() {
        if (!deleteId) return;
        const btn = $(this);
        
        // Add loading state if you have one, or just disable
        btn.prop('disabled', true).html('<span><i class="bi bi-hourglass-split"></i> Menghapus...</span>');

        if (typeof SCA !== 'undefined') {
            SCA.loading({
                title: "Menghapus...",
                message: "Mohon tunggu sebentar"
            });
        }

        $.ajax({
            url: `${window.urlBuilderBase}/${deleteId}`,
            method: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                btn.prop('disabled', false).html('<span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span>');
                $('#delModal').modal('hide');
                
                SCA.toast({
                    type: res.success ? "success" : "danger",
                    title: res.success ? "Berhasil!" : "Gagal!",
                    message: res.message ?? "Dokumen berhasil dihapus.",
                });

                if (res.success) {
                    window.loadData();
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html('<span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span>');
                SCA.toast({
                    type: "danger",
                    title: "Error!",
                    message: "Terjadi kesalahan sistem saat menghapus data."
                });
            },
            complete: function() {
                SCA.close();
            }
        });
    });

    // Detail Logic
    $(document).on('click', '.ib-v:not(a)', function() {
        const id = $(this).data('id');
        const url = `${window.urlBuilderBase}/${id}`;
        
        if (typeof showLoading === 'function') {
            showLoading(true, { title: 'Memuat Detail', message: 'Sedang mengambil data dokumen...' });
        }
        
        $.ajax({
            url: url,
            method: 'GET',
            success: function(res) {
                if (typeof showLoading === 'function') showLoading(false);
                
                if (res.success) {
                    const d = res.data;
                    const ext = d.file_info.extension;
                    
                    $('#detailNama').text(d.nama);
                    $('#detailMeta').html(`${ext.toUpperCase()} &bull; ${d.file_info.size}`);
                    $('#detailKategori').text(d.kategori_label).attr('class', `cat cat-${d.kategori}`);
                    $('#detailProject').text(d.project.name);
                    $('#detailVersi').text(d.versi || '-');
                    $('#detailTanggal').text(d.tanggal_upload);
                    $('#detailUploaderName').text(d.uploader.name);
                    $('#detailUploaderAvatar').text(getInitials(d.uploader.name));
                    $('#detailKeterangan').text(d.keterangan || 'Tidak ada keterangan.');
                    
                    if (d.file_info.url) {
                        $('#btnDownloadDetail').attr('href', d.file_info.url).show();
                    } else {
                        $('#btnDownloadDetail').hide();
                    }

                    // Preview logic
                    if (d.file_info.url && ext.match(/(jpg|jpeg|png|webp|gif)$/i)) {
                        $('#detailImagePreview img').attr('src', d.file_info.url);
                        $('#detailImagePreview').show();
                        $('#detailFileIcon').hide();
                    } else {
                        $('#detailImagePreview').hide();
                        $('#detailFileIcon').show().attr('class', `detail-icon-large ${getFileIconClass(ext)}`)
                            .html(`<i class="${getFileIcon(ext)}"></i>`);
                    }

                    setTimeout(() => {
                        $('#showModal').modal('show');
                    }, 400);
                }
            },
            error: function() {
                if (typeof showLoading === 'function') showLoading(false);
                SCA.toast({ type: 'danger', title: 'Error', message: 'Gagal memuat data.' });
            }
        });
    });

    // Edit Logic
    $(document).on('click', '.ib-e', function() {
        const id = $(this).data('id');
        const url = `${window.urlBuilderBase}/${id}`;
        
        $('#formEditDokumen')[0].reset();
        $('#previewContainerEdit').hide();
        $('#dropZoneContentEdit').show();

        if (typeof showLoading === 'function') {
            showLoading(true, { title: 'Menyiapkan Data', message: 'Mohon tunggu sebentar...' });
        }

        $.ajax({
            url: url,
            method: 'GET',
            success: function(res) {
                if (typeof showLoading === 'function') showLoading(false);
                
                if (res.success) {
                    const d = res.data;
                    $('#formEditDokumen').attr('action', `${window.urlBuilderBase}/${d.id}`);
                    $('#editNama').val(d.nama);
                    $('#editVersi').val(d.versi);
                    $('#editTanggal').val(d.tanggal_upload_raw || '');
                    $('#editKeterangan').val(d.keterangan);
                    
                    $('#sel2TypeEdit').val(d.type).trigger('change');
                    $('#sel2KatEdit').val(d.kategori).trigger('change');
                    $('#sel2ProjEdit').val(d.project.id).trigger('change');
                    $('#sel2UserEdit').val(d.uploader.id).trigger('change');

                    if (d.file_info && d.file_info.url && (d.file_info.extension.match(/(jpg|jpeg|png|webp|gif)$/i))) {
                        $('#imagePreviewEdit').attr('src', d.file_info.url);
                        $('#previewContainerEdit').show();
                        $('#dropZoneContentEdit').hide();
                    }

                    setTimeout(() => {
                        $('#editModal').modal('show');
                    }, 400);
                }
            },
            error: function() {
                if (typeof showLoading === 'function') showLoading(false);
                SCA.toast({ type: 'danger', title: 'Error', message: 'Gagal mengambil data untuk diedit.' });
            }
        });
    });

    $('#addModal').on('shown.bs.modal', function() {
        if (!$('#sel2Kat').hasClass('select2-hidden-accessible')) {
            initSelect2();
        }
    });

    $('#editModal').on('shown.bs.modal', function() {
        if (!$('#sel2KatEdit').hasClass('select2-hidden-accessible')) {
            initSelect2Edit();
        }
    });

    // Add Modal Loading Effect
    $(document).on('click', '.btn-add', function(e) {
        const target = $(this).data('bs-target');
        if (!target) return;

        e.preventDefault();
        e.stopPropagation();

        if (typeof showLoading === 'function') {
            showLoading(true, { title: 'Membuka Form', message: 'Sedang menyiapkan formulir...' });
        }

        setTimeout(() => {
            if (typeof showLoading === 'function') showLoading(false);
            $(target).modal('show');
        }, 400);
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
                        ${(item.file_info.url && ext.match(/(jpg|jpeg|png|webp|gif)$/i)) 
                            ? `<div class="f-ico img" style="background:none;border:1px solid var(--bd);overflow:hidden;padding:0">
                                  <img src="${item.file_info.url}" style="width:100%;height:100%;object-fit:cover;">
                               </div>`
                            : `<div class="f-ico ${iconClass}"><i class="${icon}"></i></div>`
                        }
                        <div>
                            <div class="f-nm">${item.nama}</div>
                            <div class="f-meta">${ext} &bull; ${item.file_info.size}</div>
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
                        <button class="ibtn ib-v" title="Lihat" data-id="${item.id}"><i class="bi bi-eye"></i></button>
                        ${item.file_info.url ? `<a href="${item.file_info.url}" target="_blank" class="ibtn ib-d" title="Unduh" download><i class="bi bi-download"></i></a>` : ''}
                        <button class="ibtn ib-e" title="Edit" data-id="${item.id}"><i class="bi bi-pencil-square"></i></button>
                        <button class="ibtn ib-x" title="Hapus" data-id="${item.id}" data-nm="${item.nama}" data-bs-toggle="modal" data-bs-target="#delModal">
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
    const $zone = $('#dropZone');
    const $input = $('#fileInput');
    const $name = $('#fileName');
    if (!$zone.length) return;
    $zone.on('click', function() { $input.click(); });
    $input.on('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            $name.html(`<strong style="color:var(--cyan)">${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`);
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#previewContainer').fadeIn(300);
                    $('#dropZoneContent').fadeOut(300);
                }
                reader.readAsDataURL(file);
            } else { $('#previewContainer').hide(); $('#dropZoneContent').show(); }
        }
    });
    $('#btnRemovePreview').on('click', function(e) {
        e.stopPropagation(); $input.val('');
        $('#previewContainer').fadeOut(200); $('#dropZoneContent').fadeIn(200);
        $name.html('PDF, DOCX, XLSX, PPTX, ZIP, PNG &mdash; Maks. 50 MB');
    });
}

function initDropZoneEdit() {
    const $zone = $('#dropZoneEdit');
    const $input = $('#fileInputEdit');
    const $name = $('#fileNameEdit');
    if (!$zone.length) return;
    $zone.on('click', function() { $input.click(); });
    $input.on('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            $name.html(`<strong style="color:var(--cyan)">${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)`);
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreviewEdit').attr('src', e.target.result);
                    $('#previewContainerEdit').fadeIn(300);
                    $('#dropZoneContentEdit').fadeOut(300);
                }
                reader.readAsDataURL(file);
            } else { $('#previewContainerEdit').hide(); $('#dropZoneContentEdit').show(); }
        }
    });
    $('#btnRemovePreviewEdit').on('click', function(e) {
        e.stopPropagation(); $input.val('');
        $('#previewContainerEdit').fadeOut(200); $('#dropZoneContentEdit').fadeIn(200);
        $name.html('Biarkan kosong jika tidak ingin mengubah file');
    });
}

function initSelect2() {
    $('.select2').each(function() {
        const $this = $(this);
        const isModal = $this.closest('.modal').length > 0;
        const isKat = $this.attr('id') === 'sel2Kat' || $this.attr('id') === 'sel2KatEdit' || $this.attr('id') === 'fKategori';
        
        const opts = { 
            placeholder: $this.attr('placeholder') || '-- Pilih --', 
            allowClear: true, 
            theme: 'default',
            width: isModal ? '100%' : 'resolve'
        };

        if (isModal) {
            opts.dropdownParent = $this.closest('.modal');
        }

        // Template for Category with Icon
        if (isKat) {
            opts.templateResult = formatKat;
            opts.templateSelection = formatKat;
            opts.escapeMarkup = function(m) { return m; };
        }

        // Special handling for Tipe Dokumen in Add Modal
        if ($this.attr('id') === 'sel2Type' || $this.attr('id') === 'tampilData') {
            $this.select2($.extend({}, opts, { minimumResultsForSearch: Infinity })).on('change', function() {
                if ($this.attr('id') === 'sel2Type') {
                    if ($(this).val() === 'file') { $('#dropZone').slideDown(300); } else { $('#dropZone').slideUp(300); }
                }
            });
        } else {
            $this.select2(opts);
        }
    });
}

function formatKat(state) {
    if (!state.id) return state.text;
    const $option = $(state.element);
    const icon = $option.data('icon') || 'bi-tag';
    const color = $option.data('color') || 'var(--cyan)';
    return `<span><i class="bi ${icon}" style="color:${color};margin-right:8px;"></i>${state.text}</span>`;
}

function initSelect2Edit() {
    $('#editModal .select2').each(function() {
        const $this = $(this);
        const opts = { 
            dropdownParent: $('#editModal'),
            placeholder: $this.attr('placeholder') || '-- Pilih --', 
            allowClear: true, 
            theme: 'default',
            width: '100%'
        };

        if ($this.attr('id') === 'sel2TypeEdit') {
            $this.select2($.extend({}, opts, { minimumResultsForSearch: Infinity })).on('change', function() {
                if ($(this).val() === 'file') { $('#dropZoneEdit').slideDown(300); } else { $('#dropZoneEdit').slideUp(300); }
            });
        } else {
            $this.select2(opts);
        }
    });
}

function getFileIconClass(ext) {
    const map = { 
        pdf: 'pdf', xls: 'xls', xlsx: 'xls', doc: 'doc', docx: 'doc', zip: 'zip',
        png: 'img', jpg: 'img', jpeg: 'img', webp: 'img', gif: 'img',
        article: 'pdf', code: 'xls'
    };
    return map[ext.toLowerCase()] || 'doc';
}

function getFileIcon(ext) {
    const map = { 
        pdf: 'bi bi-file-earmark-pdf-fill', 
        xls: 'bi bi-file-earmark-spreadsheet-fill', 
        xlsx: 'bi bi-file-earmark-spreadsheet-fill', 
        doc: 'bi bi-file-earmark-word-fill', 
        docx: 'bi bi-file-earmark-word-fill', 
        zip: 'bi bi-file-zip-fill',
        png: 'bi bi-file-earmark-image-fill',
        jpg: 'bi bi-file-earmark-image-fill',
        jpeg: 'bi bi-file-earmark-image-fill',
        webp: 'bi bi-file-earmark-image-fill',
        gif: 'bi bi-file-earmark-image-fill',
        article: 'bi bi-file-earmark-richtext-fill',
        code: 'bi bi-file-earmark-code-fill'
    };
    return map[ext.toLowerCase()] || 'bi bi-file-earmark-fill';
}

function getInitials(name) {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
}
