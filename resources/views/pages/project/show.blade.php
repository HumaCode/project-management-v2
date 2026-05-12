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
        <div class="ph-icon"><i class="bi bi-kanban-fill"></i></div>
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
        <a href="{{ route('projects.index') }}" class="btn-act btn-outline"><i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span></a>
        <a href="#" class="btn-act btn-outline"><i class="bi bi-pencil-fill"></i> <span class="d-none d-sm-inline">Edit</span></a>
        <button class="btn-act btn-primary-alt" data-bs-toggle="modal" data-bs-target="#uploadModal">
          <span><i class="bi bi-cloud-upload-fill"></i> <span class="d-none d-sm-inline">Upload Dokumen</span><span class="d-sm-none">Upload</span></span>
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
              <div class="info-val"><span class="tag tag-prog" style="padding:2px 8px"><span class="tdot"></span>In Progress</span></div>
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
                Pengembangan sistem informasi untuk mendukung keterbukaan informasi publik di lingkungan Pemerintah Kota Pekalongan sesuai UU KIP No. 14 Tahun 2008.
              </div>
            </div>
            <div class="info-item" style="grid-column:1/-1">
              <div class="info-label" style="margin-bottom:10px">Person In Charge</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="av-stack">
                    <div class="av" title="Andi Wijaya" style="background:linear-gradient(135deg,#0072c6,#00c8ff)">AW</div>
                    <div class="av" title="Siti Rahayu" style="background:linear-gradient(135deg,#6d28d9,#a78bfa)">SR</div>
                    <div class="av" title="Deni Kurnia" style="background:linear-gradient(135deg,#065f46,#00e5a0)">DK</div>
                    <div class="av" title="Rina Marlina" style="background:linear-gradient(135deg,#92400e,#f59e0b)">RM</div>
                    <div class="av more" title="+1 lainnya">+1</div>
                  </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:5px">
                  <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                    <div style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#0072c6,#00c8ff);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">AW</div>
                    <span>Andi Wijaya</span><span style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Developer</span>
                  </div>
                  <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                    <div style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#6d28d9,#a78bfa);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">SR</div>
                    <span>Siti Rahayu</span><span style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Designer</span>
                  </div>
                  <div style="display:flex;align-items:center;gap:8px;font-size:12.5px">
                    <div style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#065f46,#00e5a0);display:grid;place-items:center;font-size:8px;font-weight:700;font-family:var(--mono);color:#fff;flex-shrink:0">DK</div>
                    <span>Deni Kurnia</span><span style="font-size:10.5px;color:var(--muted);font-family:var(--mono);margin-left:auto">Backend Dev</span>
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
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px">
              <div style="position:relative;flex:1;max-width:280px">
                <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px"></i>
                <input type="text" placeholder="Cari dokumen..." style="width:100%;height:34px;border-radius:var(--rs);background:rgba(0,200,255,.05);border:1px solid var(--bd);padding:0 10px 0 32px;color:var(--txt);font-family:var(--font);font-size:12.5px;outline:none" id="docSearch"/>
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
                    <td><div class="doc-name"><div class="doc-ico pdf"><i class="bi bi-file-earmark-pdf-fill"></i></div><div><div class="doc-nm">Dokumen Spesifikasi Teknis<span class="doc-ver">v3</span></div><div style="font-size:11px;color:var(--muted)">PDF · 4.2 MB</div></div></div></td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v3.0</span></td>
                    <td class="d-none d-sm-table-cell doc-size">4.2 MB</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">12 Feb 2025</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Andi Wijaya</td>
                    <td><div style="display:flex;gap:4px;justify-content:center">
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Download"><i class="bi bi-download"></i></button>
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Hapus"><i class="bi bi-trash3"></i></button>
                    </div></td>
                  </tr>
                  <tr>
                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">02</td>
                    <td><div class="doc-name"><div class="doc-ico xls"><i class="bi bi-file-earmark-spreadsheet-fill"></i></div><div><div class="doc-nm">RAB Proyek 2025<span class="doc-ver">v2</span></div><div style="font-size:11px;color:var(--muted)">XLSX · 1.8 MB</div></div></div></td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v2.1</span></td>
                    <td class="d-none d-sm-table-cell doc-size">1.8 MB</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">08 Feb 2025</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Rina Marlina</td>
                    <td><div style="display:flex;gap:4px;justify-content:center">
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Download"><i class="bi bi-download"></i></button>
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Hapus"><i class="bi bi-trash3"></i></button>
                    </div></td>
                  </tr>
                  <tr>
                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">03</td>
                    <td><div class="doc-name"><div class="doc-ico doc"><i class="bi bi-file-earmark-word-fill"></i></div><div><div class="doc-nm">Berita Acara Kick-off<span class="doc-ver">v1</span></div><div style="font-size:11px;color:var(--muted)">DOCX · 680 KB</div></div></div></td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v1.0</span></td>
                    <td class="d-none d-sm-table-cell doc-size">680 KB</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">03 Jan 2025</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Budi Santoso</td>
                    <td><div style="display:flex;gap:4px;justify-content:center">
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Download"><i class="bi bi-download"></i></button>
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Hapus"><i class="bi bi-trash3"></i></button>
                    </div></td>
                  </tr>
                  <tr>
                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">04</td>
                    <td><div class="doc-name"><div class="doc-ico zip"><i class="bi bi-file-earmark-zip-fill"></i></div><div><div class="doc-nm">Source Code Sprint 3<span class="doc-ver">v1</span></div><div style="font-size:11px;color:var(--muted)">ZIP · 18.4 MB</div></div></div></td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v1.0</span></td>
                    <td class="d-none d-sm-table-cell doc-size">18.4 MB</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">20 Feb 2025</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Deni Kurnia</td>
                    <td><div style="display:flex;gap:4px;justify-content:center">
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Download"><i class="bi bi-download"></i></button>
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Hapus"><i class="bi bi-trash3"></i></button>
                    </div></td>
                  </tr>
                  <tr>
                    <td style="color:var(--muted);font-family:var(--mono);font-size:11px">05</td>
                    <td><div class="doc-name"><div class="doc-ico pdf"><i class="bi bi-file-earmark-pdf-fill"></i></div><div><div class="doc-nm">Laporan Progress Bulan 1<span class="doc-ver">v1</span></div><div style="font-size:11px;color:var(--muted)">PDF · 2.1 MB</div></div></div></td>
                    <td class="col-hide-xs"><span style="font-family:var(--mono);font-size:11.5px;color:var(--cyan)">v1.0</span></td>
                    <td class="d-none d-sm-table-cell doc-size">2.1 MB</td>
                    <td class="d-none d-md-table-cell" style="font-family:var(--mono);font-size:11.5px;color:var(--muted)">31 Jan 2025</td>
                    <td class="d-none d-md-table-cell" style="font-size:12.5px">Rina Marlina</td>
                    <td><div style="display:flex;gap:4px;justify-content:center">
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(0,200,255,.08);border:1px solid rgba(0,200,255,.15);color:var(--cyan);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Download"><i class="bi bi-download"></i></button>
                      <button style="width:28px;height:28px;border-radius:6px;background:rgba(255,77,109,.07);border:1px solid rgba(255,77,109,.2);color:var(--err);cursor:pointer;display:grid;place-items:center;font-size:13px;transition:all .2s" title="Hapus"><i class="bi bi-trash3"></i></button>
                    </div></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div style="padding:10px 0 4px;text-align:center;font-family:var(--mono);font-size:11px;color:var(--muted)">
              Menampilkan 5 dari 24 dokumen &mdash; <a href="#" style="color:var(--cyan)">Lihat semua</a>
            </div>
          </div>

          <!-- Tab: Catatan -->
          <div class="tab-pane" id="tab-catatan">
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:0" id="noteWrap">
              <div class="note-card">
                <div class="note-head">
                  <div class="note-av">AW</div>
                  <div class="note-author">Andi Wijaya</div>
                  <div class="note-time">2 jam lalu</div>
                </div>
                <div class="note-body">Sprint 4 sudah dimulai. Fokus bulan ini adalah finalisasi modul permohonan informasi dan pengujian integrasi dengan sistem e-mail pemerintah. Tim backend sedang handle beberapa bug di API endpoint.</div>
                <div class="note-actions">
                  <button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button>
                  <button class="note-btn"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="note-btn" style="color:rgba(255,77,109,.5)"><i class="bi bi-trash3"></i> Hapus</button>
                </div>
              </div>
              <div class="note-card">
                <div class="note-head">
                  <div class="note-av" style="background:linear-gradient(135deg,#6d28d9,#a78bfa)">SR</div>
                  <div class="note-author">Siti Rahayu</div>
                  <div class="note-time">1 hari lalu</div>
                </div>
                <div class="note-body">Desain UI untuk halaman dashboard pemohon sudah direvisi sesuai feedback dari Pak Budi. Wireframe versi final sudah diupload ke folder Dokumen. Mohon review sebelum lanjut ke development.</div>
                <div class="note-actions">
                  <button class="note-btn"><i class="bi bi-reply-fill"></i> Balas</button>
                  <button class="note-btn"><i class="bi bi-pencil"></i> Edit</button>
                  <button class="note-btn" style="color:rgba(255,77,109,.5)"><i class="bi bi-trash3"></i> Hapus</button>
                </div>
              </div>
            </div>
            <div class="add-note-wrap">
              <div class="add-note-row">
                <div class="add-note-av">BS</div>
                <div style="flex:1">
                  <textarea class="add-note-box" placeholder="Tambahkan catatan atau komentar..." rows="3" id="noteInput"></textarea>
                  <div class="add-note-footer">
                    <button class="btn-note-submit" id="btnAddNote"><i class="bi bi-send-fill"></i> Kirim Catatan</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab: Aktivitas -->
          <div class="tab-pane" id="tab-aktivitas">
            <div class="timeline">
              <div class="tl-item">
                <div class="tl-dot-wrap"><div class="tl-dot fill"></div><div class="tl-line"></div></div>
                <div class="tl-content">
                  <div class="tl-title">Dokumen Source Code Sprint 3 diunggah</div>
                  <div class="tl-desc">File <strong>source-code-sprint3.zip</strong> (18.4 MB) berhasil diunggah ke sistem versioning.</div>
                  <div class="tl-user"><div class="tl-av">DK</div> Deni Kurnia</div>
                  <div class="tl-time">20 Feb 2025, 14:32</div>
                </div>
              </div>
              <div class="tl-item">
                <div class="tl-dot-wrap"><div class="tl-dot green"></div><div class="tl-line"></div></div>
                <div class="tl-content">
                  <div class="tl-title">Progress diperbarui: 65% → 72%</div>
                  <div class="tl-desc">Sprint 3 berhasil diselesaikan. Modul autentikasi dan manajemen pengguna sudah live di staging.</div>
                  <div class="tl-user"><div class="tl-av">AW</div> Andi Wijaya</div>
                  <div class="tl-time">19 Feb 2025, 09:15</div>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /crd tabs -->
      </div><!-- /col right -->
    </div><!-- /row -->

    <!-- Upload Modal -->
    <div class="modal fade modal-dark" id="uploadModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-color:rgba(0,200,255,.22)">
          <div class="modal-header" style="border-bottom-color:rgba(0,200,255,.12)">
            <h5 class="modal-title" style="display:flex;align-items:center;gap:9px"><i class="bi bi-cloud-upload-fill" style="color:var(--cyan)"></i> Upload Dokumen</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(.6)"></button>
          </div>
          <div class="modal-body">
            <div id="dropZone" style="border:2px dashed rgba(0,200,255,.2);border-radius:var(--r);padding:32px;text-align:center;cursor:pointer;transition:all .25s;background:rgba(0,200,255,.02)">
              <i class="bi bi-cloud-arrow-up-fill" style="font-size:40px;color:var(--cyan);opacity:.6;display:block;margin-bottom:10px"></i>
              <div style="font-size:14px;font-weight:600;margin-bottom:4px">Drag &amp; drop file di sini</div>
              <div style="font-size:12px;color:var(--muted)">atau klik untuk pilih file</div>
              <div style="font-size:11px;color:var(--muted);margin-top:8px;font-family:var(--mono)">PDF, DOCX, XLSX, ZIP &mdash; Maks. 50MB</div>
            </div>
            <div style="margin-top:14px">
              <label style="font-size:11.5px;font-weight:700;color:var(--dim);font-family:var(--mono);text-transform:uppercase;letter-spacing:.8px;display:block;margin-bottom:6px">Deskripsi Dokumen</label>
              <input type="text" placeholder="Opsional..." style="width:100%;height:40px;border-radius:var(--rs);background:rgba(0,200,255,.04);border:1px solid var(--bd);padding:0 12px;color:var(--txt);font-family:var(--font);font-size:13px;outline:none"/>
            </div>
          </div>
          <div class="modal-footer" style="border-top:1px solid var(--bd);padding:14px 20px;gap:8px">
            <button class="btn-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
            <button class="btn-act btn-primary-alt" style="height:38px"><span><i class="bi bi-cloud-upload-fill"></i> Upload</span></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade modal-dark" id="deleteModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill" style="color:var(--err)"></i> Hapus Project</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(.6)"></button>
          </div>
          <div class="modal-body">
            <div class="logout-warn">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <p>Apakah Anda yakin ingin <strong>menghapus project</strong> ini? Semua dokumen dan catatan terkait akan ikut terhapus secara permanen.</p>
            </div>
            <p style="font-size:13px;color:var(--muted);font-family:var(--mono)"><i class="bi bi-kanban"></i> Project: <span style="color:var(--cyan)">Sistem Informasi PPID Kota Pekalongan</span></p>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
            <button class="btn-logout"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
          </div>
          <div class="modal-drain"><div class="modal-drain-fill" id="drainFill2"></div></div>
        </div>
      </div>
    </div>

</x-master-layout>
