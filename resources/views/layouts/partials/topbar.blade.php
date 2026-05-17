<header class="topbar">
    <button class="btn-toggle" id="btnToggle" title="Toggle Sidebar">
        <i class="bi bi-layout-sidebar-inset" id="toggleIcon"></i>
    </button>
    <div class="tb-crumb d-none d-lg-flex">
        <i class="bi bi-house-fill" style="color:var(--muted)"></i>
        <i class="bi bi-chevron-right" style="opacity:.3;font-size:10px;margin:0 4px"></i>
        <span class="cur">{{ $title ?? (View::hasSection('title') ? View::getSection('title') : 'Dashboard') }}</span>
    </div>
    <div class="tb-search ms-2">
        <i class="bi bi-search"></i>
        <input type="text" id="globalSearchInput" placeholder="Cari project, dokumen, catatan..." autocomplete="off" />
        <div class="tb-search-results" id="globalSearchResults"></div>
    </div>
    <div class="tb-right">
        <div style="position:relative">
            <button class="btn-bell" id="notifTrigger" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                <span class="bell-dot d-none" id="notifDot"></span>
            </button>
            <div class="notif-dropdown" id="notifDropdown">
                <div class="nd-header">
                    <span class="nd-title">Notifikasi</span>
                    <button class="nd-mark-all" id="notifMarkAll">Tandai semua dibaca</button>
                </div>
                <div class="nd-body" id="notifDropdownBody">
                    <div class="nd-no-data">
                        <i class="bi bi-bell-slash"></i>
                        Tidak ada notifikasi baru
                    </div>
                </div>
                <div class="nd-footer">
                    <a href="{{ route('profil.index') }}?pane=notifikasi">Pengaturan Preferensi</a>
                </div>
            </div>
        </div>
        <div style="position:relative">
            <div class="user-trigger" id="userTrigger">
                <div class="ut-av">{{ user('initial') }}</div>
                <div class="ut-info">
                    <div class="ut-name">{{ user('name') }}</div>
                    <div class="ut-email">{{ user('email') }}</div>
                </div>
                <i class="bi bi-chevron-down ut-arrow"></i>
            </div>
            <div class="user-dropdown" id="userDropdown">
                <div class="dd-header">
                    <div class="dd-av">{{ user('initial') }}</div>
                    <div>
                        <div class="dd-name">{{ user('name') }}</div>
                        <div class="dd-email">{{ user('email') }}</div>
                        <span class="dd-role">{{ user('role') }}</span>
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
