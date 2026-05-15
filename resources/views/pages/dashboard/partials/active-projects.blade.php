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
            <tr class="ptr"
                onclick="window.location='{{ route('projects.show', $project->id) }}'">
                <td>
                    <div class="proj-name">{{ $project->name }}</div>
                    <div class="proj-meta">{{ $project->members_count }} anggota ·
                        {{ $project->dokumens_count }} dokumen</div>
                </td>
                <td>
                    @php
                        $statusClasses = [
                            'todo' => 'st-todo',
                            'in_progress' => 'st-prog',
                            'on_hold' => 'st-warn',
                            'done' => 'st-done',
                        ];
                        $statusLabel = [
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'on_hold' => 'On Hold',
                            'done' => 'Done',
                        ];
                    @endphp
                    <span class="status-tag {{ $statusClasses[$project->status] ?? 'st-todo' }}">
                        <span
                            class="dot"></span>{{ $statusLabel[$project->status] ?? $project->status }}
                    </span>
                </td>
                <td class="d-none d-md-table-cell">
                    <div class="prog-bar">
                        <div class="prog-fill" style="width:{{ $project->progress }}%"></div>
                    </div>
                    <div
                        style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-top:3px">
                        {{ $project->progress }}%</div>
                </td>
                <td class="d-none d-lg-table-cell">
                    @if ($project->deadline)
                        @php
                            $diff = ceil(now()->floatDiffInDays($project->deadline, false));
                            $dlClass = $diff < 0 ? 'late' : ($diff <= 3 ? 'soon' : '');
                        @endphp
                        <span class="dl-tag {{ $dlClass }}">
                            @if ($diff < 0)
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
                <td colspan="4" class="text-center py-4 text-muted">Belum ada project aktif.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if ($active_projects->hasPages())
    <div class="mt-4 mb-2 dashboard-pagination-wrapper">
        <div class="text-center mb-2" style="font-size: 11px; color: var(--muted); font-family: var(--mono);">
            Menampilkan {{ $active_projects->firstItem() }} - {{ $active_projects->lastItem() }} dari {{ $active_projects->total() }} project
        </div>
        <div class="d-flex justify-content-center dashboard-pagination">
            {{ $active_projects->appends(request()->except('proj_page'))->links('vendor.pagination.custom-dashboard') }}
        </div>
    </div>
@endif
