<x-master-layout>
    @section('title', $title)

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
                <div class="stat-num" data-count="{{ $top_stats['total_projects'] }}">0</div>
                <div class="stat-lbl">Total Project</div>
                <span class="stat-pill pill-up"><i class="bi bi-info-circle"></i> Semua project</span>
                <div class="stat-bar">
                    <div class="stat-fill" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="80">
            <div class="stat-card s-cyan">
                <div class="stat-ico"><i class="bi bi-arrow-repeat"></i></div>
                <div class="stat-num" data-count="{{ $top_stats['total_in_progress'] }}">0</div>
                <div class="stat-lbl">Sedang Berjalan</div>
                <span class="stat-pill pill-eq"><i class="bi bi-dash"></i> Aktif saat ini</span>
                <div class="stat-bar">
                    <div class="stat-fill"
                        style="width: {{ $top_stats['total_projects'] > 0 ? ($top_stats['total_in_progress'] / $top_stats['total_projects']) * 100 : 0 }}%">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="160">
            <div class="stat-card s-green">
                <div class="stat-ico"><i class="bi bi-folder2-open"></i></div>
                <div class="stat-num" data-count="{{ $top_stats['total_documents'] }}">0</div>
                <div class="stat-lbl">Total Dokumen</div>
                <span class="stat-pill pill-up"><i class="bi bi-files"></i> File tersimpan</span>
                <div class="stat-bar">
                    <div class="stat-fill" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="240">
            <div class="stat-card s-warn">
                <div class="stat-ico"><i class="bi bi-clock-history"></i></div>
                <div class="stat-num" data-count="{{ $top_stats['upcoming_deadlines_count'] }}">0</div>
                <div class="stat-lbl">Deadline Dekat</div>
                <span class="stat-pill pill-dn"><i class="bi bi-alarm"></i> Dalam 3 hari</span>
                <div class="stat-bar">
                    <div class="stat-fill" style="width: 100%; background: var(--err)"></div>
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
                    <a href="{{ route('projects.index') }}"
                        style="font-size:12.5px;color:var(--cyan);font-family:var(--mono);display:flex;align-items:center;gap:4px">Lihat
                        semua <i class="bi bi-arrow-right"></i></a>
                </div>
                <div class="crd-body" id="activeProjectsContainer">
                    @include('pages.dashboard.partials.active-projects')
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4" data-aos="fade-up" data-aos-delay="130">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-alarm-fill"></i>Deadline Dekat</div><span
                        class="crd-badge">{{ $upcoming_deadlines->count() }}</span>
                </div>
                <div class="crd-body">
                    @forelse($upcoming_deadlines as $dl)
                        <div class="dl-item ptr" onclick="window.location='{{ route('projects.show', $dl->id) }}'">
                            @php
                                $diff = ceil(now()->floatDiffInDays($dl->deadline, false));
                                $barColor = $diff < 0 ? 'var(--err)' : ($diff <= 3 ? 'var(--warn)' : 'var(--cyan)');
                                $badgeClass = $diff < 0 ? 'dlb-r' : ($diff <= 3 ? 'dlb-y' : 'dlb-b');
                            @endphp
                            <div class="dl-bar" style="background:{{ $barColor }}"></div>
                            <div class="dl-info">
                                <div class="dl-name">{{ $dl->name }}</div>
                                <div class="dl-date"><i class="bi bi-calendar3"></i>
                                    {{ tgl_indo($dl->deadline->format('Y-m-d')) }}</div>
                            </div>
                            <span class="dl-badge {{ $badgeClass }}">
                                {{ $diff < 0 ? 'Late' : ($diff == 0 ? 'Today' : 'H-' . $diff) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted" style="font-size: 13px;">Tidak ada deadline dalam
                            waktu dekat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Activity -->
    <div class="row g-3 mb-24">
        <div class="col-12 col-md-5 col-xl-4" data-aos="fade-up" data-aos-delay="80">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-bar-chart-fill"></i>Project Baru per Bulan</div>
                </div>
                <div class="crd-body d-flex flex-column h-100">
                    <div class="chart-wrap mb-3" id="chartWrap"></div>

                    <div style="height:1px;background:var(--bd);margin:10px 0 8px"></div>
                    <div style="display:flex;justify-content:space-between">
                        <span style="font-family:var(--mono);font-size:10px;color:var(--muted)">
                            {{ now()->subMonths(5)->translatedFormat('M') }} – {{ now()->translatedFormat('M Y') }}
                        </span>
                        <span style="font-family:var(--mono);font-size:11px;color:var(--cyan)">Total:
                            {{ array_sum($monthly_projects) }} project</span>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Inject dynamic chart data SEBELUM dashboard.js dimuat
            @php
                $months = [];
                for ($i = 5; $i >= 0; $i--) {
                    $months[] = now()->subMonths($i)->translatedFormat('M');
                }
                $chartData = [];
                foreach ($months as $idx => $m) {
                    $chartData[] = ['l' => $m, 'v' => $monthly_projects[$idx] ?? 0];
                }
            @endphp
            window.dashboardChartData = @json($chartData);
        </script>
        <div class="col-12 col-md-7 col-xl-8" data-aos="fade-up" data-aos-delay="80">
            <div class="crd h-100">
                <div class="crd-head">
                    <div class="crd-title"><i class="bi bi-activity"></i>Aktivitas Terbaru</div><span
                        class="crd-badge">Live</span>
                </div>
                <div class="crd-body" id="recentActivitiesContainer">
                    @include('pages.dashboard.partials.recent-activities')
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).on('click', '.dashboard-pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                // Detect which container to update based on the closest card body ID
                let container = $(this).closest('.crd-body');
                
                if (!container.length) return;

                // Add loading state
                container.addClass('op-5');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        container.html(data).removeClass('op-5');
                        
                        // Refresh AOS if needed
                        if(typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }
                    },
                    error: function() {
                        container.removeClass('op-5');
                        SCA.error('Gagal memuat data');
                    }
                });
            });
        </script>
        <style>
            .op-5 { opacity: 0.5; pointer-events: none; transition: opacity 0.2s; }
        </style>
    @endpush

</x-master-layout>
