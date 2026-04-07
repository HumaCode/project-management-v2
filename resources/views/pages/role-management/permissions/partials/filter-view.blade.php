<div class="tbar" data-aos="fade-up" data-aos-delay="60">
    <div class="tbar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari data..." oninput="debounceReload()" />
    </div>

    <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
        <button class="btn-reset" title="Reset Filter" onclick="resetFilter()">
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
