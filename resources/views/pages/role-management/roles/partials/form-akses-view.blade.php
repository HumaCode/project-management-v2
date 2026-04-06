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

                    {{-- TAMBAHAN HEADER UNTUK SHOW / DETAIL --}}
                    <th class="ph-i"><i class="bi bi-file-text-fill"></i><span class="ph-lbl">Show</span></th>

                    <th class="ph-g"><i class="bi bi-plus-circle-fill"></i><span class="ph-lbl">Create</span></th>
                    <th class="ph-a"><i class="bi bi-pencil-fill"></i><span class="ph-lbl">Update</span></th>
                    <th class="ph-r"><i class="bi bi-trash3-fill"></i><span class="ph-lbl">Delete</span></th>
                    <th class="ph-p"><i class="bi bi-layout-sidebar"></i><span class="ph-lbl">Menu</span></th>
                    <th class="th-all">Semua</th>
                </tr>
            </thead>

            <tbody>

                @php
                    // 1. Ambil semua permission yang dimiliki ROLE ini (untuk status Checked)
                    $rolePermissions = $data->permissions->pluck('name')->toArray();

                    // 2. Ambil SEMUA permission yang ada di SYSTEM (untuk mengecek apakah permission itu tersedia)
                    $allSystemPermissions = \Spatie\Permission\Models\Permission::pluck('name')->toArray();
                @endphp

                @foreach (menus(true) as $category => $menuItems)
                    @php
                        $grpSlug = Str::slug($category, '_');
                    @endphp

                    {{-- Kategori Menu --}}
                    <tr class="grp-row grp-c">
                        <td colspan="8">
                            <div class="grp-label">
                                <i class="bi bi-database-fill grp-ico grp-ico-c"></i><span>{{ $category }}</span>
                                <span class="grp-count">{{ $menuItems->count() }} menu</span>
                                <button class="grp-toggle" data-grp="{{ $grpSlug }}" type="button">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Daftar Menu per Kategori --}}
                    @foreach ($menuItems as $menu)
                        @php
                            $permKey = $menu->permission ?? Str::slug($menu->name, '_');

                            // UBAH 'detail' MENJADI 'show'
                            $canRead = in_array("read {$permKey}", $rolePermissions);
                            $canShow = in_array("show {$permKey}", $rolePermissions); // <-- Diubah
                            $canCreate = in_array("create {$permKey}", $rolePermissions);
                            $canUpdate = in_array("update {$permKey}", $rolePermissions);
                            $canDelete = in_array("delete {$permKey}", $rolePermissions);
                            $canMenu = in_array("menu {$permKey}", $rolePermissions);

                            // UBAH 'detail' MENJADI 'show'
                            $isShowAvailable = in_array("show {$permKey}", $allSystemPermissions); // <-- Diubah
                        @endphp

                        <tr class="item-row" data-grp="{{ $grpSlug }}">
                            <td class="td-menu"><span class="menu-dot"></span>{{ $menu->name }}</td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-c" title="Read">
                                    <input type="checkbox" name="permissions[]" value="read {{ $permKey }}"
                                        id="sw_{{ $permKey }}_read" {{ $canRead ? 'checked' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- KOLOM SHOW (DETAIL) DENGAN LOGIKA DISABLED --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-c" title="Show / Detail"
                                    style="{{ !$isShowAvailable ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    {{-- Pastikan value-nya adalah "show" --}}
                                    <input type="checkbox" name="permissions[]" value="show {{ $permKey }}"
                                        id="sw_{{ $permKey }}_show" {{ $canShow ? 'checked' : '' }}
                                        {{ !$isShowAvailable ? 'disabled' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-g" title="Create">
                                    <input type="checkbox" name="permissions[]" value="create {{ $permKey }}"
                                        id="sw_{{ $permKey }}_create" {{ $canCreate ? 'checked' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-a" title="Update">
                                    <input type="checkbox" name="permissions[]" value="update {{ $permKey }}"
                                        id="sw_{{ $permKey }}_update" {{ $canUpdate ? 'checked' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-r" title="Delete">
                                    <input type="checkbox" name="permissions[]" value="delete {{ $permKey }}"
                                        id="sw_{{ $permKey }}_delete" {{ $canDelete ? 'checked' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-p" title="Menu">
                                    <input type="checkbox" name="permissions[]" value="menu {{ $permKey }}"
                                        id="sw_{{ $permKey }}_menu" {{ $canMenu ? 'checked' : '' }}>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            <td class="sw-cell">
                                <label class="sw-wrap sw-all" title="Toggle semua">
                                    <input type="checkbox" id="all_{{ $permKey }}" class="sw-all-cb"
                                        data-target="{{ $permKey }}">
                                    <span class="sw-track"></span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>
    </div>
</div>
