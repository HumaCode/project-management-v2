$(document).ready(function () {
    const $form = $("#loginForm");
    const $btn = $("#btnLogin");
    const $alert = $("#alertError");
    const $alertMsg = $("#alertMsg");
    const $btnText = $btn.find("span");

    // Toggle Password
    $("#togglePass").on("click", function () {
        const input = $("#password");
        const icon = $("#eyeIcon");
        const isPass = input.attr("type") === "password";
        input.attr("type", isPass ? "text" : "password");
        icon.attr("class", isPass ? "bi bi-eye-slash" : "bi bi-eye");
    });

    // Sembunyikan error saat user mulai mengetik lagi
    $(".form-input").on("input", function () {
        if ($alert.is(":visible")) {
            $alert.fadeOut();
        }
    });

    // Submit via AJAX
    $form.on("submit", function (e) {
        e.preventDefault();

        // Reset UI
        $alert.hide();
        $btn.addClass("loading").prop("disabled", true);

        $.ajax({
            url: $form.data("url"),
            type: "POST",
            data: $form.serialize(),
            dataType: "json",
            success: function (response) {
                // Tampilan Sukses
                $btn.css(
                    "background",
                    "linear-gradient(135deg, #00e5a0, #0072c6)",
                );
                $btnText.html('<i class="bi bi-check-lg"></i> Berhasil!');

                setTimeout(() => {
                    window.location.href = response.redirect;
                }, 800);
            },
            error: function (xhr) {
                $btn.removeClass("loading").prop("disabled", false);

                let errorMsg = "Terjadi kesalahan fatal.";

                if (xhr.status === 422) {
                    // Ambil error pertama dari Laravel Validation
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors)[0][0];
                } else if (xhr.status === 419) {
                    errorMsg = "Sesi kadaluarsa, silakan refresh halaman.";
                } else if (xhr.status === 429) {
                    errorMsg = "Terlalu banyak percobaan. Coba lagi nanti.";
                }

                $alertMsg.text(errorMsg);
                $alert.css("display", "flex").hide().fadeIn();
            },
        });
    });
});
