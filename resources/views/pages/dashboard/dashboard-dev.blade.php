<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dashboard.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('assets/auth/backend/js/dashboard.js') }}"></script>
    @endpush

    <!-- Header + Breadcrumb -->
    <div class="page-header" data-aos="fade-down" data-aos-duration="500">
        <div class="ph-left">
            <div class="ph-icon"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div>
                <div class="ph-title">Dashboard</div>
                <div class="ph-sub">Ringkasan &amp; statistik sistem</div>
            </div>
        </div>
        <div class="breadcrumb-bar">
            <a href="#"><i class="bi bi-house-fill"></i> Home</a>
            <span class="sep"><i class="bi bi-chevron-right"></i></span>
            <span class="here">Dashboard</span>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-24">
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
            <div class="stat-card s-blue">
                <div class="stat-ico"><i class="bi bi-kanban-fill"></i></div>
                <div class="stat-num" data-count="24">0</div>
                <div class="stat-lbl">Total Project</div>
                <span class="stat-pill pill-up"><i class="bi bi-arrow-up-short"></i>+3 bulan ini</span>
                <div class="stat-bar">
                    <div class="stat-fill"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
            <div class="stat-card s-cyan">
                <div class="stat-ico"><i class="bi bi-arrow-repeat"></i></div>
                <div class="stat-num" data-count="9">0</div>
                <div class="stat-lbl">Sedang Berjalan</div>
                <span class="stat-pill pill-eq"><i class="bi bi-dash"></i>Sama bulan lalu</span>
                <div class="stat-bar">
                    <div class="stat-fill"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
            <div class="stat-card s-green">
                <div class="stat-ico"><i class="bi bi-folder2-open"></i></div>
                <div class="stat-num" data-count="137">0</div>
                <div class="stat-lbl">Total Dokumen</div>
                <span class="stat-pill pill-up"><i class="bi bi-arrow-up-short"></i>+18 minggu ini</span>
                <div class="stat-bar">
                    <div class="stat-fill"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
            <div class="stat-card s-warn">
                <div class="stat-ico"><i class="bi bi-clock-history"></i></div>
                <div class="stat-num" data-count="5">0</div>
                <div class="stat-lbl">Deadline Dekat</div>
                <span class="stat-pill pill-dn"><i class="bi bi-arrow-down-short"></i>2 dalam 3
                    hari</span>
                <div class="stat-bar">
                    <div class="stat-fill"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects + Deadlines -->
    <div class="row g-3 mb-24">
        <div class="col-12 col-xl-8" data-aos="fade-up" data-aos-delay="50">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-kanban-fill"></i>Project Aktif</div>
                    <a href="#"
                        style="font-size:12.5px;color:var(--cyan);font-family:var(--mono);display:flex;align-items:center;gap:4px">Lihat
                        semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="crd-body">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Nama Project</th>
                                <th style="width:110px">Status</th>
                                <th style="width:100px" class="d-none d-md-table-cell">Progress</th>
                                <th style="width:90px" class="d-none d-lg-table-cell">Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="ptr">
                                <td>
                                    <div class="proj-name">Sistem Informasi PPID</div>
                                    <div class="proj-meta">3 anggota · 12 dokumen</div>
                                </td>
                                <td><span class="status-tag st-prog"><span class="dot"></span>In
                                        Progress</span></td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:75%"></div>
                                    </div>
                                    <div
                                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        75%</div>
                                </td>
                                <td class="d-none d-lg-table-cell"><span class="dl-tag soon">3 hari
                                        lagi</span></td>
                            </tr>
                            <tr class="ptr">
                                <td>
                                    <div class="proj-name">Aplikasi E-Commerce Mobile</div>
                                    <div class="proj-meta">5 anggota · 8 dokumen</div>
                                </td>
                                <td><span class="status-tag st-prog"><span class="dot"></span>In
                                        Progress</span></td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:42%"></div>
                                    </div>
                                    <div
                                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        42%</div>
                                </td>
                                <td class="d-none d-lg-table-cell"><span class="dl-tag">15 Jan 2025</span>
                                </td>
                            </tr>
                            <tr class="ptr">
                                <td>
                                    <div class="proj-name">Sistem Absensi Karyawan</div>
                                    <div class="proj-meta">2 anggota · 5 dokumen</div>
                                </td>
                                <td><span class="status-tag st-done"><span class="dot"></span>Done</span></td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:100%;background:var(--ok)">
                                        </div>
                                    </div>
                                    <div
                                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        100%</div>
                                </td>
                                <td class="d-none d-lg-table-cell"><span class="dl-tag">Selesai</span>
                                </td>
                            </tr>
                            <tr class="ptr">
                                <td>
                                    <div class="proj-name">Dashboard Monitoring IoT</div>
                                    <div class="proj-meta">4 anggota · 3 dokumen</div>
                                </td>
                                <td><span class="status-tag st-todo"><span class="dot"></span>To
                                        Do</span></td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:10%"></div>
                                    </div>
                                    <div
                                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        10%</div>
                                </td>
                                <td class="d-none d-lg-table-cell"><span class="dl-tag">28 Feb 2025</span>
                                </td>
                            </tr>
                            <tr class="ptr">
                                <td>
                                    <div class="proj-name">Portal Layanan Publik</div>
                                    <div class="proj-meta">6 anggota · 21 dokumen</div>
                                </td>
                                <td><span class="status-tag st-prog"><span class="dot"></span>In
                                        Progress</span></td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:60%"></div>
                                    </div>
                                    <div
                                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        60%</div>
                                </td>
                                <td class="d-none d-lg-table-cell"><span class="dl-tag late">Terlambat!</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4" data-aos="fade-up" data-aos-delay="130">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-alarm-fill"></i>Deadline Dekat</div><span
                        class="crd-badge">5</span>
                </div>
                <div class="crd-body">
                    <div class="dl-item">
                        <div class="dl-bar" style="background:var(--err)"></div>
                        <div class="dl-info">
                            <div class="dl-name">Sistem Informasi PPID</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> 7 Jan 2025</div>
                        </div><span class="dl-badge dlb-r">H-1</span>
                    </div>
                    <div class="dl-item">
                        <div class="dl-bar" style="background:var(--warn)"></div>
                        <div class="dl-info">
                            <div class="dl-name">Portal Layanan Publik</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> 9 Jan 2025</div>
                        </div><span class="dl-badge dlb-y">H-3</span>
                    </div>
                    <div class="dl-item">
                        <div class="dl-bar" style="background:var(--cyan)"></div>
                        <div class="dl-info">
                            <div class="dl-name">E-Commerce Mobile</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> 13 Jan 2025</div>
                        </div><span class="dl-badge dlb-b">H-7</span>
                    </div>
                    <div class="dl-item">
                        <div class="dl-bar" style="background:var(--cyan)"></div>
                        <div class="dl-info">
                            <div class="dl-name">Dashboard IoT Monitor</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> 14 Jan 2025</div>
                        </div><span class="dl-badge dlb-b">H-7</span>
                    </div>
                    <div class="dl-item">
                        <div class="dl-bar" style="background:var(--blue2)"></div>
                        <div class="dl-info">
                            <div class="dl-name">Laporan Akhir Q1</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> 15 Jan 2025</div>
                        </div><span class="dl-badge dlb-b">H-7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Activity -->
    <div class="row g-3 mb-24">
        <div class="col-12 col-md-5 col-xl-4" data-aos="fade-up" data-aos-delay="0">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-bar-chart-fill"></i>Upload per Bulan</div>
                </div>
                <div class="crd-body">
                    <div class="chart-wrap" id="chartWrap"></div>
                    <div style="height:1px;background:var(--bd);margin:10px 0 8px"></div>
                    <div style="display:flex;justify-content:space-between">
                        <span style="font-family:var(--mono);font-size:10px;color:var(--muted)">Jan–Jun
                            2024</span>
                        <span style="font-family:var(--mono);font-size:11px;color:var(--cyan)">Total: 137
                            dok</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-7 col-xl-8" data-aos="fade-up" data-aos-delay="80">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-activity"></i>Aktivitas Terbaru</div><span
                        class="crd-badge">Live</span>
                </div>
                <div class="crd-body">
                    <div class="act-item">
                        <div class="act-ico aic-c"><i class="bi bi-file-earmark-arrow-up-fill"></i></div>
                        <div class="act-body">
                            <div class="act-txt"><strong>Andi Wijaya</strong> mengupload <strong>ERD
                                    v2.pdf</strong> ke project Sistem PPID</div>
                            <div class="act-time"><i class="bi bi-clock"></i> 5 menit lalu</div>
                        </div>
                    </div>
                    <div class="act-item">
                        <div class="act-ico aic-g"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="act-body">
                            <div class="act-txt">Status project <strong>Sistem Absensi</strong> diubah ke
                                <strong style="color:var(--ok)">Done</strong>
                            </div>
                            <div class="act-time"><i class="bi bi-clock"></i> 22 menit lalu</div>
                        </div>
                    </div>
                    <div class="act-item">
                        <div class="act-ico aic-b"><i class="bi bi-journal-plus"></i></div>
                        <div class="act-body">
                            <div class="act-txt"><strong>Siti Rahayu</strong> menambahkan catatan
                                <strong>Notulen Rapat Sprint 3</strong>
                            </div>
                            <div class="act-time"><i class="bi bi-clock"></i> 1 jam lalu</div>
                        </div>
                    </div>
                    <div class="act-item">
                        <div class="act-ico aic-w"><i class="bi bi-person-plus-fill"></i></div>
                        <div class="act-body">
                            <div class="act-txt"><strong>Manager</strong> menambahkan <strong>Deni
                                    Kurnia</strong> sebagai PIC E-Commerce</div>
                            <div class="act-time"><i class="bi bi-clock"></i> 2 jam lalu</div>
                        </div>
                    </div>
                    <div class="act-item">
                        <div class="act-ico aic-c"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                        <div class="act-body">
                            <div class="act-txt"><strong>Budi Santoso</strong> generate <strong>Laporan
                                    PDF</strong> Portal Layanan</div>
                            <div class="act-time"><i class="bi bi-clock"></i> 3 jam lalu</div>
                        </div>
                    </div>
                    <div class="act-item">
                        <div class="act-ico aic-b"><i class="bi bi-kanban-fill"></i></div>
                        <div class="act-body">
                            <div class="act-txt">Project baru <strong>Dashboard Monitoring IoT</strong>
                                berhasil dibuat</div>
                            <div class="act-time"><i class="bi bi-clock"></i> Kemarin, 16:30</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-master-layout>
