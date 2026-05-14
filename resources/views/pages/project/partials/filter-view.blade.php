<div class="tbar" data-aos="fade-up" data-aos-delay="60">
    <div class="tbar-search">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" placeholder="Cari project / pembuat..." oninput="debounceReload()" />
    </div>

    <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
        <select class="nsel" style="min-width:140px" id="fStatus">
            <option value="">Semua Status</option>
            <option value="to_do">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>

        <button class="btn-reset" id="btnReset" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
    </div>

    @can('create ' . $permissionAkses)
        <div class="tbar-right">
            <button class="btn-add" onclick="window.location.href = '{{ route('projects.create') }}'">
                <span><i class="bi bi-plus-lg"></i> Tambah Project</span>
            </button>
        </div>
    @endcan
</div>
