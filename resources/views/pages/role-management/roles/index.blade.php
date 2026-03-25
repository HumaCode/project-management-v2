<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
    @endpush

    @push('js')
        <script></script>
    @endpush


    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-xl-flex">
                <a href="dashboard.html"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="info-banner" data-aos="fade-up" data-aos-delay="20">
        <i class="bi bi-info-circle-fill"></i>
        <p>Halaman ini untuk mengelola daftar role (nama, slug, guard). Untuk mengatur <strong>permission akses menu per
                role</strong>, gunakan halaman <a href="role.html">Konfigurasi Akses Role <i class="bi bi-arrow-right"
                    style="font-size:10px"></i></a></p>
    </div>


    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc p">
            <div class="sc-ico p"><i class="bi bi-shield-fill"></i></div>
            <div>
                <div class="sc-val">5</div>
                <div class="sc-lbl">Total Role</div>
            </div>
        </div>
        <div class="sc c">
            <div class="sc-ico c"><i class="bi bi-globe"></i></div>
            <div>
                <div class="sc-val">3</div>
                <div class="sc-lbl">Guard Web</div>
            </div>
        </div>
        <div class="sc g">
            <div class="sc-ico g"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="sc-val">10</div>
                <div class="sc-lbl">Total User</div>
            </div>
        </div>
        <div class="sc a">
            <div class="sc-ico a"><i class="bi bi-key-fill"></i></div>
            <div>
                <div class="sc-val">133</div>
                <div class="sc-lbl">Total Permission</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari data..." />
        </div>

        <select class="nsel" style="min-width:128px">
            <option value="all">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak Aktif</option>
        </select>
        <div class="tbar-right">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:180px">ROLE</th>
                        <th style="min-width:80px">GUARD</th>
                        <th style="min-width:200px">DESKRIPSI</th>
                        <th style="min-width:140px">PERMISSION</th>
                        <th style="min-width:80px">USER</th>
                        <th style="min-width:100px">STATUS</th>
                        <th style="min-width:110px">DIBUAT</th>
                        <th style="min-width:110px">DIPERBARUI</th>
                        <th style="text-align:center;width:110px">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-no">01</td>
                        <td>
                            <div class="td-role">
                                <div class="role-av" style="background:linear-gradient(135deg,#7f1d1d,#ff4d6d)">A</div>
                                <div class="role-info">
                                    <div class="role-nm">Admin</div>
                                    <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i> admin
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td><span class="guard-badge rg-web">web</span></td>
                        <td class="td-desc">Akses penuh ke seluruh fitur dan pengaturan sistem</td>
                        <td>
                            <div class="perm-wrap">
                                <span class="perm-count">65</span>
                                <div class="perm-bar-track">
                                    <div class="perm-bar-fill" style="width:100%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-count-wrap">
                                <span class="uc-num">1</span>
                                <span class="uc-lbl">user</span>
                            </div>
                        </td>
                        <td><span class="rs-badge rs-aktif">Aktif</span></td>
                        <td class="td-dt">03 Jan 2024</td>
                        <td class="td-dt">07 Mar 2025</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-p" title="Konfigurasi Permission"
                                    onclick="window.location='role.html'">
                                    <i class="bi bi-shield-fill-check"></i>
                                </button>
                                <button class="ibtn ib-e" title="Edit Role" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-nm="Admin" data-slug="admin" data-guard="web"
                                    data-desc="Akses penuh ke seluruh fitur dan pengaturan sistem"
                                    data-status="aktif">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ibtn ib-x" title="Hapus Role" data-bs-toggle="modal"
                                    data-bs-target="#delModal" data-nm="Admin" data-users="1">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">02</td>
                        <td>
                            <div class="td-role">
                                <div class="role-av" style="background:linear-gradient(135deg,#78350f,#f59e0b)">M
                                </div>
                                <div class="role-info">
                                    <div class="role-nm">Manager</div>
                                    <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i>
                                        manager</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="guard-badge rg-web">web</span></td>
                        <td class="td-desc">Kelola proyek, tim, laporan, dan koordinasi anggota</td>
                        <td>
                            <div class="perm-wrap">
                                <span class="perm-count">40</span>
                                <div class="perm-bar-track">
                                    <div class="perm-bar-fill" style="width:61%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-count-wrap">
                                <span class="uc-num">3</span>
                                <span class="uc-lbl">user</span>
                            </div>
                        </td>
                        <td><span class="rs-badge rs-aktif">Aktif</span></td>
                        <td class="td-dt">03 Jan 2024</td>
                        <td class="td-dt">05 Mar 2025</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-p" title="Konfigurasi Permission"
                                    onclick="window.location='role.html'">
                                    <i class="bi bi-shield-fill-check"></i>
                                </button>
                                <button class="ibtn ib-e" title="Edit Role" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-nm="Manager" data-slug="manager"
                                    data-guard="web" data-desc="Kelola proyek, tim, laporan, dan koordinasi anggota"
                                    data-status="aktif">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ibtn ib-x" title="Hapus Role" data-bs-toggle="modal"
                                    data-bs-target="#delModal" data-nm="Manager" data-users="3">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">03</td>
                        <td>
                            <div class="td-role">
                                <div class="role-av" style="background:linear-gradient(135deg,#0c4a6e,#00c8ff)">M
                                </div>
                                <div class="role-info">
                                    <div class="role-nm">Member</div>
                                    <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i>
                                        member</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="guard-badge rg-web">web</span></td>
                        <td class="td-desc">Akses baca, kontribusi task, dan unggah dokumen</td>
                        <td>
                            <div class="perm-wrap">
                                <span class="perm-count">18</span>
                                <div class="perm-bar-track">
                                    <div class="perm-bar-fill" style="width:27%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-count-wrap">
                                <span class="uc-num">6</span>
                                <span class="uc-lbl">user</span>
                            </div>
                        </td>
                        <td><span class="rs-badge rs-aktif">Aktif</span></td>
                        <td class="td-dt">03 Jan 2024</td>
                        <td class="td-dt">01 Feb 2025</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-p" title="Konfigurasi Permission"
                                    onclick="window.location='role.html'">
                                    <i class="bi bi-shield-fill-check"></i>
                                </button>
                                <button class="ibtn ib-e" title="Edit Role" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-nm="Member" data-slug="member" data-guard="web"
                                    data-desc="Akses baca, kontribusi task, dan unggah dokumen" data-status="aktif">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ibtn ib-x" title="Hapus Role" data-bs-toggle="modal"
                                    data-bs-target="#delModal" data-nm="Member" data-users="6">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">04</td>
                        <td>
                            <div class="td-role">
                                <div class="role-av" style="background:linear-gradient(135deg,#14532d,#00e5a0)">S
                                </div>
                                <div class="role-info">
                                    <div class="role-nm">Supervisor</div>
                                    <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i>
                                        supervisor</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="guard-badge rg-web">web</span></td>
                        <td class="td-desc">Monitoring proyek dan persetujuan laporan</td>
                        <td>
                            <div class="perm-wrap">
                                <span class="perm-count">28</span>
                                <div class="perm-bar-track">
                                    <div class="perm-bar-fill" style="width:43%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-count-wrap">
                                <span class="uc-num">0</span>
                                <span class="uc-lbl">user</span>
                            </div>
                        </td>
                        <td><span class="rs-badge rs-nonaktif">Nonaktif</span></td>
                        <td class="td-dt">20 Feb 2025</td>
                        <td class="td-dt">20 Feb 2025</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-p" title="Konfigurasi Permission"
                                    onclick="window.location='role.html'">
                                    <i class="bi bi-shield-fill-check"></i>
                                </button>
                                <button class="ibtn ib-e" title="Edit Role" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-nm="Supervisor" data-slug="supervisor"
                                    data-guard="web" data-desc="Monitoring proyek dan persetujuan laporan"
                                    data-status="nonaktif">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ibtn ib-x" title="Hapus Role" data-bs-toggle="modal"
                                    data-bs-target="#delModal" data-nm="Supervisor" data-users="0">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">05</td>
                        <td>
                            <div class="td-role">
                                <div class="role-av" style="background:linear-gradient(135deg,#1e1b4b,#a78bfa)">V
                                </div>
                                <div class="role-info">
                                    <div class="role-nm">Viewer</div>
                                    <div class="role-slug"><i class="bi bi-code-slash" style="font-size:9px"></i>
                                        viewer</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="guard-badge rg-api">api</span></td>
                        <td class="td-desc">Akses hanya baca via API untuk integrasi eksternal</td>
                        <td>
                            <div class="perm-wrap">
                                <span class="perm-count">10</span>
                                <div class="perm-bar-track">
                                    <div class="perm-bar-fill" style="width:15%"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-count-wrap">
                                <span class="uc-num">0</span>
                                <span class="uc-lbl">user</span>
                            </div>
                        </td>
                        <td><span class="rs-badge rs-aktif">Aktif</span></td>
                        <td class="td-dt">01 Mar 2025</td>
                        <td class="td-dt">01 Mar 2025</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-p" title="Konfigurasi Permission"
                                    onclick="window.location='role.html'">
                                    <i class="bi bi-shield-fill-check"></i>
                                </button>
                                <button class="ibtn ib-e" title="Edit Role" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-nm="Viewer" data-slug="viewer" data-guard="api"
                                    data-desc="Akses hanya baca via API untuk integrasi eksternal"
                                    data-status="aktif">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ibtn ib-x" title="Hapus Role" data-bs-toggle="modal"
                                    data-bs-target="#delModal" data-nm="Viewer" data-users="0">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>8</b> dari <b>32</b> catatan</div>
            <div class="pag">
                <button class="pb" disabled><i class="bi bi-chevron-left"></i></button>
                <button class="pb active">1</button>
                <button class="pb">2</button>
                <button class="pb">3</button>
                <span class="pag-dot">&hellip;</span>
                <button class="pb">4</button>
                <button class="pb"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>

</x-master-layout>
