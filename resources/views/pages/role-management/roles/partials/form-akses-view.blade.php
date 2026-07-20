<div class="perm-pane active" id="pane_admin">
    <div class="role-desc-row">
        <span class="rdr-badge rd-admin">{{ $data->name }}</span>
        <span class="rdr-desc"><i class="bi bi-info-circle"
                style="margin-right:4px;opacity:.5"></i>{{ $data->description }}</span>
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

                    {{-- TAMBAHAN UNTUK ACTIVATE --}}
                    <th class="ph-v"><i class="bi bi-patch-check-fill"></i><span class="ph-lbl">Activate</span></th>

                    <th class="ph-p"><i class="bi bi-layout-sidebar"></i><span class="ph-lbl">Menu</span></th>
                    <th class="th-all">Semua</th>
                </tr>
            </thead>

            <tbody>

                @php
                    // 1. Ambil SEMUA permission dari database untuk mengecek ketersediaan fitur
                    $allSystemPermissions = \Spatie\Permission\Models\Permission::pluck('name')->toArray();

                    // 2. Deteksi apakah ini role dev
                    $isDev = strtolower($data->name) === 'dev';
                @endphp

                @foreach (menus(true) as $category => $menuItems)
                    @php
                        $grpSlug = Str::slug($category, '_');
                    @endphp

                    {{-- Kategori Menu --}}
                    <tr class="grp-row grp-c">
                        <td colspan="9">
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
                            // Pastikan ini mengambil dari field URL (sesuai Trait HasMenuPermission)
                            // Hapus spasinya jika ada spasi yang tidak sengaja terinput di database
                            $permKey = strtolower(trim($menu->url));

                            // Format string permission
                            $pRead = "read {$permKey}";
                            $pShow = "show {$permKey}";
                            $pCreate = "create {$permKey}";
                            $pUpdate = "update {$permKey}";
                            $pDelete = "delete {$permKey}";
                            $pActivate = "activate {$permKey}";
                            $pMenu = "menu {$permKey}";

                            // Cek ketersediaan permission di database (harus terdaftar di tabel permissions)
                            $isReadAvail = in_array($pRead, $allSystemPermissions);
                            $isShowAvail = in_array($pShow, $allSystemPermissions);
                            $isCreateAvail = in_array($pCreate, $allSystemPermissions);
                            $isUpdateAvail = in_array($pUpdate, $allSystemPermissions);
                            $isDeleteAvail = in_array($pDelete, $allSystemPermissions);
                            $isActivateAvail = in_array($pActivate, $allSystemPermissions);
                            $isMenuAvail = in_array($pMenu, $allSystemPermissions);
                        @endphp

                        <tr class="item-row" data-grp="{{ $grpSlug }}">
                            <td class="td-menu"><span class="menu-dot"></span>{{ $menu->name }}</td>

                            {{-- READ --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-c" title="Read"
                                    style="{{ !$isReadAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pRead }}"
                                        id="sw_{{ $permKey }}_read" @checked($isReadAvail && ($isDev || $data->hasPermissionTo($pRead)))
                                        @disabled(!$isReadAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- SHOW --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-c" title="Show / Detail"
                                    style="{{ !$isShowAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pShow }}"
                                        id="sw_{{ $permKey }}_show" @checked($isShowAvail && ($isDev || $data->hasPermissionTo($pShow)))
                                        @disabled(!$isShowAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- CREATE --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-g" title="Create"
                                    style="{{ !$isCreateAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pCreate }}"
                                        id="sw_{{ $permKey }}_create" @checked($isCreateAvail && ($isDev || $data->hasPermissionTo($pCreate)))
                                        @disabled(!$isCreateAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- UPDATE --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-a" title="Update"
                                    style="{{ !$isUpdateAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pUpdate }}"
                                        id="sw_{{ $permKey }}_update" @checked($isUpdateAvail && ($isDev || $data->hasPermissionTo($pUpdate)))
                                        @disabled(!$isUpdateAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- DELETE --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-r" title="Delete"
                                    style="{{ !$isDeleteAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pDelete }}"
                                        id="sw_{{ $permKey }}_delete" @checked($isDeleteAvail && ($isDev || $data->hasPermissionTo($pDelete)))
                                        @disabled(!$isDeleteAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- ACTIVATE --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-v" title="Activate"
                                    style="{{ !$isActivateAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pActivate }}"
                                        id="sw_{{ $permKey }}_activate" @checked($isActivateAvail && ($isDev || $data->hasPermissionTo($pActivate)))
                                        @disabled(!$isActivateAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- MENU --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-p" title="Menu"
                                    style="{{ !$isMenuAvail ? 'opacity: 0.3; cursor: not-allowed;' : '' }}">
                                    <input type="checkbox" name="permissions[]" value="{{ $pMenu }}"
                                        id="sw_{{ $permKey }}_menu" @checked($isMenuAvail && ($isDev || $data->hasPermissionTo($pMenu)))
                                        @disabled(!$isMenuAvail)>
                                    <span class="sw-track"></span>
                                </label>
                            </td>

                            {{-- TOGGLE SEMUA --}}
                            <td class="sw-cell">
                                <label class="sw-wrap sw-all" title="Toggle semua">
                                    {{-- Jika Dev, langsung centang tombol 'Semua'-nya --}}
                                    <input type="checkbox" id="all_{{ $permKey }}" class="sw-all-cb"
                                        data-target="{{ $permKey }}" @checked($isDev)>
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
