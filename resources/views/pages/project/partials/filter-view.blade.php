<div class="crd-head">
    <div class="crd-title">
        <i class="bi bi-table"></i>
        Semua Project
        <span class="crd-badge" id="totalBadge">0</span>
    </div>
    <div class="filter-bar">
        <div class="filter-wrap">
            <i class="bi bi-search"></i>
            <input class="filter-input" id="fSearch" type="text" placeholder="Cari nama / pembuat..." />
        </div>
        <select class="filter-select" id="fStatus">
            <option value="">Semua Status</option>
            <option value="to_do">To Do</option>
            <option value="in_progress">In Progress</option>
            <option value="done">Done</option>
        </select>
        <button class="btn-reset" id="btnReset" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <!-- Tombol tambah — halaman create terpisah -->
        <button class="btn-pms btn-pms-primary" onclick="window.location.href = '{{ route('projects.create') }}'">
            <span><i class="bi bi-plus-lg"></i> Tambah Project</span>
        </button>
    </div>
</div>
