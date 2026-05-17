<div class="pane" id="pane-sesi">
    <div class="pcard" data-aos="fade-up">
        <div class="pc-hd">
            <div class="pc-hd-left">
                <div class="pc-hd-ico pci-p"><i class="bi bi-laptop"></i></div>
                <div>
                    <div class="pc-hd-title">Sesi Login Aktif</div>
                    <div class="pc-hd-sub">Kelola perangkat yang sedang terautentikasi</div>
                </div>
            </div>
            @if(count($activeSessions) > 1)
                <button class="btn-sec btn-revoke-all"
                    style="height:34px;font-size:12px;padding:0 13px;color:var(--err);border-color:rgba(255,77,109,.2)"><i
                        class="bi bi-x-circle-fill"></i> Cabut Semua Lain</button>
            @endif
        </div>
        
        @forelse($activeSessions as $session)
            <div class="session-item" id="session-{{ $session->id }}">
                <div class="sess-ico"><i class="bi {{ $session->icon }}"></i></div>
                <div style="flex:1;min-width:0">
                    <div class="sess-nm">
                        {{ $session->device_name }}
                        @if($session->is_current)
                            <span class="sess-badge sb-current"><i class="bi bi-circle-fill" style="font-size:6px"></i>Sesi ini</span>
                        @else
                            <span class="sess-badge sb-inactive">Tidak aktif</span>
                        @endif
                    </div>
                    <div class="sess-detail">
                        <span><i class="bi bi-geo-alt"></i>{{ $session->location }}</span>
                        <span><i class="bi bi-hdd-network"></i>{{ $session->ip_address }}</span>
                    </div>
                    <div class="sess-time">
                        <i class="bi bi-clock" style="font-size:10px;margin-right:3px"></i>
                        @if($session->is_current)
                            Aktif sekarang
                        @else
                            {{ $session->last_active->diffForHumans() }}
                        @endif
                    </div>
                </div>
                @if(!$session->is_current)
                    <button class="btn-revoke btn-revoke-single" data-id="{{ $session->id }}"><i class="bi bi-x-circle-fill"></i> Cabut</button>
                @endif
            </div>
        @empty
            <div class="text-center py-5" style="opacity: 0.5;">
                <i class="bi bi-shield-lock" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                <span>Tidak ada sesi login aktif ditemukan.</span>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Cabut Sesi Tunggal (Single Revocation)
    $(document).on('click', '.btn-revoke-single', function(e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.data('id');

        SCA.dialog({
            type: "warning",
            title: "Cabut Sesi?",
            message: "Apakah Anda yakin ingin mencabut sesi login pada perangkat ini?",
            confirmText: "Ya, Cabut",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm" role="status" style="width:12px;height:12px;margin-right:4px"></i> Menghapus...');
            
            $.ajax({
                url: "{{ route('profil.sessions.revoke', ['id' => 'SESSION_ID']) }}".replace('SESSION_ID', id),
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#session-' + id).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.session-item').length <= 1) {
                                $('.btn-revoke-all').fadeOut(200);
                            }
                        });
                        
                        if (window.SCA && typeof SCA.toast === 'function') {
                            SCA.toast({
                                type: "success",
                                title: "Berhasil!",
                                message: res.message || "Sesi login berhasil dicabut.",
                                position: "top-right"
                            });
                        }
                    } else {
                        btn.prop('disabled', false).html('<i class="bi bi-x-circle-fill"></i> Cabut');
                        alert(res.message || 'Gagal mencabut sesi.');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bi bi-x-circle-fill"></i> Cabut');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    alert(msg);
                }
            });
        });
    });

    // 2. Cabut Semua Sesi Lain (Revoke All Other Sessions)
    $(document).on('click', '.btn-revoke-all', function(e) {
        e.preventDefault();
        let btn = $(this);

        SCA.dialog({
            type: "danger",
            title: "Cabut Semua Sesi Lain?",
            message: "Semua perangkat lain yang sedang login akan langsung dikeluarkan secara massal.",
            confirmText: "Ya, Cabut Semua",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm" role="status" style="width:12px;height:12px;margin-right:4px"></i> Memproses...');
            
            $.ajax({
                url: "{{ route('profil.sessions.revoke-others') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('.session-item').each(function() {
                            if (!$(this).find('.sb-current').length) {
                                $(this).fadeOut(300, function() {
                                    $(this).remove();
                                });
                            }
                        });
                        btn.fadeOut(200);
                        
                        if (window.SCA && typeof SCA.toast === 'function') {
                            SCA.toast({
                                type: "success",
                                title: "Berhasil!",
                                message: res.message || "Semua sesi lain berhasil dicabut.",
                                position: "top-right"
                            });
                        }
                    } else {
                        btn.prop('disabled', false).html('<i class="bi bi-x-circle-fill"></i> Cabut Semua Lain');
                        alert(res.message || 'Gagal mencabut sesi.');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bi bi-x-circle-fill"></i> Cabut Semua Lain');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    alert(msg);
                }
            });
        });
    });
});
</script>
