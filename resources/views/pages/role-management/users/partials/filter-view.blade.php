<div class="tbar" data-aos="fade-up" data-aos-delay="60">
    <div class="tbar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari data..." oninput="debounceReload()" />
    </div>

    <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
        <div style="flex: 1; min-width: 0;">
            <select class="nsel" style="min-width:128px" id="filterStatusAkun" onchange="applyFilter()">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 0;">
            <select class="nsel" style="min-width:128px" id="filterTypeRole" onchange="applyFilter()">
                <option value="all">Semua Type Role</option>

                @foreach ($rolesActive as $item)
                    <option value="{{ $item->name }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn-reset" style="margin-left: 10px;" title="Reset Filter" onclick="resetFilter()">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
    </div>
    @can('create ' . $permissionAkses)
        <div class="tbar-right">
            <a href="{{ $createUrl }}" class="btn-add action">
                <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
            </a>
        </div>
    @endcan
</div>
