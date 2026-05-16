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
        saveSettings(this, '#btnSaveProfile', "/settings/profile");
    });

    $('#formSecurity').on('submit', function (e) {
        e.preventDefault();
        saveSettings(this, '#btnSaveSecurity', "/settings/security");
    });

    $('#formEmail').on('submit', function (e) {
        e.preventDefault();
        saveSettings(this, '#btnSaveEmail', "/settings/email");
    });

    $('#btnTestMail').on('click', function () {
        const btn = $(this);
        const originalHtml = btn.html();
        const recipient = $('#testEmailRecipient').val();

        if (!recipient) {
            SCA.toast({ type: 'warning', title: 'Peringatan', message: 'Masukkan email penerima terlebih dahulu' });
            return;
        }

        // Ambil data dari form SMTP
        const formData = new FormData($('#formEmail')[0]);
        formData.append('email', recipient);

        btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Mengirim...');

        $.ajax({
            url: "/settings/email/test",
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
                let msg = "Gagal mengirim email test";
                if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                SCA.toast({ type: 'danger', title: 'Error', message: msg });
            },
            complete: function () {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    $('#formMaintenance').on('submit', function (e) {
        e.preventDefault();
        saveSettings(this, '#btnSaveMaintenance', "/settings/maintenance");
    });

    function saveSettings(form, btnSelector, url) {
        const btn = $(btnSelector);
        const originalHtml = btn.html();

        // Loading state
        btn.prop('disabled', true).html('<span><i class="bi bi-arrow-repeat spin"></i> Menyimpan...</span>');

        const formData = new FormData(form);

        $.ajax({
            url: url,
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
    }

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

    // ══════════════════════════════════════════════
    // INITIALIZATIONS
    // ══════════════════════════════════════════════
    if (typeof flatpickr !== 'undefined') {
        $(".datetimepicker").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            locale: "id"
        });
    }

    // Clear Cache Logic
    $('#btnClearCache').on('click', function () {
        const btn = $(this);
        const url = btn.data('url');
        const originalHtml = btn.html();

        console.log('Clear cache clicked. URL:', url);

        SCA.confirm(
            'Bersihkan Cache?',
            'Tindakan ini akan menghapus seluruh cache aplikasi, view, dan konfigurasi. Aplikasi mungkin terasa sedikit lambat pada request pertama setelah ini.',
            {
                confirmText: 'Ya, Bersihkan',
                confirmButtonClass: 'btn-danger',
                onConfirm: function () {
                    console.log('Confirmation accepted. Sending AJAX to:', url);
                    btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Memproses...');

                    $.ajax({
                        url: url,
                        method: "GET",
                        success: function (res) {
                            console.log('AJAX Success:', res);
                            if (res.status === 'success') {
                                SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                            }
                        },
                        error: function (err) {
                            console.error('AJAX Error:', err);
                            let msg = "Gagal membersihkan cache";
                            if (err.responseJSON && err.responseJSON.message) msg = err.responseJSON.message;
                            SCA.toast({ type: 'danger', title: 'Error', message: msg });
                        },
                        complete: function () {
                            btn.prop('disabled', false).html(originalHtml);
                        }
                    });
                }
            }
        );
    });
    
    // Load Backup History
    function loadBackupHistory() {
        $.ajax({
            url: "/settings/backups/history",
            method: "GET",
            success: function (html) {
                $('#backupHistoryContainer').html(html);
                
                // Update file count badge in header
                const count = $(html).find('.backup-card').length;
                $('.det-hd-badge').text(count + ' file');
            }
        });
    }

    // Save Backup Settings
    $('#formBackupSetting').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const btn = $('#btnSaveBackupSetting');
        const originalHtml = btn.html();

        btn.prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Menyimpan...');

        $.ajax({
            url: "/settings/backups/settings",
            method: "POST",
            data: form.serialize(),
            success: function (res) {
                if (res.status === 'success') {
                    SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                }
            },
            error: function (err) {
                SCA.toast({ type: 'danger', title: 'Error', message: 'Gagal menyimpan jadwal backup' });
            },
            complete: function () {
                btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Manual Backup
    $('#btnRunBackupManual').on('click', function () {
        const url = $(this).data('url');
        const $btn = $(this);

        SCA.confirm(
            "Konfirmasi Backup",
            "Apakah Anda yakin ingin melakukan backup database sekarang? Proses ini mungkin memakan waktu beberapa menit."
        ).then((confirmed) => {
            if (confirmed) {
                // Disable button and show loading
                $btn.prop('disabled', true).addClass('opacity-50');
                
                SCA.loading({
                    title: "Memproses Backup",
                    message: "Sedang mencadangkan database, mohon tunggu..."
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        SCA.close();
                        if (response.status === 'success') {
                            SCA.toast({ type: 'success', title: 'Berhasil', message: response.message });
                            loadBackupHistory();
                        } else {
                            SCA.toast({ type: 'danger', title: 'Gagal', message: response.message });
                        }
                    },
                    error: function(xhr) {
                        SCA.close();
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan saat memproses backup";
                        SCA.toast({ type: 'danger', title: 'Error', message: msg });
                    },
                    complete: function() {
                        // Re-enable button
                        $btn.prop('disabled', false).removeClass('opacity-50');
                    }
                });
            }
        });
    });

    // Delete Backup
    $(document).on('click', '.btnDeleteBackup', function () {
        const btn = $(this);
        const url = btn.data('url');
        const filename = btn.data('filename');

        SCA.confirm(
            'Hapus Backup?',
            `Anda akan menghapus file backup <strong>${filename}</strong> secara permanen.`
        ).then(function (isConfirmed) {
            if (isConfirmed) {
                SCA.loading({ title: "Menghapus Backup", message: "Sedang menghapus file dari storage..." });
                $.ajax({
                    url: url,
                    method: "DELETE",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        SCA.close();
                        if (res.status === 'success') {
                            SCA.toast({ type: 'success', title: 'Berhasil', message: res.message });
                            loadBackupHistory();
                        }
                    },
                    error: function (err) {
                        SCA.close();
                        SCA.toast({ type: 'danger', title: 'Error', message: 'Gagal menghapus file backup' });
                    },
                    complete: function () {
                        // SCA.close() already called in success/error
                    }
                });
            }
        });
    });

    // Load System Activities
    function loadSystemActivities(page = 1) {
        const container = $('#systemActivitiesContainer');
        const url = container.data('url');

        if (!url) return;

        // Get filter values
        const filters = {
            page: page,
            date: $('#filterDate').val(),
            event: $('#filterEvent').val(),
            module: $('#filterModule').val()
        };

        container.css('opacity', '0.5');

        $.ajax({
            url: url,
            type: 'GET',
            data: filters,
            success: function(response) {
                if (response.status === 'success') {
                    container.html(response.html);
                    if (typeof AOS !== 'undefined') AOS.refresh();
                }
            },
            error: function(xhr) {
                container.html('<div class="text-center py-5 text-danger">Gagal memuat log aktivitas</div>');
            },
            complete: function() {
                container.css('opacity', '1');
            }
        });
    }

    // Toggle Filter Bar
    $(document).on('click', '#btnToggleFilter', function() {
        $('#exportBar').hide();
        $('#filterBar').slideToggle(300);
    });

    // Toggle Export Bar
    $(document).on('click', '#btnExportLog', function() {
        $('#filterBar').hide();
        $('#exportBar').slideToggle(300);
    });

    // Handle Download Excel
    $(document).on('click', '#btnDownloadExcel', function() {
        const baseUrl = $('#btnExportLog').data('url');
        const date = $('#exportDate').val();
        
        if (!baseUrl) {
            console.error('Export URL not found');
            return;
        }

        const exportUrl = new URL(baseUrl, window.location.origin);
        if (date) exportUrl.searchParams.append('date', date);
        
        window.location.href = exportUrl.toString();
    });

    // Handle Filter Changes
    $('#filterEvent, #filterModule').on('change', function() {
        loadSystemActivities();
    });

    // Reset Filter
    $('#btnResetFilter').on('click', function() {
        $('#filterDate').val('');
        $('#filterEvent').val('');
        $('#filterModule').val('');
        if ($('#filterDate').data('flatpickr')) {
            $('#filterDate').data('flatpickr').clear();
        }
        loadSystemActivities();
    });

    // Initialize Flatpickr for Filter
    if ($('#filterDate').length) {
        const fp = flatpickr("#filterDate", {
            mode: "range",
            dateFormat: "Y-m-d",
            conjunction: " to ",
            locale: "id",
            onChange: function(selectedDates, dateStr) {
                if (selectedDates.length === 2 || selectedDates.length === 0) {
                    loadSystemActivities();
                }
            }
        });
        $('#filterDate').data('flatpickr', fp);
    }

    // Initialize Flatpickr for Export
    if ($('#exportDate').length) {
        flatpickr("#exportDate", {
            mode: "range",
            dateFormat: "Y-m-d",
            conjunction: " to ",
            locale: "id"
        });
    }

    // Handle Activity Pagination
    $(document).on('click', '#systemActivitiesContainer .pagination a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        loadSystemActivities(page);
    });

    // Initial Load
    if ($('#systemActivitiesContainer').length) {
        loadSystemActivities();
    }
});
