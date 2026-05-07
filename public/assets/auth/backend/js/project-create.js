/* 
    Project Create Logic
    Separated to avoid conflicts with other modules.
*/

(function() {
    "use strict";

    /* Form: char counter */
    function initCC(iid, cid, max) {
        var el = document.getElementById(iid),
            ct = document.getElementById(cid);
        if (!el || !ct) return;

        function upd() {
            var n = el.value.length;
            ct.textContent = n + ' / ' + max;
            ct.className = 'ccnt' + (n >= max ? ' full' : n >= max * 0.85 ? ' near' : '');
        }
        el.addEventListener('input', upd);
        upd();
    }
    
    initCC('fNama', 'cNama', 120);
    // initCC('fDesc', 'cDesc', 500); // Disabled for CKEditor
    initCC('fNotes', 'cNotes', 300);

    /* CKEditor Initialization */
    var editorInstance;
    if (document.querySelector('#fDesc')) {
        ClassicEditor
            .create(document.querySelector('#fDesc'), {
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'heading', '|',
                        'bold', 'italic', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed'
                    ],
                    shouldNotGroupWhenFull: true
                },
                // Enable Base64 Upload
                extraPlugins: [
                    function(editor) {
                        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                            return new Base64UploadAdapter(loader);
                        };
                    }
                ],
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });
    }

    // Simple Base64 Adapter
    class Base64UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }
        upload() {
            return this.loader.file.then(file => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => {
                    resolve({ default: reader.result });
                };
                reader.onerror = error => {
                    reject(error);
                };
                reader.readAsDataURL(file);
            }));
        }
        abort() {}
    }

    /* Form: slider */
    var sl = document.getElementById('slRange'),
        sv = document.getElementById('slVal'),
        sf = document.getElementById('slFill');

    function updSl() {
        if (!sl || !sv || !sf) return;
        var v = parseInt(sl.value);
        sv.textContent = v + '%';
        sv.style.left = 'calc(' + v + '% + ' + (10 - v * 0.2) + 'px)';
        sf.style.width = v + '%';
    }
    if (sl) sl.addEventListener('input', updSl);
    updSl();

    /* Form: PIC multi-select */
    var pb = document.getElementById('picBox'),
        pi = document.getElementById('picIn'),
        pd = document.getElementById('picDd'),
        ps = {};

    function picChips() {
        if (!pb || !pi) return;
        pb.querySelectorAll('.pic-chip').forEach(function(c) {
            c.remove();
        });
        Object.keys(ps).forEach(function(id) {
            var m = ps[id],
                c = document.createElement('div');
            c.className = 'pic-chip';
            c.dataset.id = id;
            c.innerHTML = '<div class="pc-av">' + m.i + '</div><span>' + m.n + '</span><button type="button" class="del-c"><i class="bi bi-x-lg"></i></button>';
            c.querySelector('.del-c').addEventListener('click', function(e) {
                e.stopPropagation();
                delete ps[id];
                picChips();
                picState();
            });
            pb.insertBefore(c, pi);
        });
    }

    function picState() {
        if (!pd) return;
        pd.querySelectorAll('.pic-opt').forEach(function(o) {
            o.classList.toggle('sel', !!ps[o.dataset.id]);
        });
    }

    if (pd) {
        pd.querySelectorAll('.pic-opt').forEach(function(o) {
            o.addEventListener('mousedown', function(e) {
                e.preventDefault();
                var id = o.dataset.id;
                if (ps[id]) delete ps[id];
                else ps[id] = {
                    n: o.dataset.nm,
                    i: o.dataset.in,
                    r: o.dataset.role
                };
                picChips();
                picState();
                pi.value = '';
                picFilt('');
            });
        });
    }

    function picFilt(q) {
        if (!pd) return;
        q = q.toLowerCase();
        pd.querySelectorAll('.pic-opt').forEach(function(o) {
            o.style.display = o.dataset.nm.toLowerCase().indexOf(q) > -1 ? '' : 'none';
        });
    }

    if (pi) {
        pi.addEventListener('focus', function() {
            pd.classList.add('open');
            picFilt(pi.value);
        });
        pi.addEventListener('blur', function() {
            setTimeout(function() {
                pd.classList.remove('open');
            }, 160);
        });
        pi.addEventListener('input', function() {
            picFilt(this.value);
        });
    }

    if (pb) {
        pb.addEventListener('click', function() {
            pi.focus();
        });
    }

    /* Form: Flatpickr (Date Picker) */
    var fpStart = flatpickr("#fStart", {
        locale: "id",
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d F Y",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr, instance) {
            fpDeadline.set('minDate', dateStr);
        }
    });

    var fpDeadline = flatpickr("#fDeadline", {
        locale: "id",
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d F Y",
        disableMobile: "true"
    });

    /* Form: reset */
    var btnReset = document.getElementById('btnReset');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            ['fNama', 'fDesc', 'fNotes'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.value = '';
            });
            var fStatus = document.getElementById('fStatus');
            if (fStatus) fStatus.value = 'to_do';
            if (fs) fs.value = '';
            if (fd) fd.value = '';
            if (sl) sl.value = 0;
            updSl();
            ps = {};
            picChips();
            picState();
            ['cNama', 'cDesc', 'cNotes'].forEach(function(id) {
                var e = document.getElementById(id);
                if (e) {
                    var p = e.textContent.split(' / ');
                    e.textContent = '0 / ' + p[1];
                    e.className = 'ccnt';
                }
            });
            document.querySelectorAll('.fi.err,.fa.err,.fsl.err').forEach(function(e) {
                e.classList.remove('err');
            });
            document.querySelectorAll('.fg.has-err').forEach(function(e) {
                e.classList.remove('has-err');
            });
        });
    }

    /* Form: submit */
    var btnSave = document.getElementById('btnSave');
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            // Sync CKEditor
            if (editorInstance) {
                editorInstance.updateSourceElement();
            }

            var ok = true;
            var checks = [{
                    el: document.getElementById('fNama'),
                    fg: document.getElementById('fNama') ? document.getElementById('fNama').closest('.fg') : null
                },
                {
                    el: document.getElementById('fStatus'),
                    fg: document.getElementById('fStatus') ? document.getElementById('fStatus').closest('.fg') : null
                },
                {
                    el: fs,
                    fg: fs ? fs.closest('.fg') : null
                },
                {
                    el: fd,
                    fg: fd ? fd.closest('.fg') : null
                }
            ];
            checks.forEach(function(c) {
                if (c.el && !c.el.value.trim()) {
                    if (c.fg) c.fg.classList.add('has-err');
                    c.el.classList.add('err');
                    ok = false;
                } else if (c.el) {
                    if (c.fg) c.fg.classList.remove('has-err');
                    c.el.classList.remove('err');
                }
            });
            if (fs && fd && fs.value && fd.value && fd.value < fs.value) {
                fd.closest('.fg').classList.add('has-err');
                fd.classList.add('err');
                ok = false;
            }
            if (ok) {
                var btn = document.getElementById('btnSave');
                btn.innerHTML = '<span><i class="bi bi-hourglass-split"></i>&nbsp;Menyimpan...</span>';
                btn.style.opacity = '.7';
                btn.style.pointerEvents = 'none';
                setTimeout(function() {
                    window.location.href = window.projectIndexUrl || '/projects';
                }, 1400);
            } else {
                var first = document.querySelector('.fg.has-err');
                if (first) first.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    }
})();
