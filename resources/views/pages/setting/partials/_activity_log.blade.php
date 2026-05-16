<div class="table-responsive">
    <table class="dtbl mb-0" id="tblActivities">
        <thead>
            <tr>
                <th style="text-align:center;width:60px">#</th>
                <th style="width:100px">TIPE</th>
                <th style="min-width:220px">EVENT</th>
                <th>DETAIL</th>
                <th class="text-end pe-4" style="width:160px">WAKTU</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $act)
            @php
                $eventLabel = match($act->event) {
                    'created' => 'Menambahkan',
                    'updated' => 'Mengubah',
                    'deleted' => 'Menghapus',
                    'login' => 'Login',
                    'logout' => 'Logout',
                    default => ucfirst($act->event ?? 'Aktivitas')
                };

                $subject = 'Data';
                $color = '#00c8ff';
                $icon = 'bi-info-lg';
                $rgb = '0, 200, 255';

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
                        default => $class
                    };
                }

                if($act->event === 'created') {
                    $color = '#00e5a0';
                    $icon = 'bi-plus-lg';
                    $rgb = '0, 229, 160';
                } elseif($act->event === 'deleted') {
                    $color = '#ff4d6d';
                    $icon = 'bi-trash3-fill';
                    $rgb = '255, 77, 109';
                } elseif($act->event === 'updated') {
                    $color = '#a78bfa';
                    $icon = 'bi-pencil-fill';
                    $rgb = '167, 139, 250';
                } elseif($act->event === 'login') {
                    $color = '#f59e0b';
                    $icon = 'bi-shield-lock-fill';
                    $rgb = '245, 158, 11';
                }
            @endphp
            <tr>
                <td style="text-align:center; opacity: 0.5;">
                    {{ str_pad($loop->iteration + ($activities->currentPage() - 1) * $activities->perPage(), 2, '0', STR_PAD_LEFT) }}
                </td>
                <td>
                    <div style="width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; color: {{ $color }}; background: rgba({{ $rgb }}, 0.15); border: 1px solid rgba({{ $rgb }}, 0.25); box-shadow: 0 4px 15px rgba({{ $rgb }}, 0.1); transition: all 0.3s ease;">
                        <i class="bi {{ $icon }}" style="font-size: 16px;"></i>
                    </div>
                </td>
                <td>
                    <div style="font-weight: 700; color: #fff; font-size: 14px; letter-spacing: 0.3px;">{{ $eventLabel }} {{ $subject }}</div>
                    <div style="font-size: 10px; color: var(--dim); opacity: 0.6; margin-top: 2px; font-family: var(--mono);">{{ strtoupper($act->log_name ?? 'SYSTEM') }} LOG</div>
                </td>
                <td>
                    <div style="font-size: 13px; color: var(--muted); line-height: 1.5;">{{ $act->description }}</div>
                    @if($act->causer)
                        <div style="font-size: 10px; color: var(--dim); margin-top: 5px; display: flex; align-items: center; gap: 6px;">
                            <div style="width: 18px; height: 18px; border-radius: 50%; background: rgba(0,200,255,0.1); display: grid; place-items: center; font-size: 9px; color: var(--cyan); border: 1px solid rgba(0,200,255,0.2);">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span>Oleh <span style="color: var(--cyan);">{{ $act->causer->name }}</span></span>
                        </div>
                    @endif
                </td>
                <td class="text-end pe-4">
                    <div style="color: #fff; font-size: 12px; font-weight: 600; font-family: var(--mono);">{{ $act->created_at->format('d/m/Y') }}</div>
                    <div style="color: var(--dim); font-size: 11px; margin-top: 2px; opacity: 0.7;">{{ $act->created_at->format('H:i') }} WIB</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-5">
                    <div class="tbl-empty" style="background: transparent; border: 0; padding: 0;">
                        <i class="bi bi-inbox" style="font-size: 40px; color: #fff; opacity: 0.1;"></i>
                        <div style="font-size: 13px; margin-top: 12px; color: #fff; opacity: 0.2;">Log aktivitas masih kosong</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($activities->total() > 0)
<div class="tbl-foot mt-0">
    <div class="tbl-info">
        Menampilkan <span>{{ $activities->firstItem() }}</span> – <span>{{ $activities->lastItem() }}</span> dari <span>{{ $activities->total() }}</span> data
    </div>
    <div class="pag">
        <div class="dashboard-pagination">
            {{ $activities->links('vendor.pagination.custom-dashboard') }}
        </div>
    </div>
</div>
@endif
