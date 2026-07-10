<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project-create.css') }}?v={{ time() }}">
        <style>
            /* ═══ Flatpickr Light Mode Overrides ═══ */
            html[data-theme="light"] .flatpickr-calendar {
                background: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05) !important;
                color: #1e293b !important;
            }
            html[data-theme="light"] .flatpickr-calendar::before,
            html[data-theme="light"] .flatpickr-calendar::after {
                border-bottom-color: #ffffff !important;
            }
            html[data-theme="light"] .flatpickr-months {
                background: #f8fafc !important;
                border-bottom: 1px solid #e2e8f0 !important;
                padding: 4px 0 !important;
            }
            html[data-theme="light"] .flatpickr-months .flatpickr-month {
                color: #1e293b !important;
                fill: #1e293b !important;
            }
            html[data-theme="light"] .flatpickr-current-month .numInputWrapper span.arrowUp::after {
                border-bottom-color: #1e293b !important;
            }
            html[data-theme="light"] .flatpickr-current-month .numInputWrapper span.arrowDown::after {
                border-top-color: #1e293b !important;
            }
            html[data-theme="light"] .flatpickr-months .flatpickr-prev-month,
            html[data-theme="light"] .flatpickr-months .flatpickr-next-month {
                color: #475569 !important;
                fill: #475569 !important;
            }
            html[data-theme="light"] .flatpickr-months .flatpickr-prev-month:hover,
            html[data-theme="light"] .flatpickr-months .flatpickr-next-month:hover {
                color: #0f172a !important;
                fill: #0f172a !important;
            }
            html[data-theme="light"] .flatpickr-current-month select.flatpickr-monthDropdown-months {
                color: #1e293b !important;
                background: transparent !important;
                font-weight: 600 !important;
            }
            html[data-theme="light"] .flatpickr-current-month select.flatpickr-monthDropdown-months option {
                background: #ffffff !important;
                color: #1e293b !important;
            }
            html[data-theme="light"] .flatpickr-current-month input.numInput.cur-year {
                color: #1e293b !important;
                font-weight: 600 !important;
            }
            html[data-theme="light"] .flatpickr-weekdays {
                background: #f1f5f9 !important;
                border-bottom: 1px solid #e2e8f0 !important;
                height: 28px !important;
                display: flex !important;
                align-items: center !important;
            }
            html[data-theme="light"] span.flatpickr-weekday {
                color: #475569 !important;
                font-weight: 600 !important;
            }
            html[data-theme="light"] .flatpickr-day {
                color: #334155 !important;
            }
            html[data-theme="light"] .flatpickr-day.prevMonthDay,
            html[data-theme="light"] .flatpickr-day.nextMonthDay {
                color: #cbd5e1 !important;
            }
            html[data-theme="light"] .flatpickr-day:hover,
            html[data-theme="light"] .flatpickr-day.prevMonthDay:hover,
            html[data-theme="light"] .flatpickr-day.nextMonthDay:hover,
            html[data-theme="light"] .flatpickr-day.focus,
            html[data-theme="light"] .flatpickr-day.prevMonthDay:focus,
            html[data-theme="light"] .flatpickr-day.nextMonthDay:focus {
                background: #f1f5f9 !important;
                border-color: #cbd5e1 !important;
                color: #0f172a !important;
            }
            html[data-theme="light"] .flatpickr-day.today {
                border-color: #3b82f6 !important;
                color: #1d4ed8 !important;
                font-weight: 600 !important;
            }
            html[data-theme="light"] .flatpickr-day.today:hover {
                background: #eff6ff !important;
                color: #1d4ed8 !important;
            }
            html[data-theme="light"] .flatpickr-day.selected,
            html[data-theme="light"] .flatpickr-day.selected:hover {
                background: #3b82f6 !important;
                border-color: #3b82f6 !important;
                color: #ffffff !important;
            }
        </style>
    @endpush

    <div class="page-header" data-aos="fade-down">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="ph-left">
                    <div class="ph-icon"><i class="{{ $icon }}"></i></div>
                    <div>
                        <div class="ph-title">{{ $title }}</div>
                        <div class="ph-sub">{{ $subtitle }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-start text-lg-end">
                <div class="breadcrumb-bar d-inline-flex">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                    <span class="sep"><i class="bi bi-chevron-right"></i></span>
                    <a href="{{ route('projects.index') }}">Project</a>
                    <span class="sep"><i class="bi bi-chevron-right"></i></span>
                    <span class="here">Permohonan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="crd mb-24" data-aos="fade-up">
        <div class="crd-head">
            <div class="crd-title"><i class="bi bi-pencil-square"></i> Form Pengajuan Permohonan Aplikasi</div>
            <span class="crd-badge"><i class="bi bi-asterisk" style="font-size:8px;margin-right:2px"></i>Wajib diisi</span>
        </div>

        <div class="form-body">
            <form id="formRequestProject" action="{{ route('project-request.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="fsec-title"><i class="bi bi-info-circle-fill"></i> Informasi Dasar Permohonan</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Nama Aplikasi / Sistem Yang Diusulkan <span class="req">*</span></label>
                            <div class="fiw">
                                <i class="bi bi-kanban-fill fi-ic"></i>
                                <input type="text" name="name" class="fi" id="fNama"
                                    placeholder="Contoh: Aplikasi Sistem E-Office Pemerintah Kota" maxlength="120"
                                    autocomplete="off" />
                            </div>
                            <div class="ccnt" id="cNama">0 / 120</div>
                            <div class="emsg">Nama aplikasi wajib diisi.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Jenis Aplikasi <span class="req">*</span></label>
                            <select name="app_type" class="fsl" id="fAppType">
                                <option value="" selected disabled>-- Pilih Jenis Aplikasi --</option>
                                <option value="website">Website</option>
                                <option value="android">Android</option>
                                <option value="website_android">Android &amp; Website</option>
                            </select>
                            <div class="emsg">Jenis aplikasi wajib dipilih.</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="fg">
                            <label>Deskripsi Kebutuhan &amp; Spesifikasi Fitur</label>
                            <textarea name="description" class="fa" id="fDesc"
                                placeholder="Jelaskan secara detail modul, alur bisnis, dan fitur yang Anda inginkan..." maxlength="500" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Tanggal <span class="req">*</span></label>
                            <div class="fiw">
                                <i class="bi bi-calendar-event fi-ic"></i>
                                <input type="text" name="start_date" class="fi" id="fStart"
                                     style="padding-left:40px" placeholder="Pilih tanggal..." readonly />
                            </div>
                            <div class="emsg">Tanggal wajib diisi.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Warna Aksen Desain Aplikasi</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="color" class="fcolor" id="fColor" value="#4f46e5" />
                                <div class="color-info">
                                    <span id="colorHex">#4F46E5</span>
                                    <small>Warna preferensi yang akan digunakan untuk tampilan aplikasi.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-file-earmark-zip-fill"></i> File Referensi &amp; Desain (Opsional)</div>

                <div class="row">
                    <!-- Gambar Referensi -->
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Gambar / Foto Referensi Desain</label>
                            <div class="thumb-upload" id="refImageUpload">
                                <button type="button" class="tu-remove" id="btnRemoveRefImage" title="Hapus Gambar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <div class="tu-preview" id="refImagePreview">
                                    <i class="bi bi-image"></i>
                                </div>
                                <div class="tu-info">
                                    <div class="tu-title">Klik atau seret gambar ke sini</div>
                                    <div class="tu-sub">Format: JPG, PNG, WEBP (Maks. 5MB)</div>
                                    <input type="file" name="reference_image" id="fRefImage" accept="image/*" style="display:none" />
                                    <button type="button" class="btn-tu" onclick="document.getElementById('fRefImage').click()">
                                        <i class="bi bi-cloud-upload"></i> Pilih Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Pendukung -->
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Dokumen Pendukung / TOR / Core Proposal</label>
                            <div class="thumb-upload" id="refFileUpload" style="height: auto; padding: 22px 16px;">
                                <button type="button" class="tu-remove" id="btnRemoveRefFile" title="Hapus File">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <div class="tu-info" style="margin-left: 0; text-align: center; width: 100%;">
                                    <div class="tu-title" id="refFileTitle" style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="bi bi-file-earmark-arrow-up" style="font-size: 28px; color: var(--cyan);"></i>
                                        <span>Klik atau seret file dokumen ke sini</span>
                                    </div>
                                    <div class="tu-sub">Format: PDF, DOCX, XLSX, ZIP, RAR (Maks. 20MB)</div>
                                    <input type="file" name="reference_file" id="fRefFile" accept=".pdf,.docx,.xlsx,.zip,.rar" style="display:none" />
                                    <button type="button" class="btn-tu" style="margin: 10px auto 0;" onclick="document.getElementById('fRefFile').click()">
                                        <i class="bi bi-file-earmark-plus"></i> Pilih File
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="factions mt-4">
                    <a href="{{ route('projects.index') }}" class="btn-batal"><i class="bi bi-arrow-left"></i> Kembali</a>
                    <button type="button" class="btn-batal" id="btnReset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span class="d-none d-sm-inline">Reset Form</span>
                    </button>
                    <button type="button" class="btn-save" id="btnSave">
                        <span><i class="bi bi-check2-circle"></i> Kirim Permohonan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        @if(config('services.recaptcha.site_key'))
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
            <script>
                window.recaptchaSiteKey = "{{ config('services.recaptcha.site_key') }}";
            </script>
        @endif
        <script>
            window.projectIndexUrl = "{{ route('projects.index') }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/project-request.js') }}?v={{ time() }}"></script>
    @endpush
</x-master-layout>
