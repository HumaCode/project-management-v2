$(document).ready(function () {
    const $form = $("#loginForm");
    const $btn = $("#btnLogin");
    const $alert = $("#alertError");
    const $alertMsg = $("#alertMsg");
    const $btnText = $btn.find("span");

    /* ── Toggle Password ── */
    $("#togglePass").on("click", function () {
        const input = $("#password");
        const icon = $("#eyeIcon");
        const isPass = input.attr("type") === "password";
        input.attr("type", isPass ? "text" : "password");
        icon.attr("class", isPass ? "bi bi-eye-slash" : "bi bi-eye");
    });

    $(".form-input").on("input", function () {
        if ($alert.is(":visible")) $alert.fadeOut();
    });

    /* ── Standard Login AJAX ── */
    $form.on("submit", function (e) {
        e.preventDefault();
        $alert.hide();
        $btn.addClass("loading").prop("disabled", true);

        const siteKey = $('meta[name="recaptcha-site-key"]').attr('content');
        if (siteKey && typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, {action: 'login'}).then(function(token) {
                    submitLoginForm(token);
                }).catch(function(err) {
                    $btn.removeClass("loading").prop("disabled", false);
                    $alertMsg.text("reCAPTCHA Error: " + err.message);
                    $alert.css("display", "flex").hide().fadeIn();
                });
            });
        } else {
            submitLoginForm(null);
        }
    });

    function submitLoginForm(recaptchaToken) {
        let formData = $form.serialize();
        if (recaptchaToken) {
            formData += '&g-recaptcha-response=' + encodeURIComponent(recaptchaToken);
        }

        $.ajax({
            url: $form.data("url"),
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                $btn.css("background", "linear-gradient(135deg, #00e5a0, #0072c6)");
                $btnText.html('<i class="bi bi-check-lg"></i> Berhasil!');
                setTimeout(() => { window.location.href = response.redirect; }, 800);
            },
            error: function (xhr) {
                $btn.removeClass("loading").prop("disabled", false);
                let errorMsg = getErrorMessage(xhr);
                $alertMsg.text(errorMsg);
                $alert.css("display", "flex").hide().fadeIn();
            },
        });
    }

    /* ── OTP LOGIN LOGIC ── */
    const $loginStandard = $("#loginStandard");
    const $loginOtp = $("#loginOtp");
    const $otpStepEmail = $("#otpStepEmail");
    const $otpStepCode = $("#otpStepCode");
    let countdownTimer;

    $("#btnOtpToggle").on("click", function() {
        $loginStandard.fadeOut(300, () => $loginOtp.fadeIn(300));
    });

    $("#btnBackLogin").on("click", function() {
        $loginOtp.fadeOut(300, () => $loginStandard.fadeIn(300));
    });

    /* Step 1: Send OTP */
    $("#btnSendOtp").on("click", function() {
        const email = $("#otpEmail").val().trim();
        const $btn = $(this);
        const $msg = $("#otpEmailMsg");

        if (!email) { setMsg($msg, "Email wajib diisi.", "error"); return; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setMsg($msg, "Format email tidak valid.", "error"); return; }

        $btn.addClass("loading").prop("disabled", true);
        $msg.hide();

        const siteKey = $('meta[name="recaptcha-site-key"]').attr('content');
        if (siteKey && typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, {action: 'send_otp'}).then(function(token) {
                    submitSendOtp(email, token);
                }).catch(function(err) {
                    $btn.removeClass("loading").prop("disabled", false);
                    setMsg($msg, "reCAPTCHA Error: " + err.message, "error");
                });
            });
        } else {
            submitSendOtp(email, null);
        }
    });

    function submitSendOtp(email, recaptchaToken) {
        const $btn = $("#btnSendOtp");
        const $msg = $("#otpEmailMsg");
        const sendData = { 
            email: email, 
            _token: $("input[name='_token']").val() 
        };
        if (recaptchaToken) {
            sendData['g-recaptcha-response'] = recaptchaToken;
        }

        $.ajax({
            url: "/login/otp/send",
            type: "POST",
            data: sendData,
            success: function(response) {
                $btn.removeClass("loading").prop("disabled", false);
                $("#btnResendOtp").removeClass("loading");
                
                $("#displayOtpEmail").text(email);
                $otpStepEmail.fadeOut(300, () => {
                    $otpStepCode.fadeIn(300);
                    startCountdown();
                    $("#o1").focus();
                });
            },
            error: function(xhr) {
                $btn.removeClass("loading").prop("disabled", false);
                $("#btnResendOtp").removeClass("loading").prop("disabled", false);
                setMsg($msg, getErrorMessage(xhr), "error");
            }
        });
    }

    /* OTP Box Auto-tab */
    $(".otp-box").on("input", function() {
        const $this = $(this);
        const val = $this.val().replace(/\D/g, "");
        $this.val(val);
        if (val) {
            $this.addClass("filled");
            $this.next(".otp-box").focus();
        } else {
            $this.removeClass("filled");
        }
        checkVerifyBtnState();
    }).on("keydown", function(e) {
        if (e.key === "Backspace" && !$(this).val()) {
            $(this).prev(".otp-box").focus();
        }
    });

    /* Verify OTP */
    $("#btnVerifyOtp").on("click", function() {
        let code = "";
        $(".otp-box").each(function() { code += $(this).val(); });
        const email = $("#otpEmail").val();
        const $btn = $(this);
        const $msg = $("#otpCodeMsg");

        if (code.length < 6) return;

        $btn.addClass("loading").prop("disabled", true);
        $msg.hide();

        const siteKey = $('meta[name="recaptcha-site-key"]').attr('content');
        if (siteKey && typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, {action: 'verify_otp'}).then(function(token) {
                    submitVerifyOtp(email, code, token);
                }).catch(function(err) {
                    $btn.removeClass("loading").prop("disabled", false);
                    setMsg($msg, "reCAPTCHA Error: " + err.message, "error");
                });
            });
        } else {
            submitVerifyOtp(email, code, null);
        }
    });

    function submitVerifyOtp(email, code, recaptchaToken) {
        const $btn = $("#btnVerifyOtp");
        const $msg = $("#otpCodeMsg");
        const verifyData = { 
            email: email, 
            code: code, 
            _token: $("input[name='_token']").val() 
        };
        if (recaptchaToken) {
            verifyData['g-recaptcha-response'] = recaptchaToken;
        }

        $.ajax({
            url: "/login/otp/verify",
            type: "POST",
            data: verifyData,
            success: function(response) {
                $btn.css("background", "linear-gradient(135deg, #00e5a0, #0072c6)");
                $btn.find("span").html('<i class="bi bi-check-lg"></i> Berhasil!');
                setTimeout(() => { window.location.href = response.redirect; }, 800);
            },
            error: function(xhr) {
                $btn.removeClass("loading").prop("disabled", false);
                setMsg($msg, getErrorMessage(xhr), "error");
                $(".otp-box").addClass("error-shake");
                setTimeout(() => $(".otp-box").removeClass("error-shake"), 500);
            }
        });
    }

    /* Timer Logic */
    function startCountdown() {
        let timeLeft = 120;
        const $timer = $("#otpTimer");
        const $btn = $("#btnResendOtp");
        
        $btn.prop("disabled", true);
        clearInterval(countdownTimer);

        countdownTimer = setInterval(() => {
            const min = Math.floor(timeLeft / 60);
            const sec = timeLeft % 60;
            $timer.text(`${min.toString().padStart(2, "0")}:${sec.toString().padStart(2, "0")}`);
            
            if (--timeLeft < 0) {
                clearInterval(countdownTimer);
                $btn.prop("disabled", false);
            }
        }, 1000);
    }

    $("#btnResendOtp").on("click", function() {
        $(this).addClass("loading").prop("disabled", true);
        $("#btnSendOtp").click();
    });

    /* Helpers */
    function getErrorMessage(xhr) {
        if (xhr.status === 422) return Object.values(xhr.responseJSON.errors)[0][0];
        if (xhr.status === 419) return "Sesi kadaluarsa, silakan refresh.";
        if (xhr.status === 429) return "Terlalu banyak percobaan.";
        return xhr.responseJSON?.message || "Terjadi kesalahan.";
    }

    function setMsg($el, text, type) {
        $el.removeClass("error success").addClass(type).html(`<i class="bi bi-${type==='error'?'exclamation-circle':'check-circle'}"></i> ${text}`).fadeIn();
    }

    function checkVerifyBtnState() {
        let code = "";
        $(".otp-box").each(function() { code += $(this).val(); });
        // Optional: auto trigger verify if 6 digits filled
        if (code.length === 6) {
            // $("#btnVerifyOtp").click();
        }
    }
});
