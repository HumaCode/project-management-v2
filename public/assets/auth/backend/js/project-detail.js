$(function () {
    // Count-up animation
    function countUp(el, target) {
        let dur = 1200;
        let start = performance.now();
        (function step(now) {
            let p = Math.min((now - start) / dur, 1);
            let ease = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(ease * target);
            if (p < 1) requestAnimationFrame(step);
            else el.textContent = target;
        })(performance.now());
    }

    let io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                let el = e.target.querySelector('[data-count]');
                if (el && !el.dataset.done) {
                    el.dataset.done = '1';
                    countUp(el, parseInt(el.dataset.count));
                }
            }
        });
    }, { threshold: 0.3 });

    $('.msc').each(function () {
        io.observe(this);
    });

    // Progress bar animate
    setTimeout(function () {
        let fill = $('#progFill');
        if (fill.length) fill.css('width', '72%');
    }, 400);

    // Tabs
    $('.tab-btn').on('click', function () {
        let tab = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
        $('#tab-' + tab).addClass('active');
    });

    // Doc search
    $('#docSearch').on('input', function () {
        let q = $(this).val().toLowerCase();
        $('.doc-tbl tbody tr').each(function () {
            let nm = $(this).find('.doc-nm');
            if (!q || (nm.length && nm.text().toLowerCase().indexOf(q) > -1)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Add note
    $('#btnAddNote').on('click', function () {
        let ta = $('#noteInput');
        if (!ta.val().trim()) return;
        let wrap = $('#tab-catatan > div:first-child');
        let card = $('<div class="note-card"></div>');
        card.css('animation', 'mup .35s ease both');
        card.html(
            '<div class="note-head"><div class="note-av">BS</div><div class="note-author">Budi Santoso</div><div class="note-time">Baru saja</div></div>' +
            '<div class="note-body">' + ta.val().replace(/</g, '&lt;') + '</div>' +
            '<div class="note-actions"><button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button><button class="note-btn"><i class="bi bi-pencil"></i> Edit</button><button class="note-btn" style="color:rgba(255,77,109,.5)"><i class="bi bi-trash3"></i> Hapus</button></div>'
        );
        wrap.append(card);
        ta.val('');
    });
});
