/* 
    Project Edit Logic
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

    /* Form: submit (AJAX) */
    var btnSave = document.getElementById('btnSave');

    /* Form: Flatpickr (Date Picker) */
    var fs = document.getElementById('fStart');
    var fd = document.getElementById('fDeadline');

    /* Select2 Initialization */
    if (typeof $ !== 'undefined' && $('.fsl').length) {
        $('.fsl').each(function() {
            var $this = $(this);
            $this.select2({
                placeholder: $this.find('option[value=""]').text() || '-- Pilih --',
                allowClear: true,
                width: '100%'
            }).on('change', function() {
                $(this).removeClass('err');
                $(this).closest('.fg').removeClass('has-err');
            });
        });
    }

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

    /* Form: Thumbnail Upload */
    var fThumb = document.getElementById('fThumb'),
        tuPreview = document.getElementById('tuPreview'),
        tuBox = document.getElementById('thumbUpload'),
        btnRemoveThumb = document.getElementById('btnRemoveThumb');

    if (fThumb) {
        fThumb.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    tuPreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    tuBox.classList.add('has-file');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (btnRemoveThumb) {
        btnRemoveThumb.addEventListener('click', function() {
            fThumb.value = '';
            tuPreview.innerHTML = '<i class="bi bi-image"></i>';
            tuBox.classList.remove('has-file');
        });
    }

    /* Form: Color Picker */
    var fColor = document.getElementById('fColor'),
        colorHex = document.getElementById('colorHex');

    if (fColor && colorHex) {
        fColor.addEventListener('input', function() {
            colorHex.textContent = this.value.toUpperCase();
        });
    }

    /* Form: submit (AJAX) */
    var btnSave = document.getElementById('btnSave');
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            if (editorInstance) {
                editorInstance.updateSourceElement();
            }

            var ok = true;
            var checks = [
                { el: document.getElementById('fNama'), fg: document.getElementById('fNama').closest('.fg') },
                { el: document.getElementById('fStatus'), fg: document.getElementById('fStatus').closest('.fg') },
                { el: document.getElementById('fTeam'), fg: document.getElementById('fTeam').closest('.fg') },
                { el: fs, fg: fs.closest('.fg') },
                { el: fd, fg: fd.closest('.fg') }
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

            if (ok) {
                submitForm();
            } else {
                var first = document.querySelector('.fg.has-err');
                if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    function submitForm() {
        var form = document.getElementById('formEditProject');
        var formData = new FormData(form);

        btnSave.innerHTML = '<span><i class="bi bi-hourglass-split"></i>&nbsp;Menyimpan...</span>';
        btnSave.style.opacity = '.7';
        btnSave.style.pointerEvents = 'none';

        axios.post(form.action, formData)
            .then(res => {
                SCA.toast({
                    type: "success",
                    title: "Berhasil!",
                    message: res.data?.message || "Perubahan project berhasil disimpan.",
                    position: "top-right",
                });
                setTimeout(() => {
                    window.location.href = window.projectIndexUrl || '/projects';
                }, 1000);
            })
            .catch(err => {
                btnSave.innerHTML = '<span><i class="bi bi-check2-circle"></i> Simpan Perubahan</span>';
                btnSave.style.opacity = '1';
                btnSave.style.pointerEvents = 'auto';

                if (err.response && err.response.status === 422) {
                    var errors = err.response.data.errors;
                    
                    SCA.toast({
                        type: "error",
                        title: "Gagal!",
                        message: "Silakan periksa kembali isian form Anda.",
                        position: "top-right",
                    });

                    Object.keys(errors).forEach(key => {
                        var el = document.getElementsByName(key)[0];
                        if (el) {
                            var fg = el.closest('.fg');
                            if (fg) {
                                fg.classList.add('has-err');
                                var msg = fg.querySelector('.emsg');
                                if (msg) msg.textContent = errors[key][0];
                            }
                        }
                    });
                    var first = document.querySelector('.fg.has-err');
                    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    SCA.toast({
                        type: "error",
                        title: "Error!",
                        message: err.response?.data?.message || err.message || "Terjadi kesalahan sistem.",
                        position: "top-right",
                    });
                }
            });
    }
})();
