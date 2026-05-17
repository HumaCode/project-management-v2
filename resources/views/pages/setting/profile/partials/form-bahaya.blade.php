<div class="pane" id="pane-bahaya">
    <div class="pcard" data-aos="fade-up">
        <div class="pc-hd">
            <div class="pc-hd-left">
                <div class="pc-hd-ico pci-r"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="pc-hd-title">Zona Bahaya</div>
                    <div class="pc-hd-sub">Tindakan yang tidak dapat dibatalkan</div>
                </div>
            </div>
        </div>
        <div class="pc-body">
            <!-- Deactivate -->
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;border-bottom:1px solid var(--bd);gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                    <div class="sec-ico pci-a"><i class="bi bi-person-dash-fill"></i></div>
                    <div>
                        <div class="sec-title">Nonaktifkan Akun</div>
                        <div class="sec-desc">Akun akan disembunyikan sementara. Anda dapat mengaktifkan kembali
                            kapan saja.</div>
                    </div>
                </div>
                <button class="btn-cancel btn-deactivate" style="border-color:rgba(245,158,11,.25);color:var(--warn);flex-shrink:0"><i
                        class="bi bi-person-dash-fill"></i> Nonaktifkan</button>
            </div>
            <!-- Delete -->
            <div
                style="display:flex;align-items:center;justify-content:space-between;padding:16px 0;gap:12px;flex-wrap:wrap">
                <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0">
                    <div class="sec-ico pci-r"><i class="bi bi-person-x-fill"></i></div>
                    <div>
                        <div class="sec-title" style="color:var(--err)">Hapus Akun Permanen</div>
                        <div class="sec-desc">Semua data, proyek, catatan, dan riwayat akan dihapus selamanya tanpa
                            bisa dipulihkan.</div>
                    </div>
                </div>
                <button class="btn-sec btn-delete-account"
                    style="border-color:rgba(255,77,109,.3);color:var(--err);background:rgba(255,77,109,.07);flex-shrink:0"><i class="bi bi-person-x-fill"></i> Hapus
                    Akun</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. NONAKTIFKAN AKUN (DEACTIVATE)
    $(document).on('click', '#pane-bahaya .btn-deactivate', function(e) {
        e.preventDefault();
        let btn = $(this);

        SCA.dialog({
            type: "warning",
            title: "Nonaktifkan Akun?",
            message: "Akun Anda akan dinonaktifkan sementara. Anda dapat mengaktifkannya kembali kapan saja.",
            confirmText: "Ya, Nonaktifkan",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm" role="status" style="width:12px;height:12px;margin-right:4px;display:inline-block;vertical-align:middle;border:.15em solid currentColor;border-right-color:transparent;border-radius:50%;animation:spinner-border .75s linear infinite"></i> Memproses...');
            
            $.ajax({
                url: "{{ route('profil.deactivate') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        if (window.SCA && typeof SCA.toast === 'function') {
                            SCA.toast({
                                type: "success",
                                title: "Dinonaktifkan!",
                                message: res.message,
                                position: "top-right"
                            });
                        }
                        setTimeout(function() {
                            window.location.href = res.redirect;
                        }, 1500);
                    } else {
                        btn.prop('disabled', false).html('<i class="bi bi-person-dash-fill"></i> Nonaktifkan');
                        alert(res.message || 'Gagal menonaktifkan akun.');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bi bi-person-dash-fill"></i> Nonaktifkan');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    alert(msg);
                }
            });
        });
    });

    // 3. HAPUS AKUN PERMANEN (DELETE ACCOUNT DIRECT CONFIRM)
    $(document).on('click', '#pane-bahaya .btn-delete-account', function(e) {
        e.preventDefault();
        let btn = $(this);

        SCA.dialog({
            type: "danger",
            title: "Hapus Data?",
            message: "Data tidak dapat dikembalikan.",
            confirmText: "Ya, Hapus",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (!confirmed) return;

            btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm" role="status" style="width:12px;height:12px;margin-right:4px;display:inline-block;vertical-align:middle;border:.15em solid currentColor;border-right-color:transparent;border-radius:50%;animation:spinner-border .75s linear infinite"></i> Menghapus...');
            
            $.ajax({
                url: "{{ route('profil.delete-account') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        if (window.SCA && typeof SCA.toast === 'function') {
                            SCA.toast({
                                type: "success",
                                title: "Akun Dihapus!",
                                message: res.message,
                                position: "top-right"
                            });
                        }
                        setTimeout(function() {
                            window.location.href = res.redirect;
                        }, 2000);
                    } else {
                        btn.prop('disabled', false).html('<i class="bi bi-person-x-fill"></i> Hapus Akun');
                        alert(res.message || 'Gagal menghapus akun.');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="bi bi-person-x-fill"></i> Hapus Akun');
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                    alert(msg);
                }
            });
        });
    });
});
</script>
