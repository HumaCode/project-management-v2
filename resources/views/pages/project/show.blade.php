<x-master-layout>

    @section('title', $title)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project-detail.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('assets/auth/backend/js/project-detail.js') }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="ph-wrap" data-aos="fade-down">
        <div class="ph-left">
            <div class="ph-icon"><i class="{{ $icon }}"></i></div>
            <div>
                <div class="ph-title">Sistem Informasi PPID Kota Pekalongan</div>
                <div class="ph-meta">
                    <span class="tag tag-prog"><span class="tdot"></span>In Progress</span>
                    <span class="sep d-none d-sm-inline">·</span>
                    <span class="breadcrumb-bar d-none d-sm-flex">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                        <span class="sep"><i class="bi bi-chevron-right"></i></span>
                        <a href="{{ route('projects.index') }}">Project</a>
                        <span class="sep"><i class="bi bi-chevron-right"></i></span>
                        <span class="here">Detail</span>
                    </span>
                </div>
            </div>
        </div>
        <div class="ph-actions">
            <a href="{{ route('projects.index') }}" class="btn-act btn-outline"><i class="bi bi-arrow-left"></i> <span
                    class="d-none d-sm-inline">Kembali</span></a>
            <a href="#" class="btn-act btn-outline"><i class="bi bi-pencil-fill"></i> <span
                    class="d-none d-sm-inline">Edit</span></a>
            <button class="btn-act btn-primary-alt" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <span><i class="bi bi-cloud-upload-fill"></i> <span class="d-none d-sm-inline">Upload
                        Dokumen</span><span class="d-sm-none">Upload</span></span>
            </button>
            <button class="btn-act btn-danger-outline" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </div>
    </div>

    <!-- Mini Stats -->
    <div class="mini-stats" data-aos="fade-up" data-aos-delay="40">
        <div class="msc">
            <div class="msc-ico c"><i class="bi bi-folder2-open"></i></div>
            <div>
                <div class="msc-val" data-count="24">0</div>
                <div class="msc-lbl">Dokumen</div>
            </div>
        </div>
        <div class="msc">
            <div class="msc-ico g"><i class="bi bi-journal-check"></i></div>
            <div>
                <div class="msc-val" data-count="8">0</div>
                <div class="msc-lbl">Catatan</div>
            </div>
        </div>
        <div class="msc">
            <div class="msc-ico w"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="msc-val" data-count="5">0</div>
                <div class="msc-lbl">Anggota</div>
            </div>
        </div>
        <div class="msc">
            <div class="msc-ico r"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="msc-val" data-count="14">0</div>
                <div class="msc-lbl">Hari Tersisa</div>
            </div>
        </div>
    </div>

    <!-- Main Grid: Info + Tabs -->
    <div class="row g-3 mb-22">

        <!-- Left: Info Card -->
        <div class="col-12 col-lg-4" data-aos="fade-up" data-aos-delay="60">
            <div class="crd h-100" style="display:flex;flex-direction:column">

                <!-- Progress -->
                <div class="prog-wrap">
                    <div class="prog-meta">
                        <span class="prog-label">Progress</span>
                        <span class="prog-pct" id="progPct">72%</span>
                    </div>
                    <div class="prog-track">
                        <div class="prog-fill" id="progFill" style="width:0%"></div>
                    </div>
                </div>

                <!-- Info grid -->
                <div class="info-grid" style="flex:1">
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-val"><span class="tag tag-prog" style="padding:2px 8px"><span
                                    class="tdot"></span>In Progress</span></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Dibuat Oleh</div>
                        <div class="info-val">Budi Santoso</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tgl Mulai</div>
                        <div class="info-val mono">01 Jan 2025</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Deadline</div>
                        <div class="info-val">
                            <div class="mono" style="margin-bottom:3px">19 Mar 2025</div>
                            <span class="dl-badge dl-warn"><i class="bi bi-exclamation-circle-fill"></i>H-14</span>
                        </div>
                    </div>
                    <div class="info-item" style="grid-column:1/-1;border-bottom:1px solid var(--bd)">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-val" style="font-weight:400;font-size:13px;color:var(--dim);line-height:1.65">
                            Pengembangan sistem informasi untuk mendukung keterbukaan informasi publik di lingkungan
                            Pemerintah Kota Pekalongan sesuai UU KIP No. 14 Tahun 2008.
                        </div>
                    </div>
                    <div class="info-item" style="grid-column:1/-1">
                        <div class="info-label" style="margin-bottom:10px">Person In Charge</div>
                        <div style="display:flex;flex-direction:column;gap:8px">
                            <div style="display:flex;align-items:center;gap:8px">
                                <div class="av-stack">
                                    <div class="av" title="Andi Wijaya"
                                        style="background:linear-gradient(135deg,#0072c6,#00c8ff)">AW</div>
                                    <div class="av" title="Siti Rahayu"
                                        style="background:linear-gradient(135deg,#6d28d9,#a78bfa)">SR</div>
                                    <div class="av" title="Deni Kurnia"
                                        style="background:linear-gradient(135deg,#065f46,#00e5a0)">DK</div>
                                    <div class="av" title="Rina Marlina"
                                        style="background:linear-gradient(135deg,#92400e,#f59e0b)">RM</div>
                                    <div class="av more" title="+1 lainnya">+1</div>
                                </div>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:5px">
                                <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                                    <div
                                        style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#0072c6,#00c8ff);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">
                                        AW</div>
                                    <span>Andi Wijaya</span><span
                                        style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Developer</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                                    <div
                                        style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#6d28d9,#a78bfa);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">
                                        SR</div>
                                    <span>Siti Rahayu</span><span
                                        style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Designer</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                                    <div
                                        style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#065f46,#00e5a0);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">
                                        DK</div>
                                    <span>Deni Kurnia</span><span
                                        style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Backend
                                        Dev</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /info-grid -->

            </div>
        </div><!-- /col left -->

        <!-- Right: Tabs -->
        <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="80">
            <div class="crd" style="display:flex;flex-direction:column;height:100%">

                <!-- Tab nav -->
                <div class="tab-nav" id="tabNav">
                    <button class="tab-btn active" data-tab="dokumen">
                        <i class="bi bi-folder2-open"></i>
                        <span>Dokumen</span>
                        <span class="tb-cnt">24</span>
                    </button>
                    <button class="tab-btn" data-tab="catatan">
                        <i class="bi bi-journal-text"></i>
                        <span>Catatan</span>
                        <span class="tb-cnt">8</span>
                    </button>
                    <button class="tab-btn" data-tab="aktivitas">
                        <i class="bi bi-activity"></i>
                        <span>Aktivitas</span>
                    </button>
                </div>

                <!-- Tab: Dokumen -->
                <div class="tab-pane active" id="tab-dokumen">
                    <div
                        style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px">
                        <div style="position:relative;flex:1;max-width:280px">
                            <i class="bi bi-search"
                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px"></i>
                            <input type="text" placeholder="Cari dokumen..."
                                style="width:100%;height:34px;border-radius:var(--rs);background:rgba(0,200,255,.05);border:1px solid var(--bd);padding:0 10px 0 32px;color:var(--txt);font-family:var(--font);font-size:12.5px;outline:none"
                                id="docSearch" />
                        </div>
                        <button class="btn-act btn-primary-alt" style="height:34px;font-size:12.5px">
                            <span><i class="bi bi-plus-lg"></i> Tambah</span>
                        </button>
                    </div>
                    <div class="tbl-wrap">
                        <table class="doc-tbl">
                            <thead>
                                <tr>
                                    <th style="width:36px">#</th>
                                    <th>Nama Dokumen</th>
                                    <th class="col-hide-xs">Versi</th>
                                    <th class="d-none d-sm-table-cell">Ukuran</th>
                                    <th class="d-none d-md-table-cell">Diunggah</th>
                                    <th class="d-none d-md-table-cell">Oleh</th>
                                    <th style="width:80px;text-align:center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">01</td>
                                    <td>
                                        <div class="doc-name">
                                            <div class="doc-ico pdf"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                            <div>
                                                <div class="doc-nm">Dokumen Spesifikasi Teknis<span
                                                        class="doc-ver">v3</span></div>
                                                <div style="font-size:11px;color:var(--muted)">PDF · 4.2 MB</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-hide-xs"><span
                                            style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v3.0</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell doc-size">4.2 MB</td>
                                    <td class="d-none d-md-table-cell"
                                        style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">12 Feb 2025</td>
                                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Andi Wijaya</td>
                                    <td>
                                        <div style="display:flex;gap:4px;justify-content:center">
                                            <button
                                                style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s"
                                                title="Download"><i class="bi bi-download"></i></button>
                                            <button
                                                style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s"
                                                title="Hapus"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">02</td>
                                    <td>
                                        <div class="doc-name">
                                            <div class="doc-ico xls"><i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                            </div>
                                            <div>
                                                <div class="doc-nm">RAB Proyek 2025<span class="doc-ver">v2</span></div>
                                                <div style="font-size:11px;color:var(--muted)">XLSX · 1.8 MB</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-hide-xs"><span
                                            style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v2.1</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell doc-size">1.8 MB</td>
                                    <td class="d-none d-md-table-cell"
                                        style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">08 Feb 2025</td>
                                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Rina Marlina</td>
                                    <td>
                                        <div style="display:flex;gap:4px;justify-content:center">
                                            <button
                                                style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s"
                                                title="Download"><i class="bi bi-download"></i></button>
                                            <button
                                                style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s"
                                                title="Hapus"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        style="padding:10px 0 4px;text-align:center;font-family:var(--mono);font-size:11px;color:var(--muted)">
                        Menampilkan 2 dari 24 dokumen &mdash; <a href="#" style="color:var(--cyan)">Lihat semua</a>
                    </div>
                </div>

                <!-- Tab: Catatan -->
                <div class="tab-pane" id="tab-catatan">
                    <div style="display:flex;flex-direction:column;gap:12px;max-height:400px;overflow-y:auto;padding-right:8px"
                        id="noteWrap">
                        <div class="note-card">
                            <div class="note-head">
                                <div class="note-av">AW</div>
                                <div class="note-author">Andi Wijaya</div>
                                <div class="note-time">2 jam yang lalu</div>
                            </div>
                            <div class="note-body">Modul pengajuan informasi sudah selesai di-deploy ke server staging untuk
                                diuji coba oleh tim internal.</div>
                            <div class="note-actions">
                                <button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button>
                                <button class="note-btn"><i class="bi bi-pencil"></i> Edit</button>
                            </div>
                        </div>
                        <div class="note-card">
                            <div class="note-head">
                                <div class="note-av" style="background:linear-gradient(135deg,#6d28d9,#a78bfa)">SR</div>
                                <div class="note-author">Siti Rahayu</div>
                                <div class="note-time">5 jam yang lalu</div>
                            </div>
                            <div class="note-body">Sudah saya update desain untuk halaman tracking permohonan di Figma.
                                Silakan dicek link-nya di dokumen teknis.</div>
                            <div class="note-actions">
                                <button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button>
                            </div>
                        </div>
                    </div>
                    <div class="add-note-wrap">
                        <div class="add-note-row">
                            <div class="add-note-av">BS</div>
                            <textarea class="add-note-box" id="noteInput" placeholder="Tulis catatan atau update baru..."></textarea>
                        </div>
                        <div class="add-note-footer">
                            <button class="btn-note-submit" id="btnAddNote">
                                <i class="bi bi-send-fill"></i> Kirim Catatan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab: Aktivitas -->
                <div class="tab-pane" id="tab-aktivitas">
                    <div class="timeline">
                        <div class="tl-item">
                            <div class="tl-dot-wrap">
                                <div class="tl-dot fill"></div>
                                <div class="tl-line"></div>
                            </div>
                            <div class="tl-content">
                                <div class="tl-title">Dokumen Baru Diunggah</div>
                                <div class="tl-desc">Andi Wijaya mengunggah <b>Source Code Sprint 3.zip</b></div>
                                <div class="tl-time">20 Feb 2025 · 14:22</div>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot-wrap">
                                <div class="tl-dot fill green"></div>
                                <div class="tl-line"></div>
                            </div>
                            <div class="tl-content">
                                <div class="tl-title">Status Project Diperbarui</div>
                                <div class="tl-desc">Budi Santoso mengubah status project menjadi <b>In Progress</b></div>
                                <div class="tl-time">15 Feb 2025 · 09:10</div>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot-wrap">
                                <div class="tl-dot"></div>
                                <div class="tl-line"></div>
                            </div>
                            <div class="tl-content">
                                <div class="tl-title">Project Dibuat</div>
                                <div class="tl-desc">Project <b>Sistem Informasi PPID</b> berhasil diinisialisasi.</div>
                                <div class="tl-time">01 Jan 2025 · 08:00</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- /col right -->

    </div>

</x-master-layout>
