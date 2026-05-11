/* ─────────────────────────────────────
     SPECIFIC JS FOR DOCUMENTS MODULE
     Note: Layout & Global logic inherited from global-js.js
  ───────────────────────────────────── */

/* ── Modal drain animation ── */
function initDrain(mid, fid) {
    var m = document.getElementById(mid);
    if (!m) return;
    m.addEventListener('show.bs.modal', function() {
        var f = document.getElementById(fid);
        if (!f) return;
        f.classList.remove('go');
        void f.offsetWidth;
        f.classList.add('go');
    });
    m.addEventListener('hidden.bs.modal', function() {
        var f = document.getElementById(fid);
        if (f) f.classList.remove('go');
    });
}
initDrain('delModal', 'drainDel');

/* ── Delete modal: inject doc name ── */
document.querySelectorAll('.ib-x').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var el = document.getElementById('delDocName');
        if (el) el.textContent = this.dataset.nm || 'ini';
    });
});

/* ── Pagination buttons behavior ── */
document.querySelectorAll('.pb:not([disabled])').forEach(function(b) {
    b.addEventListener('click', function() {
        if (this.classList.contains('active') || this.querySelector('i')) return;
        this.closest('.pag').querySelectorAll('.pb').forEach(function(x) {
            x.classList.remove('active');
        });
        this.classList.add('active');
    });
});

/* ── Count-up animation for stat cards ── */
function countUp(el, target) {
    var dur = 1200,
        start = performance.now();
    (function step(now) {
        var p = Math.min((now - start) / dur, 1),
            e = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(e * target);
        if (p < 1) requestAnimationFrame(step);
        else el.textContent = target;
    })(performance.now());
}
var io = new IntersectionObserver(function(ents) {
    ents.forEach(function(e) {
        if (e.isIntersecting) {
            var el = e.target.querySelector('[data-count]');
            if (el && !el.dataset.done) {
                el.dataset.done = '1';
                countUp(el, parseInt(el.dataset.count));
            }
        }
    });
}, {
    threshold: .3
});
document.querySelectorAll('.sc').forEach(function(c) {
    io.observe(c);
});

/* ── Drop zone behavior in Add Modal ── */
var dz = document.getElementById('dropZone');
if (dz) {
    dz.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag');
    });
    dz.addEventListener('dragleave', function() {
        this.classList.remove('drag');
    });
    dz.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag');
    });
}

/* ── Select2 init for Add Modal ── */
function initSelect2() {
    var opts = {
        dropdownParent: $('#addModal'),
        placeholder: '-- Pilih --',
        allowClear: true,
        minimumResultsForSearch: 6,
        theme: 'default'
    };
    $('#sel2Kat').select2($.extend({}, opts, {
        placeholder: '-- Pilih Kategori --',
        minimumResultsForSearch: Infinity
    }));
    $('#sel2Proj').select2($.extend({}, opts, {
        placeholder: '-- Pilih Project --',
        minimumResultsForSearch: Infinity
    }));
    $('#sel2User').select2($.extend({}, opts, {
        placeholder: '-- Pilih Pengguna --',
        minimumResultsForSearch: Infinity
    }));
}

$('#addModal').on('shown.bs.modal', function() {
    if (!$('#sel2Kat').hasClass('select2-hidden-accessible')) {
        initSelect2();
    }
});

// Focus search on select2 open
$(document).on('select2:open', function() {
    document.querySelector('.select2-search__field') && document.querySelector('.select2-search__field').focus();
});
