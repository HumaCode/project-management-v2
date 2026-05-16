$(function () {
    // ══════════════════════════════════════════════
    // NAVIGATION & PANE LOGIC
    // ══════════════════════════════════════════════

    window.showPane = function (id) {
        $('.cat-grid').fadeOut(200, function () {
            $('#' + id).addClass('active').fadeIn(300);
        });
    };

    $('.back-bar').on('click', function () {
        let pane = $(this).closest('.detail-pane');
        pane.fadeOut(200, function () {
            pane.removeClass('active');
            $('.cat-grid').fadeIn(300);
        });
    });

    // ══════════════════════════════════════════════
    // SYSTEM PROFILE AJAX
    // ══════════════════════════════════════════════

    $('#formProfile').on('submit', function (e) {
        e.preventDefault();
        const btn = $('#btnSaveProfile');
        const originalHtml = btn.html();

        // Loading state
        btn.prop('disabled', true).html('<span><i class="bi bi-arrow-repeat spin"></i> Menyimpan...</span>');

        const formData = new FormData(this);

        $.ajax({
            url: "/settings/profile",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === 'success') {
                    SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                }
            },
            error: function (err) {
                let msg = "Terjadi kesalahan sistem";
                if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                SCA.toast({ type: 'danger', title: 'Error', message: msg });
            },
            complete: function () {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Image Preview
    $('#logoUpload').on('change', function () {
        previewFile(this, '#previewLogo', '#nameLogo');
    });

    $('#favUpload').on('change', function () {
        previewFile(this, '#previewFav', '#nameFav');
    });

    function previewFile(input, imgSelector, nameSelector) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $(imgSelector).attr('src', e.target.result).show();
                // Sembunyikan ikon pendamping jika ada
                if (imgSelector === '#previewLogo') $('#iconLogo').hide();
                if (imgSelector === '#previewFav') $('#iconFav').hide();
            }
            reader.readAsDataURL(input.files[0]);
            $(nameSelector).text(input.files[0].name);
        }
    }

    // ══════════════════════════════════════════════
    // UI HELPERS
    // ══════════════════════════════════════════════

    $('.pw-eye').on('click', function () {
        const input = $(this).parent().find('input');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
        }
    });

    // Simulation for other buttons (UI Feedback)
    $('.btn-save:not(#btnSaveProfile)').on('click', function () {
        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<span><i class="bi bi-arrow-repeat spin"></i> Menyimpan...</span>');
        setTimeout(() => {
            btn.prop('disabled', false).html(originalHtml);
            SCA.toast({ type: 'success', title: 'Berhasil', message: 'Pengaturan berhasil diperbarui' });
        }, 1200);
    });
});
