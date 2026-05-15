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
                <i class="bi bi-folder-x d-block mb-3" style="font-size: 40px; opacity: 0.3;"></i>
                Tidak ada dokumen ditemukan di kategori ini.
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
                    <div style="font-size: 11px; color: var(--muted); margin-bottom: 2px;">${data.name}</div>
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
                id: $(this).data('id')
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

        showLoading(true, {
            title: "Menyiapkan Pratinjau...",
            message: "Sedang menyusun tampilan laporan"
        });

        setTimeout(() => {
            showLoading(false);
            SCA.toast({
                type: 'info',
                title: 'Pratinjau!',
                message: 'Menampilkan pratinjau urutan dokumen.'
            });
            // Future logic for actual preview modal
        }, 800);
    },

    generate() {
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
                message: 'Pilih setidaknya satu dokumen untuk laporan'
            });
            return;
        }

        showLoading(true, {
            title: "Generating PDF...",
            message: "Mohon tunggu sebentar"
        });

        // AJAX to generate PDF
        setTimeout(() => {
            showLoading(false);
            SCA.toast({
                type: 'success',
                title: 'Berhasil!',
                message: 'Laporan berhasil di-generate!'
            });
        }, 1500);
    }
};

$(document).ready(() => {
    ReportBuilder.init();
});
