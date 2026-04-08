/* ============================================================
   MODAL AJAX - FIXED VERSION
============================================================ */

let modalRequest = null;
let currentModalInstance = null;
let loadingInstance = null; // ✅ simpan instance loading

function showLoadingModal(show) {
    if (show) {
        $("#loadingModal").css("display", "flex").hide().fadeIn(200);
    } else {
        $("#loadingModal").fadeOut(200);
    }
}

function openModal(url) {
    const $modalContainer = $("#modal");
    if (!$modalContainer.length) {
        console.error("Modal container #modal not found");
        return;
    }

    if (modalRequest) {
        modalRequest.abort();
        modalRequest = null;
    }

    cleanupModal();

    showLoading(true, {
        title: "Memuat...",
        message: "Sedang mengambil meload modal",
    });

    modalRequest = $.ajax({
        url: url,
        method: "GET",

        success(res) {
            showLoading(false);

            $modalContainer.html(res);

            const modalEl = $modalContainer.find(".modal")[0];
            if (!modalEl) {
                console.error("Modal element not found in response");
                return;
            }

            currentModalInstance = new bootstrap.Modal(modalEl, {
                backdrop: true,
                keyboard: true,
                focus: true,
            });

            currentModalInstance.show();

            $(modalEl).one("shown.bs.modal", function () {
                initializeSelect2();
                $(document).trigger("modal:loaded");
            });
        },

        error(xhr) {
            showLoading(false);

            if (xhr.statusText !== "abort") {
                showToast("error", "Failed to load modal content");
            }
        },

        complete() {
            modalRequest = null;
        },
    });
}

function initializeSelect2() {
    // Cari modal element yang aktif
    const $modalEl = $("#modal").find(".modal");

    // Cari semua select di dalam modal
    const $selects = $("#modal").find("select");

    $selects.each(function () {
        const $this = $(this);

        if ($this.data("select2")) {
            $this.select2("destroy");
        }

        $this.select2({
            dropdownParent: $modalEl,
            placeholder:
                $this.attr("placeholder") ||
                $this.find("option:first").text() ||
                "Pilih opsi",

            /* --- PERUBAHAN DI SINI --- */
            // Hapus atau set ke angka positif (misal: 0 agar pencarian selalu muncul)
            minimumResultsForSearch: 0,

            allowClear: true, // Sebaiknya true agar user bisa reset pilihan
            width: "100%",
        });
    });
}

function closeModal() {
    if (currentModalInstance) {
        currentModalInstance.hide();
    }
}

function cleanupModal() {
    const $modalContainer = $("#modal");

    if (currentModalInstance) {
        currentModalInstance.dispose();
        currentModalInstance = null;
    }

    // Destroy semua Select2 instances di dalam modal
    $("#modal")
        .find("select")
        .each(function () {
            if ($(this).data("select2")) {
                $(this).select2("destroy");
            }
        });

    $modalContainer.html("");
    $(".modal-backdrop").remove();

    $("body").removeClass("modal-open");
    $("body").css({
        overflow: "",
        "padding-right": "",
    });
}

$(document).on("hidden.bs.modal", "#modal .modal", function () {
    cleanupModal();
});

/* ============================================================
   FORM SUBMIT HANDLER
============================================================ */

function handleFormSubmit(formSelector) {
    let dataTables = [];
    let onSuccessCallback = null;

    function setDataTable(ids) {
        dataTables = Array.isArray(ids) ? ids : [ids];
        return api;
    }

    function onSuccess(cb) {
        onSuccessCallback = cb;
        return api;
    }

    function init() {
        $(document)
            .off("submit", formSelector)
            .on("submit", formSelector, function (e) {
                e.preventDefault();
                const form = this;

                const submitBtn = $(form).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.prop("disabled", true);
                submitBtn.html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Loading...',
                );

                showLoading(true, {
                    title: "Memproses....",
                    message: "Mohon tunggu sebentar",
                });

                setTimeout(() => {
                    submitAjax(form, submitBtn, originalText);
                }, 300);
            });

        return api;
    }

    function submitAjax(form, submitBtn, originalText) {
        $.ajax({
            url: form.action,
            method: form.method || "POST",
            data: new FormData(form),
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            beforeSend() {
                $(form).find(".is-invalid").removeClass("is-invalid");
                $(form).find(".invalid-feedback").remove();
            },

            success(res) {
                showLoading(false);

                SCA.toast({
                    type: "success",
                    title: "Berhasil!",
                    message: res.message ?? "Berhasil diproses.",
                });

                closeModal();

                if (typeof loadData === "function") {
                    setTimeout(() => loadData(), 500);
                } else {
                    setTimeout(() => location.reload(), 1000);
                }

                if (onSuccessCallback) {
                    onSuccessCallback(res);
                }
            },

            error(xhr) {
                let errorMessage = "Terjadi kesalahan.";

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    // Menggabungkan pesan error dengan karakter Newline
                    errorMessage = Object.values(xhr.responseJSON.errors)
                        .map((e) => e[0])
                        .join("\n");
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showLoading(false);

                SCA.dialog({
                    type: "danger",
                    title: "Uupsss!",
                    message: errorMessage,
                    confirmText: "OK",
                    showCancel: false,
                });
            },
            complete() {
                submitBtn.prop("disabled", false);
                submitBtn.html(originalText);
            },
        });
    }

    const api = { init, setDataTable, onSuccess };
    return api;
}

/* ============================================================
   ACTION HANDLER (TIDAK DIUBAH)
============================================================ */

function handleAction(datatableId, onShow) {
    $(document).on("click", ".action", function (e) {
        e.preventDefault();

        const url = this.href;
        if (!url) return;

        openModal(url);

        $(document).one("modal:loaded", function () {
            if (onShow) onShow();
            handleFormSubmit("#form_action").setDataTable(datatableId).init();
        });
    });
}

/* ============================================================
   DELETE HANDLER
============================================================ */

function handleDelete(dataTableId, onSuccess) {
    // Gunakan .off() sebelum .on() untuk mencegah event menumpuk (double trigger)
    $(document)
        .off("click", ".delete")
        .on("click", ".delete", function (e) {
            e.preventDefault();

            const url = $(this).attr("href");
            if (!url) return;

            SCA.dialog({
                type: "danger",
                title: "Hapus Data?",
                message: "Data tidak dapat dikembalikan.",
                confirmText: "Ya, Hapus",
                cancelText: "Batal",
                showCancel: true,
            }).then((confirmed) => {
                // Jika user batal
                if (!confirmed) return;

                $.ajax({
                    url: url,
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },

                    beforeSend() {
                        showLoading(true, {
                            title: "Menghapus...",
                            message: "Sedang menghapus data",
                        });
                    },

                    success(res) {
                        showLoading(false);

                        SCA.toast({
                            type: res.success ? "success" : "danger",
                            title: res.success ? "Berhasil!" : "Gagal!",
                            message: res.message ?? "Data berhasil dihapus.",
                        });

                        // Cek sukses/gagal dari response
                        if (res.success) {
                            if (typeof loadData === "function") {
                                loadData(); // Jika ini memanggil handleDelete() lagi, .off() di atas akan mengamankannya
                            } else {
                                setTimeout(() => location.reload(), 1000);
                            }

                            if (onSuccess) {
                                onSuccess(res);
                            }
                        }
                    },

                    error(xhr) {
                        showLoading(false);

                        SCA.toast({
                            type: "danger",
                            title: "Gagal!",
                            message:
                                xhr.responseJSON?.message ??
                                "Gagal menghapus data.",
                        });
                    },
                });
            });
        });
}

/* ============================================================
   UTILITIES (FIXED)
============================================================ */

function showLoading(show, options = {}) {
    if (show) {
        SCA.loading({
            title: options.title || "Memproses...",
            message: options.message || "Mohon tunggu sebentar",
        });
    } else {
        // 1. Panggil fungsi close bawaan
        SCA.close();

        // 2. Sapu bersih semua overlay yang mungkin masih menyangkut di layar
        document.querySelectorAll(".sca-overlay").forEach(function (ov) {
            // Tambahkan class animasi keluar
            ov.classList.add("sca-out");

            // Hapus elemen dari HTML setelah animasi selesai (220ms)
            setTimeout(function () {
                if (ov.parentNode) ov.parentNode.removeChild(ov);
            }, 220);
        });

        // 3. Pastikan scroll halaman dikembalikan ke normal
        document.body.style.overflow = "";
    }
}

function showToast(status = "success", message = "") {
    SCA.toast({
        type: status,
        title:
            status === "success"
                ? "Berhasil"
                : status === "error"
                  ? "Gagal"
                  : status === "warning"
                    ? "Peringatan"
                    : "Informasi",
        message: message,
    });
}

function loadData() {
    location.reload();
}

//  fungsi js user
function approveUser(id) {
    // 1. Tampilkan Popup Konfirmasi
    SCA.confirm(
        "Aktifkan Pengguna?",
        "Akun pengguna ini akan diaktifkan dan bisa mengakses sistem.",
    ).then(function (isConfirmed) {
        // 2. Jika user menekan "Ya" / Konfirmasi
        if (isConfirmed) {
            // Opsional: Tampilkan loading atau disable tombol di sini jika diperlukan

            // 3. Eksekusi AJAX
            $.ajax({
                url: `/users/${id}/approve`, // Sesuaikan dengan route PUT/POST kamu
                type: "PUT", // Gunakan PUT atau POST sesuai definisi route
                headers: {
                    // Mengambil CSRF token dari tag meta HTML
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
                success: function (res) {
                    // Jika dari controller me-return jsonResponse(true, ...)
                    if (res.success) {
                        SCA.toast({
                            type: "success",
                            title: "Berhasil!",
                            message:
                                res.message || "Pengguna berhasil diaktifkan.",
                            position: "top-right",
                        });

                        // 4. Refresh data tabel (karena status berubah)
                        // Jika kamu menggunakan custom-table.js dari obrolan sebelumnya:
                        if (typeof window.loadData === "function") {
                            window.loadData();
                        }
                    } else {
                        // Jika controller me-return success = false
                        SCA.toast({
                            type: "error",
                            title: "Gagal!",
                            message:
                                res.message || "Gagal mengaktifkan pengguna.",
                            position: "top-right",
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Terjadi kesalahan sistem.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    SCA.toast({
                        type: "error",
                        title: "Error!",
                        message: errorMessage,
                        position: "top-right",
                    });
                },
            });
        }
    });
}

function rejectUser(id) {
    // 1. Tampilkan Popup Konfirmasi
    SCA.confirm(
        "Nonaktifkan Pengguna?",
        "Akun pengguna ini akan dinonaktifkan dan tidak bisa mengakses sistem.",
    ).then(function (isConfirmed) {
        // 2. Jika user menekan "Ya" / Konfirmasi
        if (isConfirmed) {
            // Opsional: Tampilkan loading atau disable tombol di sini jika diperlukan

            // 3. Eksekusi AJAX
            $.ajax({
                url: `/users/${id}/reject`, // Pastikan route ini sudah kamu buat
                type: "PUT", // Gunakan PUT atau POST sesuai definisi route
                headers: {
                    // Mengambil CSRF token dari tag meta HTML
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
                success: function (res) {
                    // Jika dari controller me-return jsonResponse(true, ...)
                    if (res.success) {
                        SCA.toast({
                            type: "success",
                            title: "Berhasil!",
                            message:
                                res.message ||
                                "Pengguna berhasil dinonaktifkan.",
                            position: "top-right",
                        });

                        // 4. Refresh data tabel (karena status berubah)
                        if (typeof window.loadData === "function") {
                            window.loadData();
                        }
                    } else {
                        // Jika controller me-return success = false
                        SCA.toast({
                            type: "error",
                            title: "Gagal!",
                            message:
                                res.message || "Gagal menonaktifkan pengguna.",
                            position: "top-right",
                        });
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Terjadi kesalahan sistem.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    SCA.toast({
                        type: "error",
                        title: "Error!",
                        message: errorMessage,
                        position: "top-right",
                    });
                },
            });
        }
    });
}

function resetPassword(id, name, email) {
    SCA.resetPassword({
        userName: name,
        userEmail: email,
    }).then(function (r) {
        if (r && r.confirmed) {
            $.ajax({
                url: `/users/${id}/reset-password`,
                type: "POST", // ✅ UBAH JADI POST DI SINI
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
                data: {
                    _method: "PUT",
                    mode: r.mode,
                    newPassword: r.newPassword,
                    newPassword_confirmation: r.newPassword_confirmation,
                },
                success: function (res) {
                    // Menggunakan format toast tunggal yang kamu minta
                    SCA.toast({
                        type: res.success ? "success" : "danger", // Pastikan classnya 'danger' atau 'error' sesuai CSS SCA kamu
                        title: res.success ? "Berhasil!" : "Gagal!",
                        // Menggunakan res.message dari Controller, fallback ke pesan default jika kosong
                        message:
                            res.message ??
                            (res.success
                                ? "Password berhasil diperbarui."
                                : "Gagal memproses permintaan."),
                    });

                    if (res.success) {
                        if (typeof loadData === "function") {
                            loadData(); // Jika ini memanggil handleDelete() lagi, .off() di atas akan mengamankannya
                        } else {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Terjadi kesalahan sistem.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    SCA.toast({
                        type: "danger",
                        title: "Error!",
                        message: errorMessage,
                    });
                },
            });
        }
    });
}
