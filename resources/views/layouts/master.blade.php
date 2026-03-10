<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dashboard.css') }}">

    @stack('css')
</head>

<body>

    <canvas id="bgc"></canvas>

    <!-- SIDEBAR — sibling of .layout, NOT inside it -->
    @include('layouts.partials.sidebar')

    <!-- OVERLAY — sibling of sidebar & .layout, toggled via display:none/block -->
    <div class="sb-overlay" id="sbOverlay"></div>

    <!-- MAIN LAYOUT -->
    <div class="layout">
        <div class="main-wrap" id="mainWrap">

            <!-- Topbar -->
            @include('layouts.partials.topbar')



            <!-- Page Content -->
            <main class="page-body">

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
                                            <td><span class="status-tag st-done"><span
                                                        class="dot"></span>Done</span></td>
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
                                            <td class="d-none d-lg-table-cell"><span
                                                    class="dl-tag late">Terlambat!</span></td>
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

            </main>

            {{-- footer --}}
            @include('layouts.partials.footer')

        </div><!-- /main-wrap -->
    </div><!-- /layout -->

    <!-- FAB -->
    <button class="fab" id="fab" onclick="scrollToTop()" aria-label="Kembali ke atas">
        <div class="fab-p1"></div>
        <div class="fab-p2"></div>
        <div class="fab-ring"></div>
        <div class="fab-orbit"></div>
        <div class="fab-inner"><i class="bi bi-chevron-up"></i></div>
        <div class="fab-tip">Kembali ke atas</div>
    </button>

    <!-- LOGOUT MODAL -->
    @include('layouts.partials.modal-logout')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script src="{{ asset('assets/auth/backend/js/global-js.js') }}"></script>
    <script>
        /* ── Counter animation ── */
        function countUp(el, target, duration) {
            duration = duration || 1200;
            var start = performance.now();
            (function step(now) {
                var p = Math.min((now - start) / duration, 1);
                var ease = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(ease * target);
                if (p < 1) requestAnimationFrame(step);
                else el.textContent = target;
            })(performance.now());
        }
        document.querySelectorAll('.stat-card').forEach(function(card) {
            var io = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        var el = card.querySelector('[data-count]');
                        if (el && !el.dataset.done) {
                            el.dataset.done = '1';
                            countUp(el, parseInt(el.dataset.count));
                        }
                    }
                });
            }, {
                threshold: .3
            });
            io.observe(card);
        });

        /* ── Chart bars ── */
        var chartData = [{
            l: 'Jan',
            v: 18
        }, {
            l: 'Feb',
            v: 24
        }, {
            l: 'Mar',
            v: 15
        }, {
            l: 'Apr',
            v: 31
        }, {
            l: 'Mei',
            v: 27
        }, {
            l: 'Jun',
            v: 22
        }];
        var maxVal = Math.max.apply(null, chartData.map(function(d) {
            return d.v;
        }));
        var wrap = document.getElementById('chartWrap');
        chartData.forEach(function(d, i) {
            var col = document.createElement('div');
            col.className = 'chart-col';
            col.innerHTML =
                '<div class="chart-bar" style="height:0;transition:height 1.2s cubic-bezier(.4,0,.2,1) ' + (i *
                    120) + 'ms" data-p="' + ((d.v / maxVal) * 100) + '" title="' + d.v +
                ' dokumen"></div><span class="chart-lbl">' + d.l + '</span>';
            wrap.appendChild(col);
        });
        setTimeout(function() {
            document.querySelectorAll('.chart-bar').forEach(function(b) {
                b.style.height = b.dataset.p + '%';
            });
        }, 500);

        /* ── FAB scroll to top ── */
        var fab = document.getElementById('fab');
        var scrollTicking = false;
        window.addEventListener('scroll', function() {
            if (!scrollTicking) {
                requestAnimationFrame(function() {
                    fab.classList.toggle('visible', window.scrollY > 300);
                    scrollTicking = false;
                });
                scrollTicking = true;
            }
        }, {
            passive: true
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        /* ── Logout modal drain ── */
        var logoutModal = document.getElementById('logoutModal');
        logoutModal.addEventListener('show.bs.modal', function() {
            var fill = document.getElementById('drainFill');
            fill.classList.remove('go');
            void fill.offsetWidth;
            fill.classList.add('go');
        });
        logoutModal.addEventListener('hidden.bs.modal', function() {
            document.getElementById('drainFill').classList.remove('go');
        });
    </script>
</body>

</html>
