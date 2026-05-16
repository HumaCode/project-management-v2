@forelse($backups as $bk)
<div class="backup-card">
    <div class="bk-ico"><i class="bi bi-file-earmark-zip-fill"></i></div>
    <div>
        <div class="bk-nm">
            {{ $bk['filename'] }}
            @if($bk['type'] == 'auto')
                <span class="badge bg-soft-info text-info ms-1" style="font-size:10px;padding:2px 6px">Auto</span>
            @else
                <span class="badge bg-soft-warning text-warning ms-1" style="font-size:10px;padding:2px 6px">Manual</span>
            @endif
        </div>
        <div class="bk-meta">{{ $bk['date'] }} &bull; {{ $bk['time'] }} &bull; {{ $bk['size'] }}</div>
    </div>
    <div class="bk-actions">
        <a href="{{ route('settings.download-backup', $bk['id']) }}" class="btn-bk btn-bk-dl" style="text-decoration:none"><i class="bi bi-download"></i> Unduh</a>
        <button type="button" class="btn-bk btn-bk-rm btnDeleteBackup" data-filename="{{ $bk['filename'] }}" data-url="{{ route('settings.delete-backup', $bk['id']) }}"><i class="bi bi-trash3-fill"></i></button>
    </div>
</div>
@empty
<div class="empty-state py-5 text-center" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.05); border-radius: 16px; margin: 10px 0;">
    <div class="empty-ico-wrap" style="width: 64px; height: 64px; background: linear-gradient(135deg, rgba(0, 200, 255, 0.1), rgba(0, 114, 198, 0.1)); border-radius: 50%; display: grid; place-items: center; margin: 0 auto 15px; border: 1px solid rgba(0, 200, 255, 0.2); box-shadow: 0 10px 20px rgba(0,0,0,0.2);">
        <i class="bi bi-database-exclamation" style="font-size: 28px; color: var(--cyan); text-shadow: 0 0 15px rgba(0, 200, 255, 0.5);"></i>
    </div>
    <h6 style="color: #fff; font-weight: 700; margin-bottom: 5px; font-size: 15px; letter-spacing: 0.5px;">Belum Ada Riwayat</h6>
    <p style="color: var(--muted); font-size: 12px; max-width: 250px; margin: 0 auto; opacity: 0.7; line-height: 1.5;">Database Anda belum pernah dibackup secara manual maupun otomatis.</p>
    <div style="margin-top: 20px;">
        <button type="button" class="btn-bk" onclick="document.getElementById('btnRunBackupManual').click()" style="background: rgba(0, 200, 255, 0.1); border: 1px solid rgba(0, 200, 255, 0.2); color: var(--cyan); padding: 6px 16px; font-size: 11px; border-radius: 20px;">
            <i class="bi bi-play-fill me-1"></i> Mulai Backup Pertama
        </button>
    </div>
</div>
@endforelse
