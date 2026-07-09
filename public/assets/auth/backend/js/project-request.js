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

    /* Form: submit (AJAX) */
    var btnSave = document.getElementById('btnSave');

    /* Form: Flatpickr (Date Picker) */
    var fs = document.getElementById('fStart');

    var fpStart = flatpickr("#fStart", {
        locale: "id",
        dateFormat: "d-m-Y",
        altInput: true,
        altFormat: "d F Y",
        disableMobile: "true"
    });

    /* Form: Reference Image Upload */
    var fRefImage = document.getElementById('fRefImage'),
        refImagePreview = document.getElementById('refImagePreview'),
        refImageUpload = document.getElementById('refImageUpload'),
        btnRemoveRefImage = document.getElementById('btnRemoveRefImage');

    if (fRefImage) {
        fRefImage.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    refImagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="max-height:100%; max-width:100%; border-radius:8px; object-fit:contain;">';
                    refImageUpload.classList.add('has-file');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (btnRemoveRefImage) {
        btnRemoveRefImage.addEventListener('click', function() {
            fRefImage.value = '';
            refImagePreview.innerHTML = '<i class="bi bi-image"></i>';
            refImageUpload.classList.remove('has-file');
        });
    }

    /* Form: Reference File Upload */
    var fRefFile = document.getElementById('fRefFile'),
        refFileUpload = document.getElementById('refFileUpload'),
        refFileTitle = document.getElementById('refFileTitle'),
        btnRemoveRefFile = document.getElementById('btnRemoveRefFile');

    if (fRefFile) {
        fRefFile.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                refFileTitle.innerHTML = '<i class="bi bi-file-earmark-check-fill" style="font-size:28px; color:var(--green)"></i><span>' + this.files[0].name + '</span>';
                refFileUpload.classList.add('has-file');
            }
        });
    }

    if (btnRemoveRefFile) {
        btnRemoveRefFile.addEventListener('click', function() {
            fRefFile.value = '';
            refFileTitle.innerHTML = '<i class="bi bi-file-earmark-arrow-up" style="font-size:28px; color:var(--cyan)"></i><span>Klik atau seret file dokumen ke sini</span>';
            refFileUpload.classList.remove('has-file');
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

    /* Form: reset */
    var btnReset = document.getElementById('btnReset');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            document.getElementById('formRequestProject').reset();
            if (editorInstance) editorInstance.setData('');
            if (fpStart) fpStart.clear();
            refImagePreview.innerHTML = '<i class="bi bi-image"></i>';
            refImageUpload.classList.remove('has-file');
            refFileTitle.innerHTML = '<i class="bi bi-file-earmark-arrow-up" style="font-size:28px; color:var(--cyan)"></i><span>Klik atau seret file dokumen ke sini</span>';
            refFileUpload.classList.remove('has-file');
            colorHex.textContent = '#4F46E5';
            document.querySelectorAll('.fg.has-err').forEach(e => e.classList.remove('has-err'));
            document.querySelectorAll('.err').forEach(e => e.classList.remove('err'));
        });
    }

    /* Form: Submit Handler */
    if (btnSave) {
        btnSave.addEventListener('click', function() {
            if (editorInstance) {
                editorInstance.updateSourceElement();
            }

            var ok = true;
            var checks = [
                { el: document.getElementById('fNama'), fg: document.getElementById('fNama').closest('.fg') },
                { el: document.getElementById('fAppType'), fg: document.getElementById('fAppType').closest('.fg') },
                { el: fs, fg: fs.closest('.fg') }
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
        var form = document.getElementById('formRequestProject');
        var formData = new FormData(form);

        btnSave.innerHTML = '<span><i class="bi bi-hourglass-split"></i>&nbsp;Mengirim...</span>';
        btnSave.style.opacity = '.7';
        btnSave.style.pointerEvents = 'none';

        axios.post(form.action, formData)
            .then(res => {
                SCA.toast({
                    type: "success",
                    title: "Berhasil!",
                    message: res.data?.message || "Permohonan Anda berhasil dikirim.",
                    position: "top-right",
                });
                setTimeout(() => {
                    window.location.href = window.projectIndexUrl || '/projects';
                }, 1000);
            })
            .catch(err => {
                btnSave.innerHTML = '<span><i class="bi bi-check2-circle"></i> Kirim Permohonan</span>';
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
