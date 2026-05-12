$(function () {
    // AOS Init
    if (typeof AOS !== 'undefined') {
        AOS.init({ once: true, easing: 'ease-out-cubic', duration: 500, offset: 20 });
    }

    // Modal drain animation
    function initDrain(mid, fid) {
        var $m = $('#' + mid);
        if ($m.length === 0) return;
        $m.on('show.bs.modal', function () {
            var $f = $('#' + fid);
            if ($f.length === 0) return;
            $f.removeClass('go');
            void $f[0].offsetWidth; // force reflow
            $f.addClass('go');
        });
        $m.on('hidden.bs.modal', function () {
            var $f = $('#' + fid);
            if ($f.length > 0) $f.removeClass('go');
        });
    }
    initDrain('delModal', 'drainDel');
    initDrain('logoutModal', 'drainLogout');

    // Delete name inject
    $(document).on('click', '.ib-x', function () {
        var name = $(this).data('nm') || 'ini';
        $('#delNm').text(name);
    });

    // Count-up animation
    function countUp($el, target) {
        var dur = 1200, start = performance.now();
        (function step(now) {
            var p = Math.min((now - start) / dur, 1), e = 1 - Math.pow(1 - p, 3);
            $el.text(Math.round(e * target));
            if (p < 1) requestAnimationFrame(step);
            else $el.text(target);
        })(performance.now());
    }

    var io = new IntersectionObserver(function (ents) {
        ents.forEach(function (e) {
            if (e.isIntersecting) {
                var $el = $(e.target).find('[data-count]');
                if ($el.length > 0 && !$el.data('done')) {
                    $el.data('done', '1');
                    countUp($el, parseInt($el.data('count')));
                }
            }
        });
    }, { threshold: .3 });

    $('.sc').each(function () {
        io.observe(this);
    });

    // Pagination (Static mockup)
    $(document).on('click', '.pb:not([disabled])', function () {
        if ($(this).hasClass('active') || $(this).find('i').length > 0) return;
        $(this).closest('.pag').find('.pb').removeClass('active');
        $(this).addClass('active');
    });

    // View modal populate
    $(document).on('click', '.ib-v', function () {
        var d = $(this).data();
        $('#viewTitle').html('<i class="bi bi-journal-text"></i>&nbsp;' + (d.title || 'Detail Catatan'));
        $('#viewBody').html(d.content || '<em style="color:var(--muted)">Tidak ada isi.</em>');
        $('#viewMeta').html(
            '<div class="view-meta-item"><i class="bi bi-tags-fill"></i>' + (d.kat || '&mdash;') + '</div>' +
            '<div class="view-meta-item"><i class="bi bi-kanban-fill"></i>' + (d.proj || '&mdash;') + '</div>' +
            '<div class="view-meta-item"><i class="bi bi-flag-fill"></i>' + (d.prio || '&mdash;') + '</div>' +
            '<div class="view-meta-item"><i class="bi bi-person-fill"></i>' + (d.by || '&mdash;') + '</div>' +
            '<div class="view-meta-item"><i class="bi bi-calendar3"></i>' + (d.tgl || '&mdash;') + '</div>'
        );
    });

    // TinyMCE Init
    var tinyCfg = {
        height: 340, menubar: false, branding: false, statusbar: true, resize: false,
        skin: 'oxide-dark', content_css: 'dark',
        plugins: ['lists', 'link', 'code', 'table', 'autolink', 'codesample'],
        toolbar: 'undo redo | bold italic underline strikethrough | bullist numlist | link table codesample | code | removeformat',
        toolbar_mode: 'wrap',
        content_style: "body{font-family:'Outfit',sans-serif;font-size:14px;color:#e2eaf4;background:#050e1d;padding:12px 14px;line-height:1.7}h1,h2,h3{color:#00c8ff;font-weight:700;margin-bottom:8px}a{color:#00c8ff}code{background:rgba(0,200,255,.1);padding:1px 5px;border-radius:4px;font-size:12.5px;color:#00c8ff}pre{background:rgba(0,0,0,.4);border:1px solid rgba(0,200,255,.15);border-radius:8px;padding:12px}"
    };

    var addInited = false, editInited = false;
    $('#addModal').on('shown.bs.modal', function () {
        if (!addInited && typeof tinymce !== 'undefined') {
            tinymce.init($.extend({}, tinyCfg, { selector: '#tinyAdd' }));
            addInited = true;
        }
    });
    $('#editModal').on('shown.bs.modal', function () {
        if (!editInited && typeof tinymce !== 'undefined') {
            tinymce.init($.extend({}, tinyCfg, { selector: '#tinyEdit' }));
            editInited = true;
        }
    });

    // Remove TinyMCE on hide to prevent re-init issues
    $('#addModal').on('hidden.bs.modal', function () {
        if (addInited && typeof tinymce !== 'undefined') {
            var inst = tinymce.get('tinyAdd');
            if (inst) inst.remove();
            addInited = false;
        }
    });
    $('#editModal').on('hidden.bs.modal', function () {
        if (editInited && typeof tinymce !== 'undefined') {
            var inst = tinymce.get('tinyEdit');
            if (inst) inst.remove();
            editInited = false;
        }
    });
});
