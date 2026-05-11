<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dokumen.css') }}">
    @endpush

    @push('js')
        <script src="{{ asset('assets/auth/backend/js/dokumen.js') }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="bi bi-folder2-open"></i></div>
            <div>
                <div class="pg-title">Manajemen Dokumen</div>
                <div class="pg-sub">48 file &bull; 5 kategori &bull; 156 MB total</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-xl-flex">
                <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">Dokumen</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc c">
            <div class="sc-ico c"><i class="bi bi-files"></i></div>
            <div>
                <div class="sc-val" data-count="48">48</div>
                <div class="sc-lbl">Total Dokumen</div>
                <div class="sc-tr up"><i class="bi bi-arrow-up-short"></i>+6 bulan ini</div>
            </div>
        </div>
        <div class="sc g">
            <div class="sc-ico g"><i class="bi bi-hdd-fill"></i></div>
            <div>
                <div class="sc-val" data-count="156">156</div>
                <div class="sc-lbl">Digunakan (MB)</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>dari 500 MB</div>
            </div>
        </div>
        <div class="sc w">
            <div class="sc-ico w"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="sc-val" data-count="5">5</div>
                <div class="sc-lbl">Kategori</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>semua aktif</div>
            </div>
        </div>
        <div class="sc r">
            <div class="sc-ico r"><i class="bi bi-arrow-repeat"></i></div>
            <div>
                <div class="sc-val" data-count="12">12</div>
                <div class="sc-lbl">Revisi Bulan Ini</div>
                <div class="sc-tr dn"><i class="bi bi-arrow-up-short"></i>+3 minggu ini</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari nama dokumen..." />
        </div>
        <select class="nsel" style="min-width:140px">
            <option value="">Semua Kategori</option>
            <option>Spesifikasi</option>
            <option>RAB / Anggaran</option>
            <option>Laporan</option>
            <option>Source Code</option>
            <option>Berita Acara</option>
            <option>Desain</option>
        </select>
        <select class="nsel" style="min-width:160px">
            <option value="">Semua Project</option>
            <option>PPID Kota Pekalongan</option>
            <option>Sistem Absensi</option>
            <option>E-Commerce Mobile</option>
            <option>Manajemen Aset</option>
        </select>
        <select class="nsel" style="min-width:110px">
            <option value="">Semua Tipe</option>
            <option>PDF</option>
            <option>Excel</option>
            <option>Word</option>
            <option>ZIP</option>
            <option>Gambar</option>
        </select>
        <div class="tbar-right">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i> <span class="d-none d-sm-inline">Tambah</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th class="td-no">#</th>
                        <th>NAMA DOKUMEN</th>
                        <th>KATEGORI</th>
                        <th>PROJECT</th>
                        <th class="text-center">VERSI</th>
                        <th>UKURAN</th>
                        <th>TGL UPLOAD</th>
                        <th>DIUNGGAH OLEH</th>
                        <th style="text-align:center;width:116px">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-no">01</td>
                        <td>
                            <div class="td-file">
                                <div class="f-ico pdf"><i class="bi bi-file-earmark-pdf-fill"></i></div>
                                <div>
                                    <div class="f-nm">Dokumen Spesifikasi Teknis PPID</div>
                                    <div class="f-meta">PDF &bull; 4.2 MB</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="cat cat-s"><i class="bi bi-file-text"></i>Spesifikasi</span></td>
                        <td><span class="proj-chip"><i class="bi bi-kanban"
                                    style="font-size:10px;opacity:.6"></i>PPID Kota Pekalongan</span></td>
                        <td class="text-center"><span class="vbadge">v3.0</span></td>
                        <td class="td-sz">4.2 MB</td>
                        <td class="td-dt">12 Feb 2025</td>
                        <td>
                            <div class="td-usr">
                                <div class="uav" style="background:linear-gradient(135deg,#0072c6,#00c8ff)">AW</div>
                                Andi Wijaya
                            </div>
                        </td>
                        <td>
                            <div class="act-row"><button class="ibtn ib-v" title="Lihat"><i
                                        class="bi bi-eye"></i></button><button class="ibtn ib-d" title="Unduh"><i
                                        class="bi bi-download"></i></button><button class="ibtn ib-e" title="Edit"><i
                                        class="bi bi-pencil"></i></button><button class="ibtn ib-x" title="Hapus"
                                    data-nm="Dokumen Spesifikasi Teknis PPID" data-bs-toggle="modal"
                                    data-bs-target="#delModal"><i class="bi bi-trash3"></i></button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">02</td>
                        <td>
                            <div class="td-file">
                                <div class="f-ico xls"><i class="bi bi-file-earmark-spreadsheet-fill"></i></div>
                                <div>
                                    <div class="f-nm">RAB Proyek PPID 2025</div>
                                    <div class="f-meta">XLSX &bull; 1.8 MB</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="cat cat-r"><i class="bi bi-currency-dollar"></i>RAB / Anggaran</span></td>
                        <td><span class="proj-chip"><i class="bi bi-kanban"
                                    style="font-size:10px;opacity:.6"></i>PPID Kota Pekalongan</span></td>
                        <td class="text-center"><span class="vbadge">v2.1</span></td>
                        <td class="td-sz">1.8 MB</td>
                        <td class="td-dt">08 Feb 2025</td>
                        <td>
                            <div class="td-usr">
                                <div class="uav" style="background:linear-gradient(135deg,#92400e,#f59e0b)">RM</div>
                                Rina Marlina
                            </div>
                        </td>
                        <td>
                            <div class="act-row"><button class="ibtn ib-v" title="Lihat"><i
                                        class="bi bi-eye"></i></button><button class="ibtn ib-d" title="Unduh"><i
                                        class="bi bi-download"></i></button><button class="ibtn ib-e" title="Edit"><i
                                        class="bi bi-pencil"></i></button><button class="ibtn ib-x" title="Hapus"
                                    data-nm="RAB Proyek PPID 2025" data-bs-toggle="modal"
                                    data-bs-target="#delModal"><i class="bi bi-trash3"></i></button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">03</td>
                        <td>
                            <div class="td-file">
                                <div class="f-ico doc"><i class="bi bi-file-earmark-word-fill"></i></div>
                                <div>
                                    <div class="f-nm">Berita Acara Kick-off Meeting</div>
                                    <div class="f-meta">DOCX &bull; 680 KB</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="cat cat-b"><i class="bi bi-file-earmark-check"></i>Berita Acara</span></td>
                        <td><span class="proj-chip"><i class="bi bi-kanban"
                                    style="font-size:10px;opacity:.6"></i>PPID Kota Pekalongan</span></td>
                        <td class="text-center"><span class="vbadge">v1.0</span></td>
                        <td class="td-sz">680 KB</td>
                        <td class="td-dt">03 Jan 2025</td>
                        <td>
                            <div class="td-usr">
                                <div class="uav" style="background:linear-gradient(135deg,#1e3a5f,#3d6080)">BS</div>
                                Budi Santoso
                            </div>
                        </td>
                        <td>
                            <div class="act-row"><button class="ibtn ib-v" title="Lihat"><i
                                        class="bi bi-eye"></i></button><button class="ibtn ib-d" title="Unduh"><i
                                        class="bi bi-download"></i></button><button class="ibtn ib-e" title="Edit"><i
                                        class="bi bi-pencil"></i></button><button class="ibtn ib-x" title="Hapus"
                                    data-nm="Berita Acara Kick-off Meeting" data-bs-toggle="modal"
                                    data-bs-target="#delModal"><i class="bi bi-trash3"></i></button></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>12</b> dari <b>48</b> data</div>
            <div class="pag">
                <button class="pb" disabled><i class="bi bi-chevron-left"></i></button>
                <button class="pb active">1</button>
                <button class="pb">2</button>
                <button class="pb">3</button>
                <span class="pag-dot">&hellip;</span>
                <button class="pb">4</button>
                <button class="pb"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>

    @push('modals')
        <!-- MODALS -->
        <!-- Modal Tambah -->
        <div class="modal fade m-dark m-cyan" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-cloud-upload-fill"></i> Tambah Dokumen Baru</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <!-- Drop zone -->
                        <div class="drop-zone" id="dropZone">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <div class="dt">Drag &amp; drop file di sini</div>
                            <div class="ds">atau <span style="color:var(--cyan);cursor:pointer">klik untuk memilih file</span></div>
                            <div class="dk">PDF, DOCX, XLSX, PPTX, ZIP, PNG &mdash; Maks. 50 MB</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">NAMA DOKUMEN<span class="req">*</span></label>
                                    <input type="text" class="fmi" placeholder="Masukkan nama dokumen..."/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">VERSI DOKUMEN</label>
                                    <input type="text" class="fmi" placeholder="Contoh: v1.0, v2.3..."/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">KATEGORI<span class="req">*</span></label>
                                    <select id="sel2Kat" style="width:100%">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="s">Spesifikasi</option>
                                        <option value="r">RAB / Anggaran</option>
                                        <option value="l">Laporan</option>
                                        <option value="c">Source Code</option>
                                        <option value="b">Berita Acara</option>
                                        <option value="d">Desain</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">PROJECT TERKAIT</label>
                                    <select id="sel2Proj" style="width:100%">
                                        <option value="">-- Pilih Project --</option>
                                        <option value="ppid">PPID Kota Pekalongan</option>
                                        <option value="absen">Sistem Absensi</option>
                                        <option value="ecom">E-Commerce Mobile</option>
                                        <option value="aset">Manajemen Aset</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">DIUNGGAH OLEH<span class="req">*</span></label>
                                    <select id="sel2User" style="width:100%">
                                        <option value="">-- Pilih Pengguna --</option>
                                        <option value="bs">Budi Santoso (Admin)</option>
                                        <option value="aw">Andi Wijaya (Developer)</option>
                                        <option value="sr">Siti Rahayu (Designer)</option>
                                        <option value="dk">Deni Kurnia (Backend Dev)</option>
                                        <option value="rm">Rina Marlina (Manager)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">TANGGAL UPLOAD</label>
                                    <input type="date" class="fmi" style="color-scheme:dark"/>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">KETERANGAN</label>
                                    <textarea class="fmta" placeholder="Deskripsi singkat dokumen ini (opsional)..." style="height: 100px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                        <button class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Dokumen</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="modal fade m-dark m-red" id="delModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-trash3-fill"></i> Hapus Dokumen</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <p>Anda akan menghapus dokumen <strong id="delDocName">ini</strong>. Semua versi dan riwayat akan ikut terhapus secara permanen.</p>
                        </div>
                        <p style="font-size:12px;color:var(--muted);font-family:var(--mono)"><i class="bi bi-info-circle"></i>&nbsp;Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
                        <button class="btn-mdel"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
                    </div>
                    <div class="modal-drain"><div class="drain-fill" id="drainDel"></div></div>
                </div>
            </div>
        </div>
    @endpush
</x-master-layout>

