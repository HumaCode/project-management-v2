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

/* ── Notification dropdown ── */
$(document).ready(function() {
    const $notifTrigger = $('#notifTrigger');
    const $notifDropdown = $('#notifDropdown');
    const $notifDot = $('#notifDot');
    const $notifDropdownBody = $('#notifDropdownBody');
    const $notifMarkAll = $('#notifMarkAll');

    // 1. Initial check for unread count to show/hide the red dot
    function checkUnreadCount() {
        $.ajax({
            url: '/notifications/recent',
            method: 'GET',
            success: function(res) {
                if (res.success) {
                    updateNotificationDot(res.unread_count);
                }
            }
        });
    }

    function updateNotificationDot(count) {
        if (count > 0) {
            $notifDot.removeClass('d-none');
        } else {
            $notifDot.addClass('d-none');
        }
    }

    // Run on load
    checkUnreadCount();

    // Polling every 30 seconds for live updates
    setInterval(checkUnreadCount, 30000);

    // 2. Click to toggle dropdown
    $notifTrigger.on('click', function(e) {
        e.stopPropagation();
        const isOpen = $notifDropdown.hasClass('open');
        
        // Close user dropdown if open
        $('#userDropdown').removeClass('open');
        $('#userTrigger').removeClass('open');

        if (!isOpen) {
            $notifDropdown.addClass('open');
            fetchRecentNotifications();
        } else {
            $notifDropdown.removeClass('open');
        }
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if ($notifDropdown.hasClass('open') && !$notifTrigger.closest('div').find(e.target).length) {
            $notifDropdown.removeClass('open');
        }
    });

    // 3. Fetch recent notifications
    function fetchRecentNotifications() {
        $notifDropdownBody.html(`
            <div class="nd-no-data">
                <div class="spinner-border spinner-border-sm text-cyan" role="status" style="width: 1rem; height: 1rem; border-width: 0.15em;"></div>
                <div style="margin-top: 8px; font-size: 11.5px; font-family: var(--mono);">Memuat...</div>
            </div>
        `);

        $.ajax({
            url: '/notifications/recent',
            method: 'GET',
            success: function(res) {
                if (res.success) {
                    updateNotificationDot(res.unread_count);
                    renderNotifications(res.notifications);
                }
            },
            error: function() {
                $notifDropdownBody.html(`
                    <div class="nd-no-data">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                        <div>Gagal memuat notifikasi</div>
                    </div>
                `);
            }
        });
    }

    // 4. Render notification items
    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            $notifDropdownBody.html(`
                <div class="nd-no-data">
                    <i class="bi bi-bell-slash text-muted"></i>
                    <div>Tidak ada notifikasi baru</div>
                </div>
            `);
            return;
        }

        let html = '';
        notifications.forEach(notif => {
            html += `
                <div class="nd-item unread" data-id="${notif.id}" data-url="${notif.url}">
                    <div class="nd-item-ico">
                        <i class="${notif.icon}"></i>
                    </div>
                    <div class="nd-item-content">
                        <div class="nd-item-msg">${notif.message}</div>
                        <div class="nd-item-time">${notif.time}</div>
                    </div>
                </div>
            `;
        });
        $notifDropdownBody.html(html);
    }

    // 5. Click on individual notification item
    $notifDropdownBody.on('click', '.nd-item', function(e) {
        e.preventDefault();
        const $item = $(this);
        const id = $item.data('id');
        const url = $item.data('url');

        $.ajax({
            url: `/notifications/${id}/mark-read`,
            method: 'POST',
            success: function() {
                window.location.href = url;
            },
            error: function() {
                // Fail-safe redirect if network fails
                window.location.href = url;
            }
        });
    });

    // 6. Mark all as read
    $notifMarkAll.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: '/notifications/mark-all',
            method: 'POST',
            success: function(res) {
                if (res.success) {
                    $notifDot.addClass('d-none');
                    fetchRecentNotifications();
                    if (typeof showToast === 'function') {
                        showToast('success', 'Semua notifikasi ditandai dibaca.');
                    }
                }
            }
        });
    });

    // 7. Dark/Light Theme Toggle
    const $themeToggle = $('#themeToggle');
    const $themeIcon = $('#themeIcon');
    const $themeToggleDropdown = $('#themeToggleDropdown');
    const $themeDropdownIcon = $('#themeDropdownIcon');
    const $themeDropdownText = $('#themeDropdownText');

    // Initialize switcher icon state on load
    function initThemeSwitcherIcon() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        if (currentTheme === 'light') {
            $themeIcon.removeClass('bi-moon-stars-fill').addClass('bi-sun-fill');
            $themeToggle.attr('title', 'Aktifkan Mode Gelap');
            
            // Dropdown settings
            $themeDropdownIcon.removeClass('bi-sun-fill').addClass('bi-moon-stars-fill');
            $themeDropdownText.text('Mode Gelap');
        } else {
            $themeIcon.removeClass('bi-sun-fill').addClass('bi-moon-stars-fill');
            $themeToggle.attr('title', 'Aktifkan Mode Terang');
            
            // Dropdown settings
            $themeDropdownIcon.removeClass('bi-moon-stars-fill').addClass('bi-sun-fill');
            $themeDropdownText.text('Mode Terang');
        }
    }
    initThemeSwitcherIcon();

    // Trigger primary theme toggle when dropdown option is clicked
    $themeToggleDropdown.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $themeToggle.trigger('click');
    });

    $themeToggle.on('click', function(e) {
        e.preventDefault();
        
        // Prevent double clicking during animation
        if ($themeToggle.hasClass('theme-transitioning')) return;

        // Add transitioning class for scale & rotate spin
        $themeToggle.addClass('theme-transitioning');

        // Halfway through rotation (250ms), swap variables and icon
        setTimeout(() => {
            const oldTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const newTheme = oldTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Sync all theme switchers (both topbar button and dropdown option)
            initThemeSwitcherIcon();
        }, 250);

        // Remove transitioned class when full animation completes (500ms)
        setTimeout(() => {
            $themeToggle.removeClass('theme-transitioning');
        }, 500);
    });
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
    $("#confirmLogoutBtn").on("click", function (e) {
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
