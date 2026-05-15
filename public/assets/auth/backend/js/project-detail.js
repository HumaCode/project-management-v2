/* AOS Initialization */
if (typeof AOS !== 'undefined') {
    AOS.init({ once: true, easing: 'ease-out-cubic', duration: 500, offset: 20 });
}

/* Modal drain utility */
function initDrain(modalId, fillId){
  var m=document.getElementById(modalId);
  if(!m) return;
  m.addEventListener('show.bs.modal',function(){
    var f=document.getElementById(fillId);
    if(!f)return;
    f.classList.remove('go');
    void f.offsetWidth;
    f.classList.add('go');
  });
  m.addEventListener('hidden.bs.modal',function(){
    var f=document.getElementById(fillId);
    if(f)f.classList.remove('go');
  });
}

$(function() {
    const $container = $('#projectContainer');
    const dataUrl = $container.data('url');
    const projectId = $container.data('id');
    
    // Activity Pagination State
    let activityCurrentPage = 1;
    let activityHasMore = false;

    function renderDocuments(dokumens, append = false) {
        let docRows = '';
        dokumens.forEach((doc, i) => {
            const num = (i + 1).toString().padStart(2, '0');
            const extInfo = doc.type === 'file' ? 'FILE' : (doc.type === 'article' ? 'ARTIKEL' : 'KODE');
            
            docRows += `
                <tr>
                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">${num}</td>
                    <td>
                        <div class="doc-name">
                            <div class="doc-ico ${doc.kategori}">
                                <i class="bi ${doc.type === 'code' ? 'bi-code-square' : (doc.type === 'article' ? 'bi-file-text' : 'bi-file-earmark-fill')}"></i>
                            </div>
                            <div>
                                <div class="doc-nm">${doc.nama}<span class="doc-ver">v${doc.versi || '1'}</span></div>
                                <div style="font-size:11px;color:var(--muted)">${extInfo} · ${doc.kategori_label}</div>
                            </div>
                        </div>
                    </td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v${doc.versi || '1.0'}</span></td>
                    <td class="d-none d-sm-table-cell doc-size">${doc.file_size || '-'}</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">${formatDateIndo(doc.created_at)}</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">${doc.uploader ? doc.uploader.name : '-'}</td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:center">
                            <button class="btn-tbl-ico btn-view-doc" data-id="${doc.id}" title="Lihat"><i class="bi bi-eye"></i></button>
                            <button class="btn-tbl-ico btn-del-doc" data-id="${doc.id}" data-nm="${doc.nama}" data-bs-toggle="modal" data-bs-target="#delDocModal" title="Hapus"><i class="bi bi-trash3"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        });

        if (docRows === '' && !append) docRows = '<tr><td colspan="7" class="text-center py-4 text-muted">Belum ada dokumen.</td></tr>';
        
        if (append) {
            $('#docTbl tbody').append(docRows);
        } else {
            $('#docTbl tbody').html(docRows);
        }
    }

    function renderDocPagination(meta) {
        const wrap = $('#docPaginationWrap');
        if (meta.last_page <= 1) {
            wrap.hide().html('');
            return;
        }

        wrap.show().html('');
        
        // Prev button
        wrap.append(`<button class="pg-btn btn-doc-pg" data-page="${meta.current_page - 1}" ${meta.current_page === 1 ? 'disabled' : ''}><i class="bi bi-chevron-left"></i></button>`);

        // Smart Pagination Logic
        const lastPage = meta.last_page;
        const current = meta.current_page;
        const range = 1; 
        
        for (let i = 1; i <= lastPage; i++) {
            if (i === 1 || i === lastPage || (i >= current - range && i <= current + range)) {
                wrap.append(`<button class="pg-btn btn-doc-pg ${i === current ? 'active' : ''}" data-page="${i}">${i}</button>`);
            } else if (i === 2 && current - range > 2) {
                wrap.append(`<span style="color:var(--muted); padding:0 4px; font-size:10px; align-self:center">...</span>`);
                i = current - range - 1; // Jump to before range
            } else if (i === current + range + 1 && current + range < lastPage - 1) {
                wrap.append(`<span style="color:var(--muted); padding:0 4px; font-size:10px; align-self:center">...</span>`);
                i = lastPage - 1; // Jump to last page
            }
        }

        // Next button
        wrap.append(`<button class="pg-btn btn-doc-pg" data-page="${meta.current_page + 1}" ${meta.current_page === meta.last_page ? 'disabled' : ''}><i class="bi bi-chevron-right"></i></button>`);
    }

    $(document).on('click', '.btn-doc-pg', function() {
        const page = $(this).data('page');
        if (!page || $(this).prop('disabled') || $(this).hasClass('active')) return;

        $('#docTbl tbody').css('opacity', '0.5');

        $.ajax({
            url: `/projects/${projectId}/dokumens`,
            method: 'GET',
            data: {
                page: page,
                per_page: 5
            },
            success: function(res) {
                if (res.success) {
                    const docData = res.data.data;
                    const docMeta = res.data;
                    
                    renderDocuments(docData, false);
                    renderDocPagination(docMeta);
                    
                    $('#docFooter').html(`Menampilkan ${docData.length} dari ${docMeta.total} dokumen &mdash; <a href="/dokumen?project_id=${projectId}" style="color:var(--cyan)">Lihat semua</a>`);
                }
            },
            complete: function() {
                $('#docTbl tbody').css('opacity', '1');
            }
        });
    });

    /* Count-up animation */
    function countUp(el, target){
      var dur=1200,start=performance.now();
      (function step(now){
          var p=Math.min((now-start)/dur,1),
          ease=1-Math.pow(1-p,3);
          el.textContent=Math.round(ease*target);
          if(p<1)requestAnimationFrame(step);
          else el.textContent=target;
      })(performance.now());
    }

    /* Tabs */
    $(document).on('click', '.tab-btn', function() {
        var tab = this.dataset.tab;
        $('.tab-btn').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
        $('#tab-'+tab).addClass('active');

        if (tab === 'catatan') {
            setTimeout(() => {
                const nw = document.getElementById('noteWrap');
                if (nw) nw.scrollTop = nw.scrollHeight;
            }, 50);
        }
    });

    /* Format Date Indo Helper */
    function formatDateIndo(dateStr) {
        if(!dateStr) return '-';
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const d = new Date(dateStr);
        return `${d.getDate().toString().padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
    }

    /* Helper: Get Status Label & Class */
    function getStatusInfo(status) {
        const map = {
            'in_progress': { label: 'In Progress', class: 'tag-prog' },
            'done': { label: 'Done', class: 'tag-done' },
            'to_do': { label: 'To Do', class: 'tag-todo' },
            'on_hold': { label: 'On Hold', class: 'tag-hold' }
        };
        return map[status] || { label: status, class: 'tag-prog' };
    }

    /* Load Data AJAX */
    window.loadDetailData = function() {
        $.ajax({
            url: dataUrl,
            method: 'GET',
            success: function(res) {
                if (res.success) {
                    const data = res.data;
                    const p = data.project;
                    const stats = data.stats;

                    // Header & Basic Info
                    $('#projectName').text(p.name);
                    $('#projectIcon').attr('class', `bi ${p.icon || 'bi-kanban-fill'}`);
                    
                    const statusInfo = getStatusInfo(p.status);
                    const statusHtml = `<span class="tdot"></span>${statusInfo.label}`;
                    $('#projectStatus, #infoStatus').attr('class', `tag ${statusInfo.class}`).html(statusHtml);

                    $('#editBtn').attr('href', `/projects/${p.id}/edit`);
                    
                    // Progress
                    $('#progPct').text(`${p.progress}%`);
                    $('#progFill').css('width', `${p.progress}%`);

                    // Stats
                    $('#statDocs').attr('data-count', stats.docs_count);
                    $('#statNotes').attr('data-count', stats.notes_count);
                    $('#statMembers').attr('data-count', stats.members_count);
                    $('#statDays').attr('data-count', stats.days_remaining);
                    
                    $('#tabDocCount').text(stats.docs_count);
                    $('#tabNoteCount').text(stats.notes_count);

                    $('#statDaysIcon').attr('class', `msc-ico ${stats.days_remaining_class === 'err' ? 'r' : (stats.days_remaining_class === 'warn' ? 'y' : 'g')}`);

                    // Trigger CountUp for stats
                    $('.msc-val').each(function() {
                        countUp(this, parseInt($(this).attr('data-count')));
                    });

                    // Info Grid
                    $('#infoCreator').text(p.creator ? p.creator.name : '-');
                    $('#infoTeamName').text(p.team ? p.team.name : 'No Team');
                    $('#infoStartDate').text(formatDateIndo(p.start_date));
                    $('#infoDeadline').text(formatDateIndo(p.deadline));
                    
                    let badgeClass = 'dl-ok';
                    if(stats.days_remaining_class === 'err') badgeClass = 'dl-err';
                    else if(stats.days_remaining_class === 'warn') badgeClass = 'dl-warn';
                    
                    $('#infoDaysBadge').attr('class', `dl-badge ${badgeClass}`)
                        .html(`<i class="bi bi-exclamation-circle-fill"></i>H-${stats.days_remaining}`);

                    $('#infoDesc').html(p.description || '<span class="text-muted">Tidak ada deskripsi.</span>');

                    // PICs
                    let picStackHtml = '';
                    let picListHtml = '';
                    const maxStack = 4;
                    
                    p.pics.forEach((pic, i) => {
                        // Stack
                        if (i < maxStack) {
                            if (pic.display_avatar) {
                                picStackHtml += `<div class="av" title="${pic.name}" style="background-image:url('${pic.display_avatar}')"></div>`;
                            } else {
                                picStackHtml += `<div class="av" title="${pic.name}">${pic.initials}</div>`;
                            }
                        }
                        
                        // List
                        picListHtml += `
                            <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                                <div class="av-small" style="${pic.display_avatar ? `background-image:url('${pic.display_avatar}')` : ''}">${pic.display_avatar ? '' : pic.initials}</div>
                                <span>${pic.name}</span>
                                <span style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">${pic.role_name || 'Anggota'}</span>
                            </div>
                        `;
                    });

                    if (p.pics.length > maxStack) {
                        picStackHtml += `<div class="av more" title="+${p.pics.length - maxStack} lainnya">+${p.pics.length - maxStack}</div>`;
                    }

                    $('#picStack').html(picStackHtml);
                    $('#picList').html(picListHtml);

                    // Documents Table
                    const docData = data.dokumens.data;
                    const docMeta = data.dokumens;
                    
                    renderDocuments(docData, false);
                    renderDocPagination(docMeta);

                    $('#tabDocCount').text(docMeta.total);
                    $('#docFooter').html(`Menampilkan ${docData.length} dari ${docMeta.total} dokumen &mdash; <a href="/dokumen?project_id=${p.id}" style="color:var(--cyan)">Lihat semua</a>`);

                    // Notes (Diskusi)
                    let noteHtml = '';
                    const currentUserId = $('#projectContainer').data('current-user-id');
                    
                    // Reverse to show oldest at top, newest at bottom
                    const reversedCatatans = [...data.catatans].reverse();
                    
                    if (reversedCatatans.length > 0) {
                        noteHtml += '<div style="margin-top: auto;"></div>'; // Spacer to push messages to bottom
                    }

                    reversedCatatans.forEach(note => {
                        const isMe = note.is_me;
                        const avContent = note.user && note.user.display_avatar 
                            ? `<img src="${note.user.display_avatar}" alt="${note.user.name}">` 
                            : (note.user ? note.user.initials : '??');

                        let attachHtml = '';
                        if (note.attachments && note.attachments.length > 0) {
                            attachHtml = '<div class="note-attachments">';
                            note.attachments.forEach(file => {
                                if (file.type === 'image') {
                                    attachHtml += `
                                        <a href="${file.url}" data-fslightbox="gallery" class="attach-img">
                                            <img src="${file.url}" alt="${file.name}">
                                        </a>
                                    `;
                                } else {
                                    attachHtml += `
                                        <a href="${file.url}" download="${file.name}" class="attach-file">
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                            <span>${file.name}</span>
                                        </a>
                                    `;
                                }
                            });
                            attachHtml += '</div>';
                        }

                        let quoteHtml = '';
                        if (note.parent) {
                            quoteHtml = `
                                <div class="note-quote" data-target="#note-${note.parent.id}">
                                    <div class="quote-author">${note.parent.user_name}</div>
                                    <div class="quote-text">${note.parent.content || 'Lampiran file'}</div>
                                </div>
                            `;
                        }

                        noteHtml += `
                            <div class="note-card ${isMe ? 'is-me' : ''}" id="note-${note.id}">
                                <div class="note-head">
                                    ${!isMe ? `<div class="note-av">${avContent}</div>` : ''}
                                    <div class="note-author-meta">
                                        <div class="note-author">${note.user ? note.user.name : 'Unknown'}</div>
                                        <div class="note-time">
                                            ${note.created_at_human || 'Baru saja'}
                                            ${note.is_edited ? '<span class="edited-label">(diedit)</span>' : ''}
                                        </div>
                                    </div>
                                    ${isMe ? `<div class="note-av">${avContent}</div>` : ''}
                                </div>
                                <div class="note-body">
                                    ${quoteHtml}
                                    ${attachHtml}
                                    ${note.content ? `<div class="note-text">${note.content}</div>` : ''}
                                </div>
                                <div class="note-actions">
                                    <button class="note-btn btn-reply-note" data-id="${note.id}"><i class="bi bi-reply-fill"></i> Balas</button>
                                    ${isMe ? `
                                        ${note.can_edit ? `<button class="note-btn btn-edit-note" data-id="${note.id}"><i class="bi bi-pencil"></i> Edit</button>` : ''}
                                        ${note.can_delete ? `<button class="note-btn btn-del-note" data-id="${note.id}"><i class="bi bi-trash3"></i> Hapus</button>` : ''}
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });
                    if (noteHtml === '') {
                        noteHtml = `
                            <div style="height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; opacity:0.6; padding-bottom:40px">
                                <div style="font-size: 50px; color: var(--cyan); opacity: 0.2; margin-bottom: 15px;">
                                    <i class="bi bi-chat-left-dots"></i>
                                </div>
                                <div style="color: var(--muted); font-size: 14px; font-family: var(--mono); text-transform:uppercase; letter-spacing:1px">Belum ada diskusi</div>
                                <div style="color: var(--dim); font-size: 12px; margin-top:5px">Mulai percakapan pertama Anda hari ini.</div>
                            </div>
                        `;
                    }
                    $('#noteWrap').html(noteHtml);
                    
                    // Refresh fslightbox for dynamic elements
                    if (typeof refreshFsLightbox === 'function') {
                        refreshFsLightbox();
                    }
                    
                    // Auto scroll to bottom
                    const nw = document.getElementById('noteWrap');
                    if (nw) nw.scrollTop = nw.scrollHeight;

                    // Activities
                    const actData = data.activities.data;
                    const actMeta = data.activities;
                    
                    activityCurrentPage = actMeta.current_page;
                    
                    renderActivities(actData, false);
                    renderActivityPagination(actMeta);

                }
            },
            error: function() {
                if(typeof SCA !== 'undefined') SCA.error('Gagal', 'Gagal memuat data detail project', true);
            }
        });
    };

    function renderActivities(activities, append = false) {
        let activityHtml = '';
        activities.forEach(act => {
            activityHtml += `
                <div class="tl-item">
                    <div class="tl-dot-wrap">
                        <div class="tl-dot ${act.color || 'primary'}"><i class="bi ${act.icon || 'bi-activity'}"></i></div>
                        <div class="tl-line"></div>
                    </div>
                    <div class="tl-content">
                        <div class="tl-title">${act.description}</div>
                        <div class="tl-desc">${act.properties && act.properties.old ? `Perubahan data terdeteksi pada sistem.` : 'Aktivitas berhasil dicatat.'}</div>
                        <div class="tl-user">
                            <div class="tl-av" ${act.causer.display_avatar ? `style="background-image:url('${act.causer.display_avatar}'); background-size:cover;"` : ''}>
                                ${act.causer.display_avatar ? '' : act.causer.initials}
                            </div> 
                            ${act.causer ? act.causer.name : 'System'}
                        </div>
                        <div class="tl-time">${act.created_at}</div>
                    </div>
                </div>
            `;
        });

        if (activityHtml === '' && !append) {
            activityHtml = '<div class="text-center py-4 text-muted">Belum ada aktivitas.</div>';
        }

        if (append) {
            $('#activityTimeline').append(activityHtml);
        } else {
            $('#activityTimeline').html(activityHtml);
        }
    }

    function renderActivityPagination(meta) {
        const wrap = $('#activityPaginationWrap');
        if (meta.last_page <= 1) {
            wrap.hide().html('');
            return;
        }

        wrap.show().html('');
        
        // Prev button
        wrap.append(`<button class="pg-btn btn-act-pg" data-page="${meta.current_page - 1}" ${meta.current_page === 1 ? 'disabled' : ''}><i class="bi bi-chevron-left"></i></button>`);

        // Smart Pagination Logic
        const lastPage = meta.last_page;
        const current = meta.current_page;
        const range = 1; 
        
        for (let i = 1; i <= lastPage; i++) {
            if (i === 1 || i === lastPage || (i >= current - range && i <= current + range)) {
                wrap.append(`<button class="pg-btn btn-act-pg ${i === current ? 'active' : ''}" data-page="${i}">${i}</button>`);
            } else if (i === 2 && current - range > 2) {
                wrap.append(`<span style="color:var(--muted); padding:0 4px; font-size:10px; align-self:center">...</span>`);
                i = current - range - 1; // Jump to before range
            } else if (i === current + range + 1 && current + range < lastPage - 1) {
                wrap.append(`<span style="color:var(--muted); padding:0 4px; font-size:10px; align-self:center">...</span>`);
                i = lastPage - 1; // Jump to last page
            }
        }

        // Next button
        wrap.append(`<button class="pg-btn btn-act-pg" data-page="${meta.current_page + 1}" ${meta.current_page === meta.last_page ? 'disabled' : ''}><i class="bi bi-chevron-right"></i></button>`);
    }

    $(document).on('click', '.btn-act-pg', function() {
        const page = $(this).data('page');
        if (!page || $(this).prop('disabled') || $(this).hasClass('active')) return;

        $('#activityTimeline').css('opacity', '0.5');

        $.ajax({
            url: `/projects/${projectId}/activities`,
            method: 'GET',
            data: {
                page: page,
                per_page: 5
            },
            success: function(res) {
                if (res.success) {
                    const actData = res.data.data;
                    const actMeta = res.data;
                    
                    activityCurrentPage = actMeta.current_page;
                    
                    renderActivities(actData, false); // Overwrite when page changes
                    renderActivityPagination(actMeta);
                    
                    // Scroll to top of timeline if needed
                    const actTab = $('#tab-aktivitas');
                    if (actTab.length) {
                        $('html, body').animate({
                            scrollTop: actTab.offset().top - 100
                        }, 300);
                    }
                }
            },
            complete: function() {
                $('#activityTimeline').css('opacity', '1');
            }
        });
    });

    /* Document Detail Modal Loader — Synced with Dokumen Module logic */
    $(document).on('click', '.btn-view-doc', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const url = `/dokumen/${id}`;
        
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

                    // 3. Show Modal with Multi-Layered Fallback
                    const modalEl = document.getElementById('modalDocDetail');
                    if (!modalEl) {
                        console.error("Modal element #modalDocDetail not found!");
                        if (typeof SCA !== 'undefined') SCA.toast({ type: 'danger', title: 'DOM Error', message: 'Elemen modal tidak ditemukan di halaman.' });
                        return;
                    }

                    try {
                        // First attempt: Standard Bootstrap 5 Native JS
                        const myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                        myModal.show();
                    } catch (e) {
                        console.warn("Bootstrap Native failed, trying jQuery fallback:", e);
                        try {
                            // Second attempt: jQuery Plugin
                            $(modalEl).modal('show');
                        } catch (e2) {
                            console.error("All Bootstrap methods failed, forcing CSS show:", e2);
                            // Final fallback: Forced CSS
                            $(modalEl).addClass('show').css({
                                'display': 'block',
                                'background': 'rgba(0,0,0,0.5)',
                                'z-index': '9999'
                            });
                            $('body').addClass('modal-open');
                        }
                    }
                }
            },
            error: function() {
                if (typeof showLoading === 'function') showLoading(false);
                SCA.toast({ type: 'danger', title: 'Error', message: 'Gagal memuat data.' });
            }
        });
    });

    /* Helper Functions for Document Icons & Info */
    function getFileIconClass(ext) {
        const map = { pdf: 'pdf', xls: 'xls', xlsx: 'xls', doc: 'doc', docx: 'doc', zip: 'zip' };
        return map[ext.toLowerCase()] || 'doc';
    }

    function getFileIcon(ext) {
        const map = { 
            pdf: 'bi bi-file-earmark-pdf-fill', 
            xls: 'bi bi-file-earmark-spreadsheet-fill', 
            xlsx: 'bi bi-file-earmark-spreadsheet-fill', 
            doc: 'bi bi-file-earmark-word-fill', 
            docx: 'bi bi-file-earmark-word-fill', 
            zip: 'bi bi-file-zip-fill'
        };
        return map[ext.toLowerCase()] || 'bi bi-file-earmark-fill';
    }

    function getInitials(name) {
        if (!name) return '??';
        return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    }

    /* Initial Load */
    window.loadDetailData();

    /* Search Doc Local Filter */
    $(document).on('input', '#docSearch', function() {
        var q = $(this).val().toLowerCase();
        $('#docTbl tbody tr').each(function() {
            var nm = $(this).find('.doc-nm').text().toLowerCase();
            $(this).toggle(!q || nm.indexOf(q) > -1);
        });
    });



    /* Modal Background Animation Helper */
    function initDrain(mid, fid) {
        var m = document.getElementById(mid);
        if (!m) return;
        m.addEventListener('show.bs.modal', function() {
            var f = document.getElementById(fid);
            if (f) { f.classList.remove('go'); void f.offsetWidth; f.classList.add('go'); }
        });
    }

    /* Delete Document Logic */
    let docDeleteId = null;
    $(document).on('click', '.btn-del-doc', function() {
        docDeleteId = $(this).data('id');
        $('#delDocTitle').text($(this).data('nm') || 'ini');
    });

    $(document).on('click', '.btn-mdel-doc', function() {
        if (!docDeleteId) return;
        const btn = $(this);
        const originalHtml = btn.html();
        
        btn.prop('disabled', true).html('<span><i class="bi bi-hourglass-split"></i> Menghapus...</span>');

        if (typeof SCA !== 'undefined') {
            SCA.loading({
                title: "Menghapus...",
                message: "Mohon tunggu sebentar"
            });
        }

        $.ajax({
            url: `/dokumen/${docDeleteId}`,
            method: 'DELETE',
            success: function(res) {
                btn.prop('disabled', false).html(originalHtml);
                $('#delDocModal').modal('hide');
                
                if (typeof SCA !== 'undefined') {
                    SCA.toast({
                        type: res.success ? "success" : "danger",
                        title: res.success ? "Berhasil!" : "Gagal!",
                        message: res.message ?? "Dokumen berhasil dihapus.",
                    });
                }

                if (res.success) {
                    window.loadDetailData();
                }
            },
            error: function() {
                btn.prop('disabled', false).html(originalHtml);
                if (typeof SCA !== 'undefined') {
                    SCA.toast({ type: "danger", title: "Error!", message: "Terjadi kesalahan sistem saat menghapus data." });
                }
            },
            complete: function() {
                if (typeof SCA !== 'undefined') SCA.close();
            }
        });
    });

    initDrain('uploadModal','drainFill');
    initDrain('deleteModal','drainFill2');
    initDrain('delDocModal','drainDelDoc');

    /* Attachment logic with Preview */
    let selectedFiles = [];

    $(document).on('change', '#chatFileInput', function() {
        const files = Array.from(this.files);
        if (files.length > 0) {
            selectedFiles = [...selectedFiles, ...files];
            renderPreviews();
            $(this).val(''); // Reset input to allow re-selecting same file
        }
    });

    function renderPreviews() {
        const wrap = $('#chatPreviewWrap');
        if (selectedFiles.length === 0) {
            wrap.hide().html('');
            return;
        }

        wrap.show().html('');
        selectedFiles.forEach((file, index) => {
            const isImage = file.type.startsWith('image/');
            const reader = new FileReader();

            const item = $(`
                <div class="preview-item" data-index="${index}">
                    <button type="button" class="btn-remove-preview" data-index="${index}">&times;</button>
                    ${isImage ? `<img src="" alt="preview">` : `<i class="bi bi-file-earmark-text preview-file-icon"></i>`}
                    <div class="preview-file-name">${file.name}</div>
                </div>
            `);

            if (isImage) {
                reader.onload = function(e) {
                    item.find('img').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }

            wrap.append(item);
        });
    }

    $(document).on('click', '.btn-remove-preview', function() {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);
        renderPreviews();
    });

    /* Reply & Edit Logic */
    let replyId = null;
    let editId = null;

    $(document).on('click', '.btn-reply-note', function() {
        cancelEdit(); // Cancel edit if replying
        const card = $(this).closest('.note-card');
        replyId = $(this).data('id');
        const author = card.find('.note-author').text();
        let text = card.find('.note-text').text() || 'Lampiran file';
        
        $('#replyAuthor').text(author);
        $('#replyText').text(text);
        $('#chatReplyWrap').show();
        $('#noteInput').focus();
    });

    $(document).on('click', '#btnCancelReply', function() {
        replyId = null;
        $('#chatReplyWrap').hide();
    });

    $(document).on('click', '.btn-edit-note', function() {
        cancelReply(); // Cancel reply if editing
        const card = $(this).closest('.note-card');
        editId = $(this).data('id');
        const content = card.find('.note-text').text();
        
        $('#editText').text(content);
        $('#noteInput').val(content).focus();
        $('#chatEditWrap').show();
        
        // Trigger resize
        $('#noteInput').trigger('input');
    });

    $(document).on('click', '#btnCancelEdit', function() {
        cancelEdit();
    });

    function cancelReply() {
        replyId = null;
        $('#chatReplyWrap').hide();
    }

    function cancelEdit() {
        editId = null;
        $('#chatEditWrap').hide();
        $('#noteInput').val('');
        $('#noteInput').trigger('input');
    }

    /* Store/Update Discussion Logic with FormData (Supports Files, Reply & Edit) */
    $(document).on('click', '#btnAddNote', function() {
        const noteInput = $('#noteInput');
        const content = noteInput.val().trim();
        const projectId = $('#projectContainer').data('id');

        if (!content && selectedFiles.length === 0) {
            if (typeof SCA !== 'undefined') SCA.toast({ type: "warn", title: "Peringatan", message: "Komentar atau lampiran tidak boleh kosong!" });
            return;
        }

        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

        let url = `/projects/${projectId}/diskusi`;
        let method = 'POST';

        if (editId) {
            url = `/projects/diskusi/${editId}`;
            method = 'PUT';
        }

        const payload = editId ? JSON.stringify({ content }) : null;
        const formData = editId ? null : new FormData();
        
        if (!editId) {
            formData.append('content', content);
            if (replyId) formData.append('parent_id', replyId);
            selectedFiles.forEach((file, i) => {
                formData.append(`files[${i}]`, file);
            });
        }

        $.ajax({
            url: url,
            method: method,
            data: editId ? payload : formData,
            processData: editId ? true : false,
            contentType: editId ? 'application/json' : false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success) {
                    noteInput.val('');
                    selectedFiles = [];
                    replyId = null;
                    editId = null;
                    renderPreviews();
                    $('#chatReplyWrap').hide();
                    $('#chatEditWrap').hide();
                    
                    if (typeof SCA !== 'undefined') {
                        SCA.toast({ type: "success", title: "Berhasil!", message: editId ? "Komentar diperbarui." : "Komentar terkirim." });
                    }
                    window.loadDetailData();
                    $('#noteInput').css('height', 'auto');
                    setTimeout(() => {
                        const nw = document.getElementById('noteWrap');
                        if (nw) nw.scrollTop = nw.scrollHeight;
                    }, 200);
                } else {
                    if (typeof SCA !== 'undefined') {
                        SCA.toast({ type: "danger", title: "Gagal!", message: res.message });
                    }
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html(originalHtml);
                let msg = "Terjadi kesalahan saat memproses komentar.";
                if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                if (typeof SCA !== 'undefined') {
                    SCA.toast({ type: "danger", title: "Error!", message: msg });
                }
            }
        });
    });

    $(document).on('click', '.note-quote', function() {
        const target = $(this).data('target');
        const element = $(target);
        
        if (element.length) {
            const container = $('#noteWrap');
            const scrollPos = element.position().top + container.scrollTop() - (container.height() / 2) + (element.height() / 2);
            
            container.animate({
                scrollTop: scrollPos
            }, 500);
            
            // Highlight effect
            element.addClass('highlight-note');
            setTimeout(() => {
                element.removeClass('highlight-note');
            }, 2000);
        } else {
            if (typeof SCA !== 'undefined') SCA.toast({ type: "info", title: "Info", message: "Pesan asli sudah terlalu lama atau sudah dihapus." });
        }
    });

    /* Auto resize textarea like WhatsApp */
    $(document).on('input', '#noteInput', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    /* Delete Discussion Logic */
    $(document).on('click', '.btn-del-note', function() {
        const id = $(this).data('id');
        
        if (typeof SCA !== 'undefined') {
            SCA.confirm(
                "Hapus Komentar?",
                "Komentar yang dihapus tidak dapat dikembalikan."
            ).then(function (isConfirmed) {
                if (isConfirmed) {
                    SCA.loading({
                        title: "Menghapus...",
                        message: "Mohon tunggu sebentar"
                    });

                    const btn = $(`.btn-del-note[data-id="${id}"]`);
                    btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');

                    $.ajax({
                        url: `/projects/diskusi/${id}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.success) {
                                SCA.toast({ type: "success", title: "Berhasil!", message: "Komentar dihapus." });
                                window.loadDetailData();
                            } else {
                                btn.prop('disabled', false).html('<i class="bi bi-trash3"></i> Hapus');
                                SCA.toast({ type: "danger", title: "Gagal!", message: res.message });
                            }
                        },
                        error: function(err) {
                            btn.prop('disabled', false).html('<i class="bi bi-trash3"></i> Hapus');
                            let msg = "Terjadi kesalahan saat menghapus komentar.";
                            if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                            SCA.toast({ type: "danger", title: "Error!", message: msg });
                        },
                        complete: function() {
                            SCA.close();
                        }
                    });
                }
            });
        } else {
            // Fallback for dev if SCA is missing
            if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
                // ... same logic
            }
        }
    });

    /* Attachment trigger */
    $(document).on('click', '#btnAttach', function() {
        $('#chatFileInput').click();
    });

    /* Picmo Emoji Picker Implementation */
    let picker = null;
    const emojiBtn = document.querySelector('#btnEmoji');
    const inputArea = document.querySelector('#noteInput');

    if (emojiBtn && typeof picmoPopup !== 'undefined') {
        picker = picmoPopup.createPopup({
            theme: 'dark',
            showSearch: true,
            showVariants: true,
            className: 'premium-emoji-picker',
        }, {
            referenceElement: emojiBtn,
            triggerElement: emojiBtn,
            position: 'top-end',
            hideOnEmojiSelect: false // Agar picker tidak langsung tertutup saat pilih emoji
        });

        picker.addEventListener('emoji:select', (selection) => {
            const start = inputArea.selectionStart;
            const end = inputArea.selectionEnd;
            const text = inputArea.value;
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);
            
            inputArea.value = before + selection.emoji + after;
            inputArea.selectionStart = inputArea.selectionEnd = start + selection.emoji.length;
            inputArea.focus();
            
            // Trigger auto-resize
            $(inputArea).trigger('input');
        });
    }

    /* --- Upload Document Logic --- */
    const $upFile = $('#upFileInput');
    const $dropZone = $('#dropZone');
    let upFileObj = null;

    $dropZone.on('click', () => $upFile.click());

    $upFile.on('change', function() {
        const file = this.files[0];
        if (file) handleFileSelection(file);
    });

    $dropZone.on('dragover', (e) => {
        e.preventDefault();
        $dropZone.css('border-color', 'var(--cyan)').css('background', 'rgba(0,200,255,0.06)');
    });

    $dropZone.on('dragleave', (e) => {
        e.preventDefault();
        $dropZone.css('border-color', 'rgba(0,200,255,0.2)').css('background', 'rgba(0,200,255,0.02)');
    });

    $dropZone.on('drop', (e) => {
        e.preventDefault();
        $dropZone.css('border-color', 'rgba(0,200,255,0.2)').css('background', 'rgba(0,200,255,0.02)');
        const file = e.originalEvent.dataTransfer.files[0];
        if (file) handleFileSelection(file);
    });

    function handleFileSelection(file) {
        upFileObj = file;
        $dropZone.find('div:first').next().text(file.name);
        $dropZone.find('div:first').next().next().text(`${(file.size / 1024 / 1024).toFixed(2)} MB`);
        
        // Auto-fill Nama if empty
        if (!$('#upNama').val()) {
            const nameWithoutExt = file.name.split('.').slice(0, -1).join('.');
            $('#upNama').val(nameWithoutExt);
        }
    }

    $(document).on('click', '#btnConfirmUpload', function() {
        if (!upFileObj) {
            if (typeof SCA !== 'undefined') SCA.toast({ type: 'warn', title: 'Peringatan', message: 'Silakan pilih file terlebih dahulu.' });
            return;
        }

        const nama = $('#upNama').val().trim();
        const kategori = $('#upKategori').val();
        if (!nama) {
            if (typeof SCA !== 'undefined') SCA.toast({ type: 'warn', title: 'Peringatan', message: 'Nama dokumen wajib diisi.' });
            return;
        }

        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span><i class="bi bi-hourglass-split"></i> Uploading...</span>');

        const formData = new FormData();
        formData.append('file', upFileObj);
        formData.append('nama', nama);
        formData.append('versi', $('#upVersi').val() || '1.0');
        formData.append('kategori', kategori);
        formData.append('keterangan', $('#upKeterangan').val());
        formData.append('project_id', $('#projectContainer').data('id'));
        formData.append('type', 'file');

        $.ajax({
            url: '/dokumen',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                btn.prop('disabled', false).html(originalHtml);
                if (res.success) {
                    $('#uploadModal').modal('hide');
                    if (typeof SCA !== 'undefined') SCA.toast({ type: 'success', title: 'Berhasil', message: 'Dokumen berhasil diupload.' });
                    
                    // Reset form
                    upFileObj = null;
                    $('#upFileInput').val('');
                    $('#upNama').val('');
                    $('#upVersi').val('');
                    $('#upKategori').val('l');
                    $('#upKeterangan').val('');
                    $dropZone.find('div:first').next().text('Drag & drop file di sini');
                    $dropZone.find('div:first').next().next().text('atau klik untuk pilih file');

                    window.loadDetailData();
                } else {
                    if (typeof SCA !== 'undefined') SCA.toast({ type: 'danger', title: 'Gagal', message: res.message });
                }
            },
            error: function(err) {
                btn.prop('disabled', false).html(originalHtml);
                let msg = 'Terjadi kesalahan sistem.';
                if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                if (typeof SCA !== 'undefined') SCA.toast({ type: 'danger', title: 'Error', message: msg });
            }
        });
    });
});
