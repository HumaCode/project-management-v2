@forelse($recent_activities as $act)
    <div class="act-item">
        @php
            $iconClass = 'aic-b';
            $icon = 'bi-info-circle';

            if (
                str_contains($act->description, 'created') ||
                str_contains($act->description, 'upload')
            ) {
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
                @if ($act->subject_type == 'App\Models\Project')
                    project <strong>{{ $act->subject->name ?? '' }}</strong>
                @elseif($act->subject_type == 'App\Models\Dokumen')
                    dokumen <strong>{{ $act->subject->nama ?? '' }}</strong>
                @endif
            </div>
            <div class="act-time"><i class="bi bi-clock"></i>
                {{ $act->created_at->diffForHumans() }}</div>
        </div>
    </div>
@empty
    <div class="text-center py-5 text-muted">Belum ada aktivitas tercatat.</div>
@endforelse

@if ($recent_activities->hasPages())
    <div class="mt-4 mb-2 dashboard-pagination-wrapper">
        <div class="text-center mb-2" style="font-size: 11px; color: var(--muted); font-family: var(--mono);">
            Menampilkan {{ $recent_activities->firstItem() }} - {{ $recent_activities->lastItem() }} dari {{ $recent_activities->total() }} aktivitas
        </div>
        <div class="d-flex justify-content-center dashboard-pagination">
            {{ $recent_activities->appends(request()->except('act_page'))->links('vendor.pagination.custom-dashboard') }}
        </div>
    </div>
@endif
