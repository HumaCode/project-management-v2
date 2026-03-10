/* ─── Password Toggle ──────────────────────────────────── */
document.getElementById("togglePass").addEventListener("click", function () {
    const inp = document.getElementById("password");
    const ico = document.getElementById("eyeIcon");
    const isPass = inp.type === "password";
    inp.type = isPass ? "text" : "password";
    ico.className = isPass ? "bi bi-eye-slash" : "bi bi-eye";
});

/* ─── Form Submit (demo) ───────────────────────────────── */
document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const btn = document.getElementById("btnLogin");
    const alert = document.getElementById("alertError");

    alert.style.display = "none";
    btn.classList.add("loading");

    // Simulate API call
    setTimeout(() => {
        btn.classList.remove("loading");
        const email = document.getElementById("email").value;
        const pass = document.getElementById("password").value;

        if (!email || !pass) {
            document.getElementById("alertMsg").textContent =
                "Harap isi semua field.";
            alert.style.display = "flex";
            return;
        }

        // Demo: show success flash then redirect (uncomment for real use)
        // window.location.href = '/dashboard';
        btn.style.background = "linear-gradient(135deg, #00e5a0, #0072c6)";
        btn.querySelector("span").innerHTML =
            '<i class="bi bi-check-lg"></i> Berhasil!';
        setTimeout(() => {
            btn.style.background = "";
            btn.querySelector("span").innerHTML =
                '<i class="bi bi-box-arrow-in-right"></i> Masuk Sekarang';
        }, 2200);
    }, 1800);
});
