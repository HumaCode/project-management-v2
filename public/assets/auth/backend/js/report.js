/**
 * Report Builder Module
 * Handles asset selection, sorting, and generation
 */
const ReportBuilder = {
    init() {
        console.log('Report Builder Initialized');
        
        // Manual Init for Select2 Dark
        $('#projectSelect').select2({
            placeholder: "Pilih Project...",
            allowClear: true,
            width: '100%'
        });

        $('#categorySelect').select2({
            placeholder: "Semua Kategori",
            allowClear: true,
            width: '100%'
        });

        this.bindEvents();
        this.initSortable();
    },

    initSortable() {
        const el = document.getElementById('canvasBody');
        if (el) {
            Sortable.create(el, {
                animation: 150,
                handle: '.canvas-handle',
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    console.log('Sort order updated');
                }
            });
        }
    },

    bindEvents() {
        // Project Selection
        $('#projectSelect').on('change', function() {
            const projectId = $(this).val();
            if (projectId) {
                // Reset category filter to "All" when project changes
                $('#categorySelect').val('').trigger('change.select2');
                ReportBuilder.loadAssets(projectId);
            }
        });
        
        // Category Selection
        $('#categorySelect').on('change', function() {
            const projectId = $('#projectSelect').val();
            if (projectId) {
                ReportBuilder.loadAssets(projectId);
            }
        });

        $('#categorySelect').on('change', function() {
            const projectId = $('#projectSelect').val();
            if (projectId) {
                ReportBuilder.loadAssets(projectId);
            }
        });

        // Add Asset to Canvas
        $(document).on('click', '.asset-item', function() {
            const data = $(this).data();
            ReportBuilder.addToCanvas(data);
        });

        // Remove from Canvas
        $(document).on('click', '.canvas-remove', function() {
            $(this).closest('.canvas-item').fadeOut(200, function() {
                $(this).remove();
                ReportBuilder.updateEmptyState();
            });
        });
    },

    loadAssets(projectId) {
        const categoryId = $('#categorySelect').val();
        
        showLoading(true, {
            title: "Memuat Dokumen...",
            message: "Sedang mengambil aset proyek"
        });

        $.ajax({
            url: '/laporan/assets',
            method: 'GET',
            data: { 
                project_id: projectId,
                category_id: categoryId 
            },
            success: (res) => {
                showLoading(false);
                this.renderAssets(res.data);
            },
            error: (xhr) => {
                showLoading(false);
                SCA.toast({
                    type: 'danger',
                    title: 'Gagal!',
                    message: 'Gagal memuat dokumen proyek.'
                });
            }
        });
    },

    renderAssets(assets) {
        const $grid = $('#assetGrid');
        const $empty = $('#assetEmpty');
        
        $grid.empty();
        
        if (!assets || assets.length === 0) {
            $grid.hide();
            $empty.html(`
                <div class="empty-icon-wrap">
                    <i class="bi bi-folder-x"></i>
                </div>
                <h5>Dokumen Tidak Ditemukan</h5>
                <p>Tidak ada dokumen ditemukan di kategori ini untuk project yang dipilih.</p>
            `).show();
            return;
        }

        $empty.hide();
        $grid.show();

        assets.forEach(asset => {
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(asset.file_info.extension?.toLowerCase());
            const thumb = isImage ? asset.file_info.url : 'https://placehold.co/140x105/0d1b2a/00c8ff?text=' + asset.file_info.extension;
            
            const html = `
                <div class="asset-item" 
                     data-id="${asset.id}" 
                     data-name="${asset.nama}" 
                     data-thumb="${thumb}"
                     data-desc="${asset.keterangan || ''}">
                    <img src="${thumb}" class="asset-thumb" alt="${asset.nama}">
                    <div class="asset-info">
                        <div class="asset-name" title="${asset.nama}">${asset.nama}</div>
                        <div class="asset-meta">${asset.file_info.extension.toUpperCase()} • ${asset.file_info.size}</div>
                    </div>
                    <div class="asset-add">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                </div>
            `;
            $grid.append(html);
        });
    },

    addToCanvas(data) {
        const html = `
            <div class="canvas-item" data-id="${data.id}">
                <div class="canvas-handle">
                    <i class="bi bi-grip-vertical"></i>
                </div>
                <img src="${data.thumb}" class="canvas-img" alt="">
                <div class="canvas-content">
                    <div class="canvas-title">${data.name}</div>
                    <textarea class="canvas-desc" rows="1" placeholder="Tambah keterangan...">${data.desc || ''}</textarea>
                </div>
                <div class="canvas-remove">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
        `;

        $('#canvasBody').append(html);
        this.updateEmptyState();
        
        // Auto resize textarea
        $('.canvas-desc').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    },

    updateEmptyState() {
        const count = $('.canvas-item').length;
        if (count > 0) {
            $('.canvas-empty').hide();
        } else {
            $('.canvas-empty').show();
        }
    },

    preview() {
        const items = [];
        $('.canvas-item').each(function() {
            items.push({
                id: $(this).data('id'),
                desc: $(this).find('.canvas-desc').val()
            });
        });

        if (items.length === 0) {
            SCA.toast({
                type: 'warning',
                title: 'Perhatian',
                message: 'Pilih setidaknya satu dokumen untuk pratinjau'
            });
            return;
        }

        this.submitForm('/laporan/preview', items, '_blank');
    },

    generate() {
        const items = [];
        $('.canvas-item').each(function() {
            items.push({
                id: $(this).data('id'),
                desc: $(this).find('.canvas-desc').val()
            });
        });

        const projectId = $('#projectSelect').val();

        if (!projectId || items.length === 0) {
            SCA.toast({
                type: 'warning',
                title: 'Perhatian',
                message: 'Pilih project dan setidaknya satu dokumen untuk membuat laporan.'
            });
            return;
        }

        showLoading(true, {
            title: "Menghasilkan PDF...",
            message: "Sedang menyusun laporan dan menyimpan ke riwayat."
        });

        $.ajax({
            url: '/laporan/generate',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                project_id: projectId,
                items: items,
                cover_image: (typeof CoverBuilder !== 'undefined') ? CoverBuilder.coverImage : null
            },
            success: (res) => {
                showLoading(false);
                SCA.toast({
                    type: 'success',
                    title: 'Berhasil!',
                    message: res.message
                });
                ReportHistory.load(); // Refresh history table
            },
            error: (xhr) => {
                showLoading(false);
                SCA.toast({
                    type: 'danger',
                    title: 'Gagal!',
                    message: xhr.responseJSON?.message || 'Gagal menghasilkan laporan PDF.'
                });
            }
        });
    },

    submitForm(url, items, target = '_self') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.target = target;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // Add Cover Image if exists
        if (typeof CoverBuilder !== 'undefined' && CoverBuilder.coverImage) {
            const coverInput = document.createElement('input');
            coverInput.type = 'hidden';
            coverInput.name = 'cover_image';
            coverInput.value = CoverBuilder.coverImage;
            form.appendChild(coverInput);
        }

        items.forEach((item, index) => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `items[${index}][id]`;
            idInput.value = item.id;
            form.appendChild(idInput);

            const descInput = document.createElement('input');
            descInput.type = 'hidden';
            descInput.name = `items[${index}][desc]`;
            descInput.value = item.desc;
            form.appendChild(descInput);
        });

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
};

/**
 * Report History Module
 * Handles AJAX history table and pagination
 */
const ReportHistory = {
    currentPage: 1,

    init() {
        this.load();
    },

    load(page = 1) {
        this.currentPage = page;
        const $body = $('#historyBody');
        const date = $('#historyDateFilter').val();
        
        $.ajax({
            url: '/laporan/history',
            method: 'GET',
            data: { 
                page: page,
                date: date
            },
            beforeSend: () => {
                // Optional: show small spinner in table
            },
            success: (res) => {
                this.render(res.data);
                this.renderPagination(res.meta);
            },
            error: () => {
                $body.html('<tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat riwayat.</td></tr>');
            }
        });
    },

    reset() {
        $('#historyDateFilter').val('');
        this.load(1);
    },

    render(data) {
        const $body = $('#historyBody');
        $body.empty();

        if (!data || data.length === 0) {
            $body.html('<tr><td colspan="5" class="text-center py-5 opacity-50">Belum ada riwayat laporan.</td></tr>');
            return;
        }

        data.forEach(item => {
            const date = new Date(item.created_at).toLocaleDateString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });

            const html = `
                <tr>
                    <td class="ps-4">
                        <div class="td-info-name">${item.title}</div>
                        <div class="td-info-desc">ID: ${item.id}</div>
                    </td>
                    <td>
                        <span class="badge-status bs-progress">
                            <span class="dot"></span>
                            ${item.project?.name || '-'}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="av-item" style="width: 28px; height: 28px; font-size: 10px;">${item.user?.initials || 'U'}</div>
                            <span class="fw-600" style="font-size: 13px;">${item.user?.name || 'Unknown'}</span>
                        </div>
                    </td>
                    <td class="td-mono">${date}</td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/laporan/download/${item.id}" class="btn-act" title="Download PDF">
                                <i class="bi bi-download"></i>
                            </a>
                            <button class="btn-act btn-act-danger" onclick="ReportHistory.delete('${item.id}')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            $body.append(html);
        });
    },

    renderPagination(meta) {
        const $pager = $('#historyPagination');
        $pager.empty();

        if (meta.last_page <= 1) {
            $pager.hide();
            return;
        }
        $pager.show();

        let html = `<div class="pagi-info">Menampilkan <span>${meta.total}</span> riwayat laporan</div>`;
        html += `<div class="pagi-btns">`;
        
        if (meta.current_page > 1) {
            html += `<button class="pg-btn" onclick="ReportHistory.load(${meta.current_page - 1})"><i class="bi bi-chevron-left"></i></button>`;
        }

        // Simple page numbers
        for (let i = 1; i <= meta.last_page; i++) {
            const active = i === meta.current_page ? 'active' : '';
            html += `<button class="pg-btn ${active}" onclick="ReportHistory.load(${i})">${i}</button>`;
        }

        if (meta.current_page < meta.last_page) {
            html += `<button class="pg-btn" onclick="ReportHistory.load(${meta.current_page + 1})"><i class="bi bi-chevron-right"></i></button>`;
        }

        html += `</div>`;
        $pager.append(html);
    },

    delete(id) {
        SCA.dialog({
            type: "danger",
            title: "Hapus Riwayat?",
            message: "File laporan akan dihapus permanen dari sistem.",
            confirmText: "Ya, Hapus",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            showLoading(true, {
                title: "Menghapus...",
                message: "Sedang menghapus file dan riwayat laporan."
            });

            $.ajax({
                url: `/laporan/${id}`,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: (res) => {
                    showLoading(false);
                    SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                    this.load(this.currentPage);
                },
                error: (xhr) => {
                    showLoading(false);
                    SCA.toast({ type: 'danger', title: 'Gagal', message: xhr.responseJSON?.message || 'Gagal menghapus riwayat.' });
                }
            });
        });
    }
};

$(document).ready(() => {
    ReportBuilder.init();
    ReportHistory.init();
});
