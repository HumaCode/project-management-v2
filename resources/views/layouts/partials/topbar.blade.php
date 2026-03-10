<header class="topbar">
    <button class="btn-toggle" id="btnToggle" title="Toggle Sidebar">
        <i class="bi bi-layout-sidebar-inset" id="toggleIcon"></i>
    </button>
    <div class="tb-crumb d-none d-lg-flex">
        <i class="bi bi-house-fill" style="color:var(--muted)"></i>
        <span style="opacity:.4">/</span>
        <span class="cur">Dashboard</span>
    </div>
    <div class="tb-search ms-2">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Cari project, dokumen..." />
    </div>
    <div class="tb-right">
        <button class="btn-bell">
            <i class="bi bi-bell-fill"></i>
            <span class="bell-dot"></span>
        </button>
        <div style="position:relative">
            <div class="user-trigger" id="userTrigger">
                <div class="ut-av">BS</div>
                <div class="ut-info">
                    <div class="ut-name">Budi Santoso</div>
                    <div class="ut-email">budi@example.com</div>
                </div>
                <i class="bi bi-chevron-down ut-arrow"></i>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <div class="dd-header">
                    <div class="dd-av">BS</div>
                    <div>
                        <div class="dd-name">Budi Santoso</div>
                        <div class="dd-email">budi@example.com</div>
                        <span class="dd-role">ADMIN</span>
                    </div>
                </div>
                <div class="dd-body">
                    <div class="dd-item"><i class="bi bi-person-circle"></i><span>Profil Saya</span></div>
                    <div class="dd-item"><i class="bi bi-gear-wide-connected"></i><span>Pengaturan</span>
                    </div>
                    <div class="dd-item">
                        <i class="bi bi-bell-fill"></i><span>Notifikasi</span>
                        <span
                            style="margin-left:auto;font-size:10px;background:rgba(255,77,109,.15);color:var(--err);padding:1px 6px;border-radius:8px;font-family:var(--mono)">3</span>
                    </div>
                    <div class="dd-item"><i class="bi bi-moon-stars-fill"></i><span>Mode Gelap</span>
                    </div>
                    <div class="dd-sep"></div>
                    <div class="dd-item danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="bi bi-box-arrow-right"></i><span>Logout</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
