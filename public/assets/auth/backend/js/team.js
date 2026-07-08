/**
 * Team Management Module
 * File: public/assets/auth/backend/js/team.js
 */

(function ($) {
    "use strict";

    let isLoading = false;
    let editId = null;

    const avatarColors = [
        '#00c8ff', // cyan
        '#4e54ff', // blue
        '#8a4fff', // purple
        '#ff4d6d', // pink
        '#ff8a00', // orange
        '#00d1b2', // teal
        '#485ed9'  // indigo
    ];

    function getAvatarColor(id) {
        if (!id) return avatarColors[0];
        let hash = 0;
        for (let i = 0; i < id.length; i++) {
            hash = id.charCodeAt(i) + ((hash << 5) - hash);
        }
        return avatarColors[Math.abs(hash) % avatarColors.length];
    }

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

                const data = res.data.data;
                const meta = res.data.meta || res.data;

                if (!data || data.length === 0) {
                    $tbody.empty();
                    $empty.removeClass('d-none');
                } else {
                    $empty.addClass('d-none');
                    renderTable(data, meta);
                }

                window.renderInfo(meta);
                window.renderPagination(meta);

                // Update Stats Cards
                if (res.data.stats) {
                    $('#totalTeams').text(res.data.stats.total_teams);
                    $('#totalMembers').text(res.data.stats.total_members);
                }
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
            // Render members avatars
            let avatarsHtml = '<div class="av-stack">';
            if (row.members && row.members.length > 0) {
                row.members.slice(0, 4).forEach(m => {
                    const bgColor = getAvatarColor(m.id);
                    const content = m.avatar ? `<img src="${m.avatar}" style="width:100%; height:100%; border-radius:50%; object-fit:cover">` : m.initials;
                    avatarsHtml += `<div class="av-item" title="${m.name}" style="background:${bgColor}">${content}</div>`;
                });
                if (row.members.length > 4) {
                    avatarsHtml += `<div class="av-item more" title="Lainnya" style="background:rgba(255,255,255,0.05); color:var(--cyan)">+${row.members.length - 4}</div>`;
                }
            } else {
                avatarsHtml += '<span class="text-muted opacity-50" style="font-size:11px">Belum ada anggota</span>';
            }
            avatarsHtml += '</div>';

            html += `
                <tr>
                    <td class="td-no" style="text-align:center">${String(no++).padStart(2, '0')}</td>
                    <td>
                        <div style="font-weight:600; color:var(--cyan); font-size:14px">${row.name}</div>
                        <div style="font-size:10px; opacity:0.6; margin-top:2px; font-family:var(--mono)"><i class="bi bi-people-fill"></i> ${row.members ? row.members.length : 0} ANGGOTA</div>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width:300px; font-size:12.5px; color:var(--dim)" title="${row.description || '-'}">
                            ${row.description || '<span class="opacity-50 italic">Tidak ada deskripsi</span>'}
                        </div>
                    </td>
                    <td>${avatarsHtml}</td>
                    <td>
                        <div style="font-size:13px; font-weight:500">${row.creator ? row.creator.name : 'System'}</div>
                    </td>
                    <td class="td-dt" style="font-family:var(--mono); font-size:12px">${new Date(row.created_at).toLocaleDateString('id-ID')}</td>
                    <td>
                        <div class="act-row" style="display:flex; justify-content:center; gap:8px">
                            <button type="button" onclick="showDetail('${row.id}')" class="ibtn ib-v" title="Detail"><i class="bi bi-eye"></i></button>
                            <button type="button" onclick="editTeam('${row.id}')" class="ibtn ib-e" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button type="button" onclick="deleteTeam('${row.id}', '${row.name}')" class="ibtn ib-x" title="Hapus"><i class="bi bi-trash3"></i></button>
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
       TEAM ACTIONS (ADD, EDIT, DELETE)
    ============================================================ */
    window.loadUsers = function (selectedMembers = []) {
        // selectedMembers can be an array of IDs or an array of objects with {id, team_role}
        const selectedIds = selectedMembers.map(m => typeof m === 'object' ? m.id : m);
        const rolesMap = {};
        selectedMembers.forEach(m => {
            if (typeof m === 'object') rolesMap[m.id] = m.team_role;
        });

        const $wrap = $('#userSelectionWrap');
        $wrap.html(`
            <div class="col-12 text-center py-4">
                <div style="display:inline-flex; flex-direction:column; align-items:center; gap:12px">
                    <div class="spinner-custom"></div>
                    <div style="font-size: 13px; color: var(--txt); font-weight: 500; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-people-fill text-cyan" style="animation: pulse 1.5s infinite"></i>
                        <span>Memuat anggota...</span>
                    </div>
                </div>
            </div>
        `);
        $('#memberSearchInput').val(''); // Reset input pencarian ketika memuat anggota

        $.ajax({
            url: window.urlUsers,
            method: 'GET',
            success(res) {
                if (res.success) {
                    // Sort: selected users on top, then sort alphabetically by name
                    res.data.sort((a, b) => {
                        const aChecked = selectedIds.includes(a.id);
                        const bChecked = selectedIds.includes(b.id);
                        if (aChecked && !bChecked) return -1;
                        if (!aChecked && bChecked) return 1;
                        return a.name.localeCompare(b.name);
                    });

                    let html = '';
                    res.data.forEach(user => {
                        const isChecked = selectedIds.includes(user.id) ? 'checked' : '';
                        const roleValue = rolesMap[user.id] || 'anggota';
                        const bgColor = getAvatarColor(user.id);
                        html += `
                            <div class="col-12 col-md-6 user-selection-item" data-name="${user.name.toLowerCase()}" data-role="${user.role_name.toLowerCase()}">
                                <div class="item-sel ${isChecked ? 'active' : ''}">
                                    <label style="display:flex; align-items:center; gap:10px; flex-grow:1; cursor:pointer; margin:0">
                                        <input type="checkbox" name="members[${user.id}][id]" value="${user.id}" ${isChecked} class="d-none user-check">
                                        <div class="member-avatar" style="background:${bgColor}">
                                            ${user.avatar ? `<img src="${user.avatar}">` : user.initials}
                                        </div>
                                        <div style="font-size:12px; flex-grow:1; color:var(--txt); line-height:1.2; min-width: 0;">
                                            <div style="font-weight:600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${user.name}</div>
                                            <div style="font-size:10px; opacity:0.6; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${user.role_name}</div>
                                        </div>
                                    </label>
                                    <div class="role-input-wrap" style="display:${isChecked ? 'block' : 'none'}; width:100px">
                                        <input type="text" name="members[${user.id}][role]" value="${roleValue}" class="fmi px-2" style="height:28px; font-size:11px" placeholder="Role (e.g. Backend)" ${isChecked ? '' : 'disabled'}>
                                    </div>
                                    <i class="bi bi-check-circle-fill check-ico" style="color:var(--cyan); font-size:14px; display:${isChecked ? 'block' : 'none'}"></i>
                                </div>
                            </div>
                        `;
                    });
                    $wrap.html(html || '<div class="col-12 text-center py-3">Tidak ada anggota tersedia</div>');
                }
            }
        });
    };

    $(document).on('change', '.user-check', function() {
        const isChecked = $(this).is(':checked');
        const $parent = $(this).closest('.item-sel');
        const $roleWrap = $parent.find('.role-input-wrap');
        const $roleInput = $roleWrap.find('input');
        const $checkIco = $parent.find('.check-ico');

        if (isChecked) {
            $parent.addClass('active');
            $roleWrap.fadeIn(200);
            $roleInput.prop('disabled', false);
            if (!$roleInput.val()) {
                $roleInput.val('anggota');
            }
            $checkIco.show();
        } else {
            $parent.removeClass('active');
            $roleWrap.hide();
            $roleInput.prop('disabled', true);
            $checkIco.hide();
        }
    });

    $(document).on('input', '#memberSearchInput', function () {
        const query = $(this).val().toLowerCase().trim();
        $('.user-selection-item').each(function () {
            const name = $(this).data('name') || '';
            const role = $(this).data('role') || '';
            if (name.includes(query) || role.includes(query)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#btnAdd').on('click', function () {
        editId = null;
        $('#modalTitle').text('Tambah Tim Baru');
        $('#teamForm')[0].reset();
        $('#modalForm').modal('show');
        window.loadUsers();
    });

    window.editTeam = function (id) {
        editId = id;
        $('#modalTitle').text('Edit Tim');
        
        SCA.loading({
            title: "Memuat Tim",
            message: "Sedang mengambil data tim..."
        });
        
        $.ajax({
            url: `${window.urlBase}/${id}/edit`,
            method: 'GET',
            success(res) {
                if (res.success) {
                    const team = res.data;
                    $('input[name="name"]').val(team.name);
                    $('textarea[name="description"]').val(team.description);
                    
                    const selectedMembers = team.members.map(m => ({
                        id: m.id,
                        team_role: m.team_role // From the TeamResource/UserResource pivot logic
                    }));
                    window.loadUsers(selectedMembers);
                    $('#modalForm').modal('show');
                }
            },
            complete() {
                SCA.close();
            }
        });
    };

    $('#teamForm').on('submit', function (e) {
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

    window.deleteTeam = function (id, name) {
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

    window.showDetail = function (id) {
        SCA.loading({
            title: "Detail Tim",
            message: "Sedang menyiapkan profil tim..."
        });
        
        $.ajax({
            url: `${window.urlBase}/${id}/edit`, // We can use the same edit endpoint to get full data
            method: 'GET',
            success(res) {
                if (res.success) {
                    const team = res.data;
                    $('#detName').text(team.name);
                    $('#detCreator span').text(team.creator ? team.creator.name : 'System');
                    $('#detDesc').text(team.description || 'Tidak ada deskripsi tim.');
                    $('#detCount').text(`${team.members ? team.members.length : 0} Orang`);

                    let membersHtml = '';
                    if (team.members && team.members.length > 0) {
                        team.members.forEach(m => {
                            const bgColor = getAvatarColor(m.id);
                            membersHtml += `
                                <div class="col-12 col-md-6">
                                    <div class="member-card">
                                        <div class="member-avatar" style="background:${bgColor}">
                                            ${m.avatar ? `<img src="${m.avatar}">` : m.initials}
                                        </div>
                                        <div class="member-info">
                                            <div class="member-name">${m.name}</div>
                                            <div class="member-role">${m.role_name}</div>
                                        </div>
                                        <div class="member-team-role">
                                            ${m.team_role || 'ANGGOTA'}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        membersHtml = '<div class="col-12 text-center text-muted small py-3 italic">Belum ada anggota di tim ini.</div>';
                    }
                    $('#detMemberList').html(membersHtml);
                    $('#modalDetail').modal('show');
                }
            },
            complete() {
                SCA.close();
            }
        });
    };

})(jQuery);
