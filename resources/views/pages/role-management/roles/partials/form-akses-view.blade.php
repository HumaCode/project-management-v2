<div class="perm-pane active" id="pane_admin">
    <div class="role-desc-row">
        <span class="rdr-badge rd-admin">Admin</span>
        <span class="rdr-desc"><i class="bi bi-info-circle" style="margin-right:4px;opacity:.5"></i>Akses penuh ke semua
            fitur</span>
        <span class="rdr-users"><i class="bi bi-people-fill" style="margin-right:4px"></i>1 pengguna terkait</span>
    </div>
    <div class="scroll-hint-perm">
        <i class="bi bi-arrow-right-short"
            style="font-size:16px;color:var(--cyan);animation:sh-arrow 1.4s ease-in-out infinite"></i>
        Geser ke kanan untuk melihat semua permission
    </div>

    <div class="perm-scroll">
        <table class="perm-table">
            <thead>
                <tr>
                    <th class="th-menu">Menu / Halaman</th>
                    {{-- permission --}}
                    <th class="ph-c"><i class="bi bi-eye-fill"></i><span class="ph-lbl">Read</span></th>
                    <th class="ph-g"><i class="bi bi-plus-circle-fill"></i><span class="ph-lbl">Create</span></th>
                    <th class="ph-a"><i class="bi bi-pencil-fill"></i><span class="ph-lbl">Update</span></th>
                    <th class="ph-r"><i class="bi bi-trash3-fill"></i><span class="ph-lbl">Delete</span></th>
                    <th class="ph-p"><i class="bi bi-layout-sidebar"></i><span class="ph-lbl">Menu</span></th>
                    <th class="th-all">Semua</th>
                </tr>
            </thead>
            <tbody>

                {{-- kategori menu --}}
                <tr class="grp-row grp-c">
                    <td colspan="7">
                        <div class="grp-label">
                            <i class="bi bi-database-fill grp-ico grp-ico-c"></i><span>Master Data</span>
                            <span class="grp-count">4 menu</span>
                            <button class="grp-toggle" data-grp="master_data">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- menu --}}
                <tr class="item-row" data-grp="master_data">
                    <td class="td-menu"><span class="menu-dot"></span>Proyek</td>
                    <td class="sw-cell"><label class="sw-wrap sw-c" title="Read"><input type="checkbox"
                                id="sw_admin_proyek_read" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-g" title="Create"><input type="checkbox"
                                id="sw_admin_proyek_create" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-a" title="Update"><input type="checkbox"
                                id="sw_admin_proyek_update" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-r" title="Delete"><input type="checkbox"
                                id="sw_admin_proyek_delete" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-p" title="Menu"><input type="checkbox"
                                id="sw_admin_proyek_menu" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell">
                        <label class="sw-wrap sw-all" title="Toggle semua">
                            <input type="checkbox" id="all_admin_proyek" class="sw-all-cb" data-target="admin_proyek">
                            <span class="sw-track"></span></label>
                    </td>
                </tr>


                <tr class="item-row" data-grp="master_data">
                    <td class="td-menu"><span class="menu-dot"></span>Manajemen Tim</td>
                    <td class="sw-cell"><label class="sw-wrap sw-c" title="Read"><input type="checkbox"
                                id="sw_admin_tim_read" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-g" title="Create"><input type="checkbox"
                                id="sw_admin_tim_create" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-a" title="Update"><input type="checkbox"
                                id="sw_admin_tim_update" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-r" title="Delete"><input type="checkbox"
                                id="sw_admin_tim_delete" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-p" title="Menu"><input type="checkbox"
                                id="sw_admin_tim_menu" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-all" title="Toggle semua"><input type="checkbox"
                                id="all_admin_tim" class="sw-all-cb" data-target="admin_tim"><span
                                class="sw-track"></span></label></td>
                </tr>
                <tr class="item-row" data-grp="master_data">
                    <td class="td-menu"><span class="menu-dot"></span>Dokumen</td>
                    <td class="sw-cell"><label class="sw-wrap sw-c" title="Read"><input type="checkbox"
                                id="sw_admin_dokumen_read" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-g" title="Create"><input type="checkbox"
                                id="sw_admin_dokumen_create" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-a" title="Update"><input type="checkbox"
                                id="sw_admin_dokumen_update" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-r" title="Delete"><input type="checkbox"
                                id="sw_admin_dokumen_delete" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-p" title="Menu"><input type="checkbox"
                                id="sw_admin_dokumen_menu" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-all" title="Toggle semua"><input type="checkbox"
                                id="all_admin_dokumen" class="sw-all-cb" data-target="admin_dokumen"><span
                                class="sw-track"></span></label></td>
                </tr>
                <tr class="item-row" data-grp="master_data">
                    <td class="td-menu"><span class="menu-dot"></span>Catatan / Notulen</td>
                    <td class="sw-cell"><label class="sw-wrap sw-c" title="Read"><input type="checkbox"
                                id="sw_admin_catatan_read" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-g" title="Create"><input type="checkbox"
                                id="sw_admin_catatan_create" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-a" title="Update"><input type="checkbox"
                                id="sw_admin_catatan_update" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-r" title="Delete"><input type="checkbox"
                                id="sw_admin_catatan_delete" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-p" title="Menu"><input type="checkbox"
                                id="sw_admin_catatan_menu" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-all" title="Toggle semua"><input type="checkbox"
                                id="all_admin_catatan" class="sw-all-cb" data-target="admin_catatan"><span
                                class="sw-track"></span></label></td>
                </tr>
                <tr class="grp-row grp-g">
                    <td colspan="7">
                        <div class="grp-label"><i
                                class="bi bi-file-earmark-bar-graph-fill grp-ico grp-ico-g"></i><span>Laporan</span><span
                                class="grp-count">3 menu</span><button class="grp-toggle" data-grp="laporan"><i
                                    class="bi bi-chevron-down"></i></button></div>
                    </td>
                </tr>
                <tr class="item-row" data-grp="laporan">
                    <td class="td-menu"><span class="menu-dot"></span>Laporan Proyek</td>
                    <td class="sw-cell"><label class="sw-wrap sw-c" title="Read"><input type="checkbox"
                                id="sw_admin_laporan_read" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-g" title="Create"><input type="checkbox"
                                id="sw_admin_laporan_create" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-a" title="Update"><input type="checkbox"
                                id="sw_admin_laporan_update" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-r" title="Delete"><input type="checkbox"
                                id="sw_admin_laporan_delete" checked=""><span class="sw-track"></span></label>
                    </td>
                    <td class="sw-cell"><label class="sw-wrap sw-p" title="Menu"><input type="checkbox"
                                id="sw_admin_laporan_menu" checked=""><span class="sw-track"></span></label></td>
                    <td class="sw-cell"><label class="sw-wrap sw-all" title="Toggle semua"><input type="checkbox"
                                id="all_admin_laporan" class="sw-all-cb" data-target="admin_laporan"><span
                                class="sw-track"></span></label></td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="save-bar">
        <div class="save-info"><i class="bi bi-shield-lock-fill"></i>Perubahan hanya berlaku setelah disimpan. Role:
            <strong style="color:var(--purple)">Admin</strong>
        </div>
        <div class="save-btns">
            <button class="btn-reset-modal"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
            <button class="btn-save"><span><i class="bi bi-floppy-fill"></i> Simpan Permission</span></button>
        </div>
    </div>
</div>
