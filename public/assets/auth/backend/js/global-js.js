/* ── AOS ── */
AOS.init({
    once: true,
    easing: "ease-out-cubic",
    duration: 600,
});

/* ── Background canvas ── */
(function () {
    const canvas = document.getElementById("bgc");
    const ctx = canvas.getContext("2d");
    let W,
        H,
        nodes = [];
    const COLORS = ["rgba(0,200,255,", "rgba(0,114,198,", "rgba(0,229,160,"];

    function init() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
        nodes = [];
        const n = Math.max(20, Math.floor((W * H) / 28000));
        for (let i = 0; i < n; i++) {
            nodes.push({
                x: Math.random() * W,
                y: Math.random() * H,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
                r: Math.random() * 2 + 1,
                c: COLORS[Math.floor(Math.random() * COLORS.length)],
                p: Math.random() * Math.PI * 2,
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        nodes.forEach((n) => {
            n.x += n.vx;
            n.y += n.vy;
            n.p += 0.015;
            if (n.x < -10) n.x = W + 10;
            if (n.x > W + 10) n.x = -10;
            if (n.y < -10) n.y = H + 10;
            if (n.y > H + 10) n.y = -10;
        });
        for (let i = 0; i < nodes.length; i++)
            for (let j = i + 1; j < nodes.length; j++) {
                const a = nodes[i],
                    b = nodes[j],
                    dx = a.x - b.x,
                    dy = a.y - b.y,
                    d = Math.sqrt(dx * dx + dy * dy);
                if (d < 140) {
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.strokeStyle = `rgba(0,140,200,${(1 - d / 140) * 0.15})`;
                    ctx.lineWidth = 0.7;
                    ctx.stroke();
                }
            }
        nodes.forEach((n) => {
            const a = 0.4 + Math.sin(n.p) * 0.3;
            ctx.beginPath();
            ctx.arc(
                n.x,
                n.y,
                n.r * (0.85 + Math.sin(n.p) * 0.15),
                0,
                Math.PI * 2,
            );
            ctx.fillStyle = n.c + a + ")";
            ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    window.addEventListener("resize", init, {
        passive: true,
    });
    init();
    draw();
})();

/* ═══════════════════════════════════════════════════
           SIDEBAR TOGGLE
           ─────────────────────────────────────────────────
           Desktop: toggle .collapsed class → width change
           Mobile:  toggle .open class + show overlay

           KEY: overlay uses display:none/block — no
           z-index trickery needed. Sidebar z:300 > overlay z:200.
           Both are siblings of .layout (no stacking context trap).
        ═══════════════════════════════════════════════════ */
const sidebar = document.getElementById("sidebar");
const mainWrap = document.getElementById("mainWrap");
const overlay = document.getElementById("sbOverlay");
const toggleBtn = document.getElementById("btnToggle");
const toggleIco = document.getElementById("toggleIcon");

let isCollapsed = false;

function isMobile() {
    return window.innerWidth < 992;
}

function openMobileSidebar() {
    sidebar.classList.add("open");
    overlay.classList.add("active");
}

function closeMobileSidebar() {
    sidebar.classList.remove("open");
    overlay.classList.remove("active");
}

toggleBtn.addEventListener("click", function () {
    if (isMobile()) {
        sidebar.classList.contains("open")
            ? closeMobileSidebar()
            : openMobileSidebar();
    } else {
        isCollapsed = !isCollapsed;
        sidebar.classList.toggle("collapsed", isCollapsed);
        mainWrap.classList.toggle("expanded", isCollapsed);
        toggleIco.className = isCollapsed
            ? "bi bi-layout-sidebar"
            : "bi bi-layout-sidebar-inset";
    }
});

overlay.addEventListener("click", closeMobileSidebar);

window.addEventListener(
    "resize",
    function () {
        if (!isMobile()) closeMobileSidebar();
    },
    {
        passive: true,
    },
);

/* ── User dropdown ── */
const userTrigger = document.getElementById("userTrigger");
const userDropdown = document.getElementById("userDropdown");

userTrigger.addEventListener("click", function () {
    const open = userDropdown.classList.toggle("open");
    userTrigger.classList.toggle("open", open);
});

document.addEventListener("click", function (e) {
    if (!userTrigger.contains(e.target) && !userDropdown.contains(e.target)) {
        userDropdown.classList.remove("open");
        userTrigger.classList.remove("open");
    }
});

/* ── FAB scroll to top ── */
var fab = document.getElementById("fab");
var scrollTicking = false;
window.addEventListener(
    "scroll",
    function () {
        if (!scrollTicking) {
            requestAnimationFrame(function () {
                fab.classList.toggle("visible", window.scrollY > 300);
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    },
    {
        passive: true,
    },
);

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}

/* ── Logout modal drain ── */
var logoutModal = document.getElementById("logoutModal");
logoutModal.addEventListener("show.bs.modal", function () {
    var fill = document.getElementById("drainFill");
    fill.classList.remove("go");
    void fill.offsetWidth;
    fill.classList.add("go");
});
logoutModal.addEventListener("hidden.bs.modal", function () {
    document.getElementById("drainFill").classList.remove("go");
});

$(document).ready(function () {
    $(".btn-logout").on("click", function (e) {
        e.preventDefault();

        let $btn = $(this);
        let $content = $btn.find("span");
        $("#drainFill").css("width", "100%");

        // 1. Ubah tampilan menjadi spinner
        $btn.prop("disabled", true); // Mencegah double click
        $content.html('<div class="logout-spinner"></div> Sedang proses...');

        // 2. Tunggu 500ms (setengah detik) sebelum eksekusi AJAX
        setTimeout(function () {
            $.ajax({
                url: "/logout",
                type: "POST",
                success: function (response) {
                    // Berhasil logout, arahkan ke halaman login atau home
                    window.location.href = "/login";
                },
                error: function (xhr) {
                    // Jika ada error, kembalikan tombol ke semula
                    console.error("Logout gagal:", xhr);
                    $btn.prop("disabled", false);
                    $content.html(
                        '<i class="bi bi-box-arrow-right"></i> Ya, Logout Sekarang',
                    );
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                },
            });
        }, 500);
    });
});
