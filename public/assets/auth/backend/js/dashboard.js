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
var chartData = [
    {
        l: "Jan",
        v: 18,
    },
    {
        l: "Feb",
        v: 24,
    },
    {
        l: "Mar",
        v: 15,
    },
    {
        l: "Apr",
        v: 31,
    },
    {
        l: "Mei",
        v: 27,
    },
    {
        l: "Jun",
        v: 22,
    },
];
var maxVal = Math.max.apply(
    null,
    chartData.map(function (d) {
        return d.v;
    }),
);
var wrap = document.getElementById("chartWrap");
chartData.forEach(function (d, i) {
    var col = document.createElement("div");
    col.className = "chart-col";
    col.innerHTML =
        '<div class="chart-bar" style="height:0;transition:height 1.2s cubic-bezier(.4,0,.2,1) ' +
        i * 120 +
        'ms" data-p="' +
        (d.v / maxVal) * 100 +
        '" title="' +
        d.v +
        ' dokumen"></div><span class="chart-lbl">' +
        d.l +
        "</span>";
    wrap.appendChild(col);
});
setTimeout(function () {
    document.querySelectorAll(".chart-bar").forEach(function (b) {
        b.style.height = b.dataset.p + "%";
    });
}, 500);
