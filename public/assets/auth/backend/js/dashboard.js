/* ── Counter animation ── */
function countUp(el, target, duration) {
    duration = duration || 1200;
    var start = performance.now();
    (function step(now) {
        var p = Math.min((now - start) / duration, 1);
        var ease = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(ease * target);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target;
    })(performance.now());
}
document.querySelectorAll(".stat-card").forEach(function (card) {
    var io = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el = card.querySelector("[data-count]");
                    if (el && !el.dataset.done) {
                        el.dataset.done = "1";
                        countUp(el, parseInt(el.dataset.count));
                    }
                }
            });
        },
        {
            threshold: 0.3,
        },
    );
    io.observe(card);
});

/* ── Chart bars ── */
var chartData = window.dashboardChartData || [
    { l: "Jan", v: 0 },
    { l: "Feb", v: 0 },
    { l: "Mar", v: 0 },
    { l: "Apr", v: 0 },
    { l: "Mei", v: 0 },
    { l: "Jun", v: 0 },
];

var maxVal = Math.max.apply(
    null,
    chartData.map(function (d) {
        return parseFloat(d.v) || 0;
    }),
) || 1;

var wrap = document.getElementById("chartWrap");
if (wrap) {
    wrap.innerHTML = ""; // Clear fallback
    chartData.forEach(function (d, i) {
        var val = parseFloat(d.v) || 0;
        var percent = (val / maxVal) * 100;
        
        var col = document.createElement("div");
        col.className = "chart-col";
        col.innerHTML =
            '<div class="chart-bar" style="height:0;transition:height 1.2s cubic-bezier(.4,0,.2,1) ' +
            i * 100 +
            'ms" data-p="' +
            percent +
            '" title="' +
            val +
            (val > 1 ? " projects" : " project") +
            '"></div><span class="chart-lbl">' +
            d.l +
            "</span>";
        wrap.appendChild(col);
    });

    // Force animation after a short delay
    setTimeout(function () {
        var wrapHeight = wrap.clientHeight || 120;
        document.querySelectorAll(".chart-bar").forEach(function (b) {
            var p = parseFloat(b.dataset.p) || 0;
            // Wadah chart-wrap tingginya dinamis, kita ambil dari clientHeight
            var heightPx = (p / 100) * (wrapHeight - 20); // -20 for labels padding
            b.style.height = heightPx + "px";
        });
    }, 200);
}
