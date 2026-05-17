<div class="act-list">
    @forelse($activities as $act)
        @php
            // Event label mapping
            $eventLabel = match($act->event) {
                'created' => 'Data Dibuat',
                'updated' => 'Data Diperbarui',
                'deleted' => 'Data Dihapus',
                'login' => 'Login Berhasil',
                'logout' => 'Logout Selesai',
                default => ucfirst($act->event ?? 'Aktivitas')
            };

            // Subject mapping
            $subject = '';
            if($act->subject_type) {
                $class = class_basename($act->subject_type);
                $subject = match($class) {
                    'Project' => 'Proyek',
                    'Dokumen' => 'Dokumen',
                    'Team' => 'Tim',
                    'Catatan' => 'Catatan',
                    'User' => 'Pengguna',
                    'KategoriDokumen' => 'Kategori',
                    'Backup' => 'Backup',
                    'Setting' => 'Pengaturan',
                    'Diagram' => 'Diagram',
                    'DokumenItem' => 'Bagian Dokumen',
                    'Laporan' => 'Laporan',
                    'Menu' => 'Menu Sistem',
                    'MenuPermission' => 'Akses Menu',
                    'Permission' => 'Izin Akses',
                    'Role' => 'Role Akses',
                    default => $class
                };
            }

            // Timeline styling configs
            $icon = 'bi-clock-history';
            $colorClass = 'ai-c'; // default: cyan

            if($act->event === 'created') {
                $icon = 'bi-plus-circle-fill';
                $colorClass = 'ai-g'; // green
            } elseif($act->event === 'deleted') {
                $icon = 'bi-trash3-fill';
                $colorClass = 'ai-r'; // red
            } elseif($act->event === 'updated') {
                $icon = 'bi-pencil-fill';
                $colorClass = 'ai-a'; // amber/orange
            } elseif($act->event === 'login') {
                $icon = 'bi-box-arrow-in-right';
                $colorClass = 'ai-c'; // cyan
            } elseif($act->event === 'logout') {
                $icon = 'bi-box-arrow-right';
                $colorClass = 'ai-p'; // purple
            }
        @endphp

        <div class="act-item" data-aos="fade-up">
            <div class="act-ico {{ $colorClass }}"><i class="bi {{ $icon }}"></i></div>
            <div class="act-body">
                <div class="act-title">{{ $eventLabel }} {{ $subject }}</div>
                <div class="act-desc">{{ $act->description }}</div>
                <div class="act-time">
                    <i class="bi bi-clock" style="font-size:10px"></i>
                    {{ $act->created_at->diffForHumans() }}
                    @if(isset($act->properties['ip']) || isset($act->properties['ip_address']))
                        <span class="ip">{{ $act->properties['ip'] ?? $act->properties['ip_address'] }}</span>
                    @else
                        <span class="ip">127.0.0.1</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5" style="opacity: 0.5;">
            <i class="bi bi-clock-history" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
            <span>Belum ada riwayat aktivitas pada akun Anda.</span>
        </div>
    @endforelse
</div>

@if($activities->total() > 0)
    <div class="tbl-foot mt-4" style="border:none; padding: 0 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
        <div class="tbl-info" style="color: var(--dim); font-size: 12px;">
            Menampilkan <span>{{ $activities->firstItem() }}</span> – <span>{{ $activities->lastItem() }}</span> dari <span>{{ $activities->total() }}</span> aktivitas
        </div>
        <div class="pag">
            <div class="dashboard-pagination">
                {{ $activities->links('vendor.pagination.custom-dashboard') }}
            </div>
        </div>
    </div>
@endif
