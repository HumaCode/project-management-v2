/* ── Password Toggle ─────────────────────────────────────── */
document.getElementById("togglePass").addEventListener("click", () => {
    const inp = document.getElementById("password");
    const ico = document.getElementById("eyeIcon");
    const show = inp.type === "password";
    inp.type = show ? "text" : "password";
    ico.className = show ? "bi bi-eye-slash" : "bi bi-eye";
});

document.getElementById("toggleConfirm").addEventListener("click", () => {
    const inp = document.getElementById("confirmPass");
    const ico = document.getElementById("eyeIconConfirm");
    const show = inp.type === "password";
    inp.type = show ? "text" : "password";
    ico.className = show ? "bi bi-eye-slash" : "bi bi-eye";
});

/* ── Nama Validation ─────────────────────────────────────── */
const namaInput = document.getElementById("nama");
const namaStatus = document.getElementById("namaStatus");
const namaMsg = document.getElementById("namaMsg");

namaInput.addEventListener("input", () => {
    const v = namaInput.value.trim();
    if (!v) {
        resetField(namaInput, namaStatus, namaMsg);
        return;
    }
    if (v.length < 3) {
        setInvalid(namaInput, namaStatus, namaMsg, "Nama minimal 3 karakter");
    } else if (!/^[a-zA-Z\s.']+$/.test(v)) {
        setInvalid(
            namaInput,
            namaStatus,
            namaMsg,
            "Nama hanya boleh huruf dan spasi",
        );
    } else {
        setValid(namaInput, namaStatus, namaMsg, "Nama valid");
    }
    updateSteps();
});

/* ── Username Validation ─────────────────────────────────── */
const usernameInput = document.getElementById("username");
const usernameMsg = document.getElementById("usernameMsg");
const emailMsg = document.getElementById("emailMsg");
const checkingSpin = document.getElementById("checkingSpin");
let usernameTimer;

// Fake unavailable usernames for demo
const takenNames = ["admin", "manager", "user", "test", "root", "superadmin"];

usernameInput.addEventListener("input", () => {
    const v = usernameInput.value.trim();
    clearTimeout(usernameTimer);
    checkingSpin.style.display = "none";
    resetField(usernameInput, null, usernameMsg);

    if (!v) return;

    if (v.length < 3) {
        setFieldMsg(usernameMsg, "Minimal 3 karakter", "error");
        usernameInput.classList.add("is-invalid");
        return;
    }
    if (!/^[a-zA-Z0-9_]+$/.test(v)) {
        setFieldMsg(
            usernameMsg,
            "Hanya huruf, angka, dan underscore (_)",
            "error",
        );
        usernameInput.classList.add("is-invalid");
        return;
    }
    if (v.length > 20) {
        setFieldMsg(usernameMsg, "Maksimal 20 karakter", "error");
        usernameInput.classList.add("is-invalid");
        return;
    }

    // Simulate availability check
    checkingSpin.style.display = "block";
    setFieldMsg(usernameMsg, "Mengecek ketersediaan...", "info");

    usernameTimer = setTimeout(() => {
        checkingSpin.style.display = "none";
        if (takenNames.includes(v.toLowerCase())) {
            usernameInput.classList.add("is-invalid");
            setFieldMsg(
                usernameMsg,
                `Username "${v}" sudah digunakan`,
                "error",
            );
        } else {
            usernameInput.classList.add("is-valid");
            setFieldMsg(usernameMsg, `@${v} tersedia`, "success");
        }
        updateSteps();
    }, 900);
});

/* ── Password Strength ───────────────────────────────────── */
const passInput = document.getElementById("password");
const strengthWrap = document.getElementById("strengthWrap");
const strengthFill = document.getElementById("strengthFill");
const strengthText = document.getElementById("strengthText");
const strengthPct = document.getElementById("strengthPct");
const passMsg = document.getElementById("passMsg");

const checks = {
    len: {
        el: document.getElementById("chkLen"),
        test: (v) => v.length >= 8,
    },
    upper: {
        el: document.getElementById("chkUpper"),
        test: (v) => /[A-Z]/.test(v),
    },
    num: {
        el: document.getElementById("chkNum"),
        test: (v) => /[0-9]/.test(v),
    },
    sym: {
        el: document.getElementById("chkSym"),
        test: (v) => /[!@#$%^&*()_+\-=\[\]{}|;':",./<>?]/.test(v),
    },
};

const levels = [
    {
        min: 0,
        label: "Sangat Lemah",
        color: "#ff4d6d",
        pct: 15,
    },
    {
        min: 1,
        label: "Lemah",
        color: "#ff7849",
        pct: 32,
    },
    {
        min: 2,
        label: "Cukup",
        color: "#f59e0b",
        pct: 55,
    },
    {
        min: 3,
        label: "Kuat",
        color: "#22d3ee",
        pct: 78,
    },
    {
        min: 4,
        label: "Sangat Kuat",
        color: "#00e5a0",
        pct: 100,
    },
];

passInput.addEventListener("input", () => {
    const v = passInput.value;
    if (!v) {
        strengthWrap.style.display = "none";
        resetField(passInput, null, passMsg);
        updateSteps();
        return;
    }

    strengthWrap.style.display = "block";

    let score = 0;
    Object.values(checks).forEach((c) => {
        const pass = c.test(v);
        c.el.classList.toggle("pass", pass);
        c.el.querySelector("i").className = pass
            ? "bi bi-check-circle-fill"
            : "bi bi-x-circle";
        if (pass) score++;
    });

    const lvl = levels[score];
    strengthFill.style.width = lvl.pct + "%";
    strengthFill.style.background = `linear-gradient(90deg, ${lvl.color}aa, ${lvl.color})`;
    strengthText.textContent = lvl.label;
    strengthText.style.color = lvl.color;
    strengthPct.textContent = lvl.pct + "%";

    passInput.classList.remove("is-valid", "is-invalid");
    if (score < 2) {
        passInput.classList.add("is-invalid");
    } else if (score >= 3) {
        passInput.classList.add("is-valid");
    }

    // re-check confirm
    checkConfirm();
    updateSteps();
});

/* ── Confirm Password ────────────────────────────────────── */
const confirmInput = document.getElementById("confirmPass");
const confirmStatus = document.getElementById("confirmStatus");
const confirmMsg = document.getElementById("confirmMsg");

function checkConfirm() {
    const pass = passInput.value;
    const conf = confirmInput.value;
    if (!conf) {
        resetField(confirmInput, confirmStatus, confirmMsg);
        return;
    }
    if (conf === pass) {
        setValid(confirmInput, confirmStatus, confirmMsg, "Password cocok");
        confirmStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
    } else {
        setInvalid(
            confirmInput,
            confirmStatus,
            confirmMsg,
            "Password tidak cocok",
        );
        confirmStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
    }
    updateSteps();
}

confirmInput.addEventListener("input", checkConfirm);

/* ── Step Indicator Update ───────────────────────────────── */
function updateSteps() {
    const namaOk = namaInput.classList.contains("is-valid");
    const userOk = usernameInput.classList.contains("is-valid");
    const passOk = passInput.classList.contains("is-valid");
    const confirmOk = confirmInput.classList.contains("is-valid");

    // Step 1: nama + username
    const s1 = document.getElementById("step1");
    const n1 = document.getElementById("stepNum1");
    if (namaOk && userOk) {
        s1.classList.remove("active");
        s1.classList.add("done");
        n1.innerHTML = '<i class="bi bi-check-lg" style="font-size:12px"></i>';
    } else {
        s1.classList.add("active");
        s1.classList.remove("done");
        n1.textContent = "1";
    }

    // Step 2: password
    const s2 = document.getElementById("step2");
    const n2 = document.getElementById("stepNum2");
    if (passOk) {
        s2.classList.remove("active");
        s2.classList.add("done");
        n2.innerHTML = '<i class="bi bi-check-lg" style="font-size:12px"></i>';
    } else if (namaOk && userOk) {
        s2.classList.add("active");
        s2.classList.remove("done");
        n2.textContent = "2";
    } else {
        s2.classList.remove("active", "done");
        n2.textContent = "2";
    }

    // Step 3: confirm
    const s3 = document.getElementById("step3");
    const n3 = document.getElementById("stepNum3");
    if (confirmOk) {
        s3.classList.remove("active");
        s3.classList.add("done");
        n3.innerHTML = '<i class="bi bi-check-lg" style="font-size:12px"></i>';
    } else if (passOk) {
        s3.classList.add("active");
        s3.classList.remove("done");
        n3.textContent = "3";
    } else {
        s3.classList.remove("active", "done");
        n3.textContent = "3";
    }
}

/* ── Helpers ─────────────────────────────────────────────── */
function setValid(input, statusEl, msgEl, msg) {
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    if (statusEl) {
        statusEl.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
        statusEl.style.color = "var(--success)";
    }
    if (msgEl) setFieldMsg(msgEl, msg, "success");
}

function setInvalid(input, statusEl, msgEl, msg) {
    input.classList.remove("is-valid");
    input.classList.add("is-invalid");
    if (statusEl) {
        statusEl.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
        statusEl.style.color = "var(--error)";
    }
    if (msgEl) setFieldMsg(msgEl, msg, "error");
}

function resetField(input, statusEl, msgEl) {
    input.classList.remove("is-valid", "is-invalid");
    if (statusEl) statusEl.innerHTML = "";
    if (msgEl) msgEl.innerHTML = "";
}

function setFieldMsg(el, text, type) {
    const icons = {
        success: "bi-check-circle",
        error: "bi-exclamation-circle",
        info: "bi-info-circle",
    };
    el.className = `field-msg ${type}`;
    el.innerHTML = text ? `<i class="bi ${icons[type] || ""}"></i>${text}` : "";
}

/* ── Form Submit ─────────────────────────────────────────── */
document
    .getElementById("registerForm")
    .addEventListener("submit", function (e) {
        e.preventDefault();

        const alert = document.getElementById("alertError");
        alert.style.display = "none";

        const nama = namaInput.value.trim();
        const user = usernameInput.value.trim();
        const pass = passInput.value;
        const conf = confirmInput.value;
        const terms = document.getElementById("terms").checked;

        // Validasi final
        if (!nama || !namaInput.classList.contains("is-valid")) {
            showAlert("Harap isi nama lengkap yang valid.");
            namaInput.focus();
            return;
        }
        if (!user || !usernameInput.classList.contains("is-valid")) {
            showAlert("Harap pilih username yang tersedia dan valid.");
            usernameInput.focus();
            return;
        }
        if (!pass || !passInput.classList.contains("is-valid")) {
            showAlert("Password belum memenuhi kriteria keamanan.");
            passInput.focus();
            return;
        }
        if (pass !== conf) {
            showAlert("Konfirmasi password tidak cocok.");
            confirmInput.focus();
            return;
        }
        if (!terms) {
            showAlert(
                "Anda harus menyetujui syarat & ketentuan terlebih dahulu.",
            );
            return;
        }

        const btn = document.getElementById("btnRegister");
        btn.classList.add("loading");
        btn.disabled = true;

        setTimeout(() => {
            btn.classList.remove("loading");
            btn.disabled = false;
            // Success state
            btn.style.background = "linear-gradient(135deg, #00e5a0, #0072c6)";
            btn.querySelector("span").innerHTML =
                '<i class="bi bi-check-lg"></i> Akun Berhasil Dibuat!';
            // Redirect: window.location.href = '/login';
            setTimeout(() => {
                btn.style.background = "";
                btn.querySelector("span").innerHTML =
                    '<i class="bi bi-person-plus-fill"></i> Buat Akun Sekarang';
            }, 2800);
        }, 2000);
    });
