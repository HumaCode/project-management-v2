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
                    <div class="stat-fill" style="width: {{ $top_stats['total_projects'] > 0 ? ($top_stats['total_in_progress'] / $top_stats['total_projects']) * 100 : 0 }}%"></div>
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
                <div class="crd-body">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Nama Project</th>
                                <th style="width:110px">Status</th>
                                <th style="width:100px" class="d-none d-md-table-cell">Progress</th>
                                <th style="width:110px" class="d-none d-lg-table-cell">Deadline</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($active_projects as $project)
                            <tr class="ptr" onclick="window.location='{{ route('projects.show', $project->slug) }}'">
                                <td>
                                    <div class="proj-name">{{ $project->name }}</div>
                                    <div class="proj-meta">{{ $project->members_count }} anggota · {{ $project->dokumens_count }} dokumen</div>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'todo' => 'st-todo',
                                            'in_progress' => 'st-prog',
                                            'on_hold' => 'st-warn',
                                            'done' => 'st-done'
                                        ];
                                        $statusLabel = [
                                            'todo' => 'To Do',
                                            'in_progress' => 'In Progress',
                                            'on_hold' => 'On Hold',
                                            'done' => 'Done'
                                        ];
                                    @endphp
                                    <span class="status-tag {{ $statusClasses[$project->status] ?? 'st-todo' }}">
                                        <span class="dot"></span>{{ $statusLabel[$project->status] ?? $project->status }}
                                    </span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:{{ $project->progress }}%"></div>
                                    </div>
                                    <div style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                                        {{ $project->progress }}%</div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @if($project->deadline)
                                        @php
                                            $diff = now()->diffInDays($project->deadline, false);
                                            $dlClass = $diff < 0 ? 'late' : ($diff <= 3 ? 'soon' : '');
                                        @endphp
                                        <span class="dl-tag {{ $dlClass }}">
                                            @if($diff < 0)
                                                Terlambat!
                                            @elseif($diff == 0)
                                                Hari ini
                                            @elseif($diff <= 7)
                                                {{ $diff }} hari lagi
                                            @else
                                                {{ tgl_indo($project->deadline->format('Y-m-d')) }}
                                            @endif
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada project aktif.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
                    <div class="dl-item ptr" onclick="window.location='{{ route('projects.show', $dl->slug) }}'">
                        @php
                            $diff = now()->diffInDays($dl->deadline, false);
                            $barColor = $diff < 0 ? 'var(--err)' : ($diff <= 3 ? 'var(--warn)' : 'var(--cyan)');
                            $badgeClass = $diff < 0 ? 'dlb-r' : ($diff <= 3 ? 'dlb-y' : 'dlb-b');
                        @endphp
                        <div class="dl-bar" style="background:{{ $barColor }}"></div>
                        <div class="dl-info">
                            <div class="dl-name">{{ $dl->name }}</div>
                            <div class="dl-date"><i class="bi bi-calendar3"></i> {{ tgl_indo($dl->deadline->format('Y-m-d')) }}</div>
                        </div>
                        <span class="dl-badge {{ $badgeClass }}">
                            {{ $diff < 0 ? 'Late' : ($diff == 0 ? 'Today' : 'H-'.$diff) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted" style="font-size: 13px;">Tidak ada deadline dalam waktu dekat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart + Activity -->
    <div class="row g-3 mb-24">
        <div class="col-12 col-md-5 col-xl-4" data-aos="fade-up" data-aos-delay="0">
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
                        <span style="font-family:var(--mono);font-size:11px;color:var(--cyan)">Total: {{ array_sum($monthly_projects) }} project</span>
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
                foreach($months as $idx => $m) {
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
                <div class="crd-body">
                    @forelse($recent_activities as $act)
                    <div class="act-item">
                        @php
                            $iconClass = 'aic-b';
                            $icon = 'bi-info-circle';
                            
                            if (str_contains($act->description, 'created') || str_contains($act->description, 'upload')) {
                                $iconClass = 'aic-c';
                                $icon = 'bi-plus-circle-fill';
                            } elseif (str_contains($act->description, 'updated')) {
                                $iconClass = 'aic-g';
                                $icon = 'bi-pencil-square';
                            } elseif (str_contains($act->description, 'deleted')) {
                                $iconClass = 'aic-r';
                                $icon = 'bi-trash-fill';
                            }
                        @endphp
                        <div class="act-ico {{ $iconClass }}"><i class="bi {{ $icon }}"></i></div>
                        <div class="act-body">
                            <div class="act-txt">
                                <strong>{{ $act->causer->name ?? 'System' }}</strong> 
                                {{ $act->description }} 
                                @if($act->subject_type == 'App\Models\Project')
                                    project <strong>{{ $act->subject->name ?? '' }}</strong>
                                @elseif($act->subject_type == 'App\Models\Dokumen')
                                    dokumen <strong>{{ $act->subject->nama ?? '' }}</strong>
                                @endif
                            </div>
                            <div class="act-time"><i class="bi bi-clock"></i> {{ $act->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">Belum ada aktivitas tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-master-layout>
