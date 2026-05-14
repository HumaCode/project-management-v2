$(function () {
    // AOS Init
    if (typeof AOS !== 'undefined') {
        AOS.init({ once: true, easing: 'ease-out-cubic', duration: 500, offset: 20 });
    }

    // Select2 Init
    function initSelect2() {
        $('.select2').each(function() {
            const $p = $(this).closest('.modal');
            const config = {
                width: '100%',
                placeholder: $(this).data('placeholder') || '-- Pilih --',
                allowClear: true
            };
            
            if ($p.length) {
                config.dropdownParent = $p;
            }
            
            $(this).select2(config);
        });
    }
    initSelect2();

    // State
    window.tableState = {
        search: '',
        category: '',
        project_id: '',
        priority: '',
        page: 1,
        per_page: 10
    };

    // Helpers
    function getCategoryIcon(cat) {
        const icons = {
            'Personal': 'bi-journal-text',
            'Project': 'bi-journal-bookmark-fill',
            'Meeting': 'bi-people-fill',
            'Technical': 'bi-code-square',
            'Task': 'bi-check2-square',
            'Penting': 'bi-exclamation-octagon-fill'
        };
        const cls = cat ? cat.toLowerCase() : 'personal';
        return `<div class="ct-ico ${cls}"><i class="bi ${icons[cat] || 'bi-journal-text'}"></i></div>`;
    }

    function getPriorityBadge(prio) {
        const config = {
            'tinggi': { cls: 'prio-h', label: 'Tinggi' },
            'sedang': { cls: 'prio-m', label: 'Sedang' },
            'rendah': { cls: 'prio-l', label: 'Rendah' }
        };
        const c = config[prio] || config.sedang;
        return `<span class="prio ${c.cls}"><span class="prio-dot"></span>${c.label}</span>`;
    }

    function getCategoryBadge(cat) {
        const cls = cat ? cat.toLowerCase() : 'personal';
        return `<span class="kat-badge kb-${cls}"><i class="bi bi-tag"></i>${cat}</span>`;
    }

    // Load Data
    window.loadData = function () {
        if (typeof window.renderLoading === 'function') {
            window.renderLoading(window.tableState.per_page);
        }

        $.ajax({
            url: window.catatanUrl,
            method: 'GET',
            data: {
                search: window.tableState.search,
                category: window.tableState.category,
                project_id: window.tableState.project_id,
                priority: window.tableState.priority,
                row_per_page: window.tableState.per_page,
                page: window.tableState.page
            },
            success: function (res) {
                if (res.success) {
                    const data = res.data.data;
                    const meta = res.data.meta;
                    const $tbody = $('#dataBody');

                    if (data.length === 0) {
                        if (typeof window.renderEmpty === 'function') window.renderEmpty();
                    } else {
                        let html = '';
                        data.forEach((item, i) => {
                            const rowIndex = (meta.current_page - 1) * meta.per_page + i + 1;
                            
                            html += `
                                <tr>
                                    <td class="td-no">${rowIndex.toString().padStart(2, '0')}</td>
                                    <td>
                                        <div class="td-title">
                                            ${getCategoryIcon(item.category)}
                                            <div>
                                                <div class="ct-nm">${item.title}</div>
                                                <div class="ct-preview">${item.content_preview}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${getCategoryBadge(item.category)}</td>
                                    <td>
                                        ${item.project_name ? `<span class="proj-chip"><i class="bi bi-kanban" style="font-size:10px;opacity:.6"></i>${item.project_name}</span>` : '<span style="color:var(--muted);font-size:12px">&mdash;</span>'}
                                    </td>
                                    <td>${getPriorityBadge(item.priority)}</td>
                                    <td>
                                        <div class="td-usr">
                                            <div class="uav" style="background:linear-gradient(135deg,var(--blue),var(--cyan))">${item.user.initials}</div>
                                            ${item.user.name}
                                        </div>
                                    </td>
                                    <td class="td-dt">${item.created_at}</td>
                                    <td class="td-dt">${item.updated_at_diff}</td>
                                    <td>
                                        <div class="act-row">
                                            <button class="ibtn ib-v btn-view" title="Lihat" 
                                                data-id="${item.id}"
                                                data-title="${item.title}"
                                                data-category="${item.category}"
                                                data-project="${item.project_name || '-'}"
                                                data-priority="${item.priority}"
                                                data-user="${item.user.name}"
                                                data-date="${item.created_at}"><i class="bi bi-eye"></i></button>
                                            <button class="ibtn ib-e btn-edit" title="Edit" data-id="${item.id}"><i class="bi bi-pencil"></i></button>
                                            <button class="ibtn ib-x btn-delete" title="Hapus" data-id="${item.id}" data-nm="${item.title}"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                        $tbody.html(html);

                        if (typeof window.renderInfo === 'function') window.renderInfo(meta);
                        if (typeof window.renderPagination === 'function') window.renderPagination(meta);
                    }
                }
            },
            error: function (xhr) {
                if (typeof window.renderError === 'function') window.renderError();
            }
        });
    };

    // Filter Listeners
    $('#filterCategory, #filterProject, #filterPriority').on('change', function () {
        window.tableState.category = $('#filterCategory').val();
        window.tableState.project_id = $('#filterProject').val();
        window.tableState.priority = $('#filterPriority').val();
        window.tableState.page = 1;
        window.loadData();
    });

    // Search Debounce
    let searchTimer;
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            window.tableState.search = $(this).val();
            window.tableState.page = 1;
            window.loadData();
        }, 400);
    });

    // Reset Filter
    $('#btnResetFilter').on('click', function() {
        const $btn = $(this);
        $btn.find('i').addClass('bi-spin'); // if we have spin class, or just let the CSS handle it
        
        $('#searchInput').val('');
        $('#filterCategory').val('');
        $('#filterProject').val(null).trigger('change');
        $('#filterPriority').val('');
        
        window.tableState = {
            search: '',
            category: '',
            project_id: '',
            priority: '',
            page: 1,
            per_page: 10
        };
        
        window.loadData();
        
        setTimeout(() => $btn.find('i').removeClass('bi-spin'), 600);
    });

    // Modal drain animation
    function initDrain(mid, fid) {
        var $m = $('#' + mid);
        if ($m.length === 0) return;
        $m.on('show.bs.modal', function () {
            var $f = $('#' + fid);
            if ($f.length === 0) return;
            $f.removeClass('go');
            void $f[0].offsetWidth; // force reflow
            $f.addClass('go');
        });
        $m.on('hidden.bs.modal', function () {
            var $f = $('#' + fid);
            if ($f.length > 0) $f.removeClass('go');
        });
    }
    initDrain('delModal', 'drainDel');

    // Delete name inject
    $(document).on('click', '.btn-delete', function () {
        var name = $(this).data('nm') || 'ini';
        var id = $(this).data('id');
        $('#delNm').text(name);
        $('#delModal').data('id', id);
        $('#delModal').modal('show');
    });

    // View modal populate (Now from AJAX context)
    $(document).on('click', '.btn-view', function () {
        var d = $(this).data();
        // Since content is long, we might want to fetch it via AJAX or just use the resource if included
        // For now, let's assume we fetch detail if needed, but I'll use a placeholder if not in data
        $('#viewTitle').html('<i class="bi bi-journal-text"></i>&nbsp;' + (d.title || 'Detail Catatan'));
        
        // Fetch full content via AJAX for view modal to be accurate
        const id = d.id;
        $('#viewBody').html('<div class="text-center py-4"><div class="spinner-border text-cyan spinner-border-sm"></div></div>');
        $('#viewModal').modal('show');

        $.get(`/catatan/${id}`, function(res) {
            if(res.success) {
                const item = res.data;
                $('#viewBody').html(item.content || '<em style="color:var(--muted)">Tidak ada isi.</em>');
                $('#viewMeta').html(`
                    <div class="view-meta-item"><i class="bi bi-tags-fill"></i>${item.category}</div>
                    <div class="view-meta-item"><i class="bi bi-kanban-fill"></i>${item.project_name || '-'}</div>
                    <div class="view-meta-item"><i class="bi bi-flag-fill"></i>${item.priority}</div>
                    <div class="view-meta-item"><i class="bi bi-person-fill"></i>${item.user.name}</div>
                    <div class="view-meta-item"><i class="bi bi-calendar3"></i>${item.created_at}</div>
                `);
            }
        });
    });

    // CKEditor 5 Initialization
    var addEditor, editEditor;

    class Base64UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }
        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => {
                    resolve({ default: reader.result });
                };
                reader.onerror = error => {
                    reject(error);
                };
                reader.readAsDataURL(file);
            }));
        }
        abort() {}
    }

    function createEditor(selector) {
        return ClassicEditor
            .create(document.querySelector(selector), {
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'heading', '|',
                        'bold', 'italic', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed'
                    ],
                    shouldNotGroupWhenFull: true
                },
                extraPlugins: [
                    function(editor) {
                        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                            return new Base64UploadAdapter(loader);
                        };
                    }
                ],
                mediaEmbed: {
                    previewsInData: true
                }
            });
    }

    $('#addModal').on('shown.bs.modal', function () {
        if (!addEditor && document.querySelector('#ckAdd')) {
            createEditor('#ckAdd').then(editor => {
                addEditor = editor;
            }).catch(error => {
                console.error(error);
            });
        }
    });

    $('#editModal').on('shown.bs.modal', function () {
        if (!editEditor && document.querySelector('#ckEdit')) {
            createEditor('#ckEdit').then(editor => {
                editEditor = editor;
            }).catch(error => {
                console.error(error);
            });
        }
    });

    $('#addModal').on('hidden.bs.modal', function () {
        if (addEditor) {
            addEditor.setData('');
        }
    });

    // Edit modal populate
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editModal').modal('show');
        
        // Reset form and show loading
        $('#form_edit')[0].reset();
        if (editEditor) editEditor.setData('');
        
        $.get(`/catatan/${id}`, function(res) {
            if(res.success) {
                const item = res.data;
                $('#edit_id').val(item.id);
                $('#edit_title').val(item.title);
                $('#edit_category').val(item.category);
                $('#edit_priority').val(item.priority);
                $('#edit_project_id').val(item.project_id || '').trigger('change');
                
                if (editEditor) {
                    editEditor.setData(item.content || '');
                } else {
                    // Fallback if editor not ready
                    $('#ckEdit').val(item.content || '');
                }
                
                $('#form_edit').attr('action', `/catatan/${item.id}`);
            }
        });
    });

    // Form Edit Submit Handler
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#form_edit')
            .onSuccess(function() {
                $('#editModal').modal('hide');
                $('#form_edit')[0].reset();
                if (editEditor) editEditor.setData('');
                $('.select2').val(null).trigger('change');
                if (typeof window.loadData === 'function') window.loadData();
            })
            .init();
    }

    // Sync CKEditor for Edit
    $('#form_edit').on('submit', function() {
        if (editEditor) {
            $('#ckEdit').val(editEditor.getData());
        }
    });

    // Delete Modal Populate
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nm = $(this).data('nm');
        $('#delModal').data('id', id).modal('show');
        $('#delNm').text(nm);
    });

    // Delete Action
    $(document).on('click', '.btn-mdel', function() {
        const id = $('#delModal').data('id');
        const $btn = $(this);
        const originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<span><i class="spinner-border spinner-border-sm"></i> Menghapus...</span>');

        if (typeof SCA !== 'undefined') {
            SCA.loading({
                title: "Menghapus...",
                message: "Mohon tunggu sebentar"
            });
        }

        $.ajax({
            url: `/catatan/${id}`,
            method: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if(res.success) {
                    $('#delModal').modal('hide');
                    if (typeof window.loadData === 'function') window.loadData();
                    if (typeof SCA !== 'undefined') SCA.success('Berhasil', res.message, true);
                }
            },
            error: function(xhr) {
                if (typeof SCA !== 'undefined') SCA.error('Gagal', 'Gagal menghapus catatan', true);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalHtml);
                if (typeof SCA !== 'undefined') SCA.close();
            }
        });
    });

    // Initial Load
    window.loadData();

    // Form Add Submit Handler
    if (typeof handleFormSubmit === 'function') {
        handleFormSubmit('#form_add')
            .onSuccess(function() {
                $('#addModal').modal('hide');
                $('#form_add')[0].reset();
                if (addEditor) addEditor.setData('');
                $('.select2').val(null).trigger('change');
                if (typeof window.loadData === 'function') window.loadData();
            })
            .init();
    }

    // Override submit to sync CKEditor
    $('#form_add').on('submit', function() {
        if (addEditor) {
            $('#ckAdd').val(addEditor.getData());
        }
    });
});
