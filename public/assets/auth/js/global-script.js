/* ── Network Canvas (same as login) ─────────────────────── */
(function () {
    const canvas = document.getElementById("bg-canvas");
    const ctx = canvas.getContext("2d");
    let W,
        H,
        nodes = [];
    const COLORS = ["rgba(0,200,255,", "rgba(0,114,198,", "rgba(0,229,160,"];

    function resize() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
        initNodes();
    }

    function initNodes() {
        nodes = [];
        const n = Math.max(28, Math.floor((W * H) / 22000));
        for (let i = 0; i < n; i++) {
            nodes.push({
                x: Math.random() * W,
                y: Math.random() * H,
                vx: (Math.random() - 0.5) * 0.45,
                vy: (Math.random() - 0.5) * 0.45,
                r: Math.random() * 2 + 1,
                c: COLORS[Math.floor(Math.random() * COLORS.length)],
                pulse: Math.random() * Math.PI * 2,
            });
        }
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        nodes.forEach((n) => {
            n.x += n.vx;
            n.y += n.vy;
            n.pulse += 0.018;
            if (n.x < -20) n.x = W + 20;
            if (n.x > W + 20) n.x = -20;
            if (n.y < -20) n.y = H + 20;
            if (n.y > H + 20) n.y = -20;
        });
        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const a = nodes[i],
                    b = nodes[j];
                const dx = a.x - b.x,
                    dy = a.y - b.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 160) {
                    ctx.beginPath();
                    ctx.moveTo(a.x, a.y);
                    ctx.lineTo(b.x, b.y);
                    ctx.strokeStyle = `rgba(0,160,220,${(1 - dist / 160) * 0.28})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                }
            }
        }
        nodes.forEach((n) => {
            const g = 0.5 + Math.sin(n.pulse) * 0.35;
            ctx.beginPath();
            ctx.arc(
                n.x,
                n.y,
                n.r * (0.9 + Math.sin(n.pulse) * 0.15),
                0,
                Math.PI * 2,
            );
            ctx.fillStyle = n.c + g + ")";
            ctx.fill();
            if (n.r > 2) {
                ctx.beginPath();
                ctx.arc(n.x, n.y, n.r * 3.5, 0, Math.PI * 2);
                ctx.fillStyle = n.c + g * 0.08 + ")";
                ctx.fill();
            }
        });
        requestAnimationFrame(draw);
    }

    window.addEventListener("resize", resize, {
        passive: true,
    });
    resize();
    draw();
})();

/* ── Floating Particles ─────────────────────────────────── */
(function () {
    const hues = ["rgba(0,200,255,", "rgba(0,114,198,", "rgba(0,229,160,"];
    for (let i = 0; i < 18; i++) {
        const p = document.createElement("div");
        p.className = "particle";
        const size = Math.random() * 4 + 2;
        const clr = hues[Math.floor(Math.random() * hues.length)];
        const dur = 12 + Math.random() * 20;
        p.style.cssText = `width:${size}px;height:${size}px;left:${Math.random() * 100}vw;bottom:-10px;background:${clr}${0.4 + Math.random() * 0.3}));animation-duration:${dur}s;animation-delay:${Math.random() * -20}s;--drift:${(Math.random() - 0.5) * 120}px;box-shadow:0 0 ${size * 3}px ${clr}0.6));`;
        document.body.appendChild(p);
    }
})();

function showAlert(msg) {
    const el = document.getElementById("alertError");
    document.getElementById("alertMsg").textContent = msg;
    el.style.display = "flex";
    el.scrollIntoView({
        behavior: "smooth",
        block: "nearest",
    });
}
