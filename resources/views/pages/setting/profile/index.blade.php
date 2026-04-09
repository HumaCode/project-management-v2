<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/profile.css') }}">
    @endpush

    @push('js')
        <script>
            /* Profile tabs */
            document.querySelectorAll('.ptab').forEach(function(tab) {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.ptab').forEach(function(t) {
                        t.classList.remove('active');
                    });
                    this.classList.add('active');
                    var pane = this.dataset.pane;
                    document.querySelectorAll('.pane').forEach(function(p) {
                        p.classList.remove('active');
                    });
                    var el = document.getElementById('pane-' + pane);
                    if (el) {
                        el.classList.add('active');
                    }
                });
            });

            /* Password toggle */
            document.querySelectorAll('.pw-eye').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var inp = this.previousElementSibling;
                    if (inp.type === 'password') {
                        inp.type = 'text';
                        this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                    } else {
                        inp.type = 'password';
                        this.innerHTML = '<i class="bi bi-eye"></i>';
                    }
                });
            });

            /* Password strength */
            var pwNew = document.getElementById('pwNew');
            if (pwNew) {
                pwNew.addEventListener('input', function() {
                    var v = this.value,
                        bars = document.querySelectorAll('.pws-bar'),
                        lbl = document.getElementById('pwsLbl');
                    var score = 0;
                    if (v.length >= 8) score++;
                    if (/[A-Z]/.test(v)) score++;
                    if (/[0-9]/.test(v)) score++;
                    if (/[^A-Za-z0-9]/.test(v)) score++;
                    bars.forEach(function(b, i) {
                        b.classList.remove('weak', 'med', 'str');
                        if (score <= 1 && i < 1) b.classList.add('weak');
                        else if (score <= 2 && i < 2) b.classList.add('med');
                        else if (score >= 3 && i < score) b.classList.add('str');
                    });
                    var texts = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
                    var colors = ['', 'var(--err)', 'var(--warn)', 'var(--ok)', 'var(--cyan)'];
                    if (lbl) {
                        lbl.textContent = v ? texts[score] || texts[3] : '';
                        lbl.style.color = colors[score] || colors[3];
                    }
                });
            }

            /* Avatar upload preview */
            var avUp = document.getElementById('avUpload');
            if (avUp) {
                avUp.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (!file) return;
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        var img = document.getElementById('avImg');
                        if (img) {
                            img.src = ev.target.result;
                        } else {
                            var inner = document.getElementById('avInner');
                            if (inner) {
                                inner.innerHTML = '<img id="avImg" src="' + ev.target.result +
                                    '" style="width:100%;height:100%;object-fit:cover;border-radius:50%">';
                            }
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('avUpload');

                if (fileInput) {
                    fileInput.addEventListener('change', function(event) {
                        const file = event.target.files[0];

                        if (file) {
                            // Validasi ukuran file (Maksimal 2MB sesuai keterangan di UI)
                            if (file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file terlalu besar. Maksimal 2MB!');
                                this.value = ''; // Reset pilihan
                                return;
                            }

                            // Buat URL sementara untuk file gambar yang dipilih
                            const imgUrl = URL.createObjectURL(file);
                            let previewEl = document.getElementById('avPreview');

                            // Cek apakah elemen preview saat ini adalah DIV (inisial teks)
                            if (previewEl.tagName.toLowerCase() === 'div') {
                                // Jika DIV, kita buat tag IMG baru untuk menggantikannya
                                const newImg = document.createElement('img');
                                newImg.id = 'avPreview';
                                newImg.className = 'av-inner';
                                newImg.style.cssText =
                                    'width:84px;height:84px;object-fit:cover;border-radius:50%;display:block;margin:0 auto;';
                                newImg.src = imgUrl;

                                // Gantikan DIV lama dengan IMG yang baru
                                previewEl.parentNode.replaceChild(newImg, previewEl);
                            } else {
                                // Jika sudah berupa IMG (sudah punya avatar sebelumnya), langsung ganti src-nya
                                previewEl.src = imgUrl;
                            }
                        }
                    });
                }
            });

            /* Cover upload hint */
            document.querySelectorAll('.cover-edit').forEach(function(b) {
                b.addEventListener('click', function() {
                    var inp = document.createElement('input');
                    inp.type = 'file';
                    inp.accept = 'image/*';
                    inp.onchange = function(e) {
                        var f = e.target.files[0];
                        if (!f) return;
                        var r = new FileReader();
                        r.onload = function(ev) {
                            var cov = document.querySelector('.hero-cover');
                            if (cov) {
                                cov.style.backgroundImage = 'url(' + ev.target.result + ')';
                                cov.style.backgroundSize = 'cover';
                                cov.style.backgroundPosition = 'center';
                            }
                        };
                        r.readAsDataURL(f);
                    };
                    inp.click();
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#form-edit').on('submit', function(e) {
                    e.preventDefault(); // Mencegah form reload halaman

                    let form = $(this);
                    let url = form.data('url');
                    let submitBtn = form.find('.btn-save');

                    // Simpan isi asli tombol agar bisa dikembalikan nanti
                    let originalBtnHtml = submitBtn.html();

                    // Gunakan FormData untuk menangkap semua input teks DAN file
                    let formData = new FormData(this);

                    // Trik Laravel: Karena kita upload file, request harus POST, 
                    // tapi kita beri tahu Laravel bahwa ini sebenarnya adalah PUT
                    formData.append('_method', 'PUT');

                    // 1. Ubah tombol jadi disabled dan tampilkan spinner
                    submitBtn.prop('disabled', true);
                    // Ganti icon floppy disk dengan spinner bawaan bootstrap (atau sesuaikan dengan class CSS spinner-mu)
                    submitBtn.html(
                        '<span><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="margin-right: 8px;"></span>Menyimpan...</span>'
                        );

                    // 2. Tunda selama setengah detik (500 ms) sebelum menjalankan AJAX
                    setTimeout(function() {

                        $.ajax({
                            url: url,
                            type: 'POST', // Wajib POST untuk FormData yang berisi file
                            data: formData,
                            processData: false, // Wajib false agar jQuery tidak mengubah file menjadi string
                            contentType: false, // Wajib false agar jQuery tidak salah set header Content-Type
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                // Kembalikan tombol ke kondisi semula
                                submitBtn.prop('disabled', false);
                                submitBtn.html(originalBtnHtml);

                                if (res.success) {
                                    SCA.toast({
                                        type: "success",
                                        title: "Berhasil!",
                                        message: res.message ||
                                            "Profil berhasil diperbarui.",
                                        position: "top-right"
                                    });
                                } else {
                                    SCA.toast({
                                        type: "error", // Sesuaikan jika kamu pakai 'danger'
                                        title: "Gagal!",
                                        message: res.message ||
                                            "Gagal memperbarui profil.",
                                        position: "top-right"
                                    });
                                }
                            },
                            error: function(xhr) {
                                // Kembalikan tombol ke kondisi semula
                                submitBtn.prop('disabled', false);
                                submitBtn.html(originalBtnHtml);

                                let errorMessage = "Terjadi kesalahan sistem.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                SCA.toast({
                                    type: "error", // Sesuaikan jika kamu pakai 'danger'
                                    title: "Error!",
                                    message: errorMessage,
                                    position: "top-right"
                                });
                            }
                        });

                    }, 500); // Penundaan 500 milidetik
                });
            });
        </script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="bc d-none d-xl-flex">
            <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
            <span class="sep"><i class="bi bi-chevron-right"></i></span>
            <span class="here">{{ $title }}</span>
        </div>
    </div>

    <!-- ══════════ HERO CARD ══════════ -->
    <div class="hero-card" data-aos="fade-up" data-aos-delay="30">
        <!-- Cover / Banner -->
        <div class="hero-cover">
            <div class="cover-pattern"></div>
            <div class="cover-grid"></div>
            <div class="cover-glow"></div>
            <button class="cover-edit"><i class="bi bi-camera-fill"></i> Ganti Cover</button>
        </div>
        <!-- Meta row -->
        <div class="hero-meta">
            <!-- Avatar -->
            <div class="av-wrap">
                @if ($profile->avatar)
                    <div class="av-ring">
                        <img src="{{ asset('storage/avatar/' . $profile->avatar) }}" alt="{{ $profile->name }}"
                            class="av-inner" style="object-fit: cover; width: 100%; height: 100%; border-radius: 50%;">
                    </div>
                @else
                    <div class="av-ring">
                        <div class="av-inner" id="avInner">{{ $profile->initials }}</div>
                    </div>
                @endif
                <div class="av-online" title="Online"></div>

            </div>
            <!-- Names & badges -->
            <div class="hero-names">
                <div class="hero-fullname">{{ $profile->name }}</div>
                <div class="hero-username">@ {{ $profile->username }} &middot; {{ $profile->email }}</div>
                <div class="hero-badges">
                    <span class="hbadge hb-admin"><i class="bi bi-shield-fill-check"></i>
                        {{ $profile->role_name ?? '-' }}</span>
                    <span class="hbadge hb-active"><span class="hb-dot"></span> Online</span>
                    <span class="hbadge"
                        style="background:rgba(0,200,255,.08);color:var(--cyan);border:1px solid rgba(0,200,255,.2)"><i
                            class="bi bi-calendar3"></i> Bergabung {{ tgl_indo($profile->created_at) }}</span>
                </div>
            </div>
            <!-- Actions -->
            <div class="hero-actions">
                <button class="btn-sec" onclick="document.querySelector('[data-pane=edit]').click()"><i
                        class="bi bi-pencil-fill"></i> Edit Profil</button>
                <button class="btn-p" onclick="document.querySelector('[data-pane=keamanan]').click()"><span><i
                            class="bi bi-shield-lock-fill"></i> Keamanan</span></button>
            </div>
        </div>
        <!-- Quick stats bar -->
        <div class="hero-qstats">
            <div class="hqs-item c">
                <div class="hqs-val">24</div>
                <div class="hqs-lbl">Proyek</div>
            </div>
            <div class="hqs-item g">
                <div class="hqs-val">186</div>
                <div class="hqs-lbl">Task Selesai</div>
            </div>
            <div class="hqs-item a">
                <div class="hqs-val">42</div>
                <div class="hqs-lbl">Dokumen</div>
            </div>
            <div class="hqs-item p">
                <div class="hqs-val">8</div>
                <div class="hqs-lbl">Tim</div>
            </div>
        </div>
    </div>

    <!-- ══════════ TABS ══════════ -->
    <div class="prof-tabs" data-aos="fade-up" data-aos-delay="50">
        <div class="ptab active" data-pane="edit"><i class="bi bi-person-fill"></i><span>Edit Profil</span></div>
        <div class="ptab" data-pane="keamanan"><i class="bi bi-shield-lock-fill"></i><span>Keamanan</span></div>
        <div class="ptab" data-pane="notifikasi"><i class="bi bi-bell-fill"></i><span>Notifikasi</span></div>
        <div class="ptab" data-pane="aktivitas"><i class="bi bi-clock-history"></i><span>Aktivitas</span></div>
        <div class="ptab" data-pane="sesi"><i class="bi bi-laptop"></i><span>Sesi Aktif</span></div>
        <div class="ptab" data-pane="bahaya"><i class="bi bi-exclamation-triangle-fill"></i><span>Zona Bahaya</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════ PANE: EDIT PROFIL ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-edit')

    <!-- ══════════════════════════════════════════════ PANE: KEAMANAN ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-keamanan')


    <!-- ══════════════════════════════════════════════ PANE: NOTIFIKASI ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-notifikasi')


    <!-- ══════════════════════════════════════════════ PANE: AKTIVITAS ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-aktivitas')


    <!-- ══════════════════════════════════════════════ PANE: SESI AKTIF ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-sesi')


    <!-- ══════════════════════════════════════════════ PANE: ZONA BAHAYA ══════════════════════════════════════════════ -->
    @include('pages.setting.profile.partials.form-bahaya')


</x-master-layout>
