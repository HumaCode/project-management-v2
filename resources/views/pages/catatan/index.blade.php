<x-master-layout>
    @section('title', $title)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/catatan.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="{{ asset('assets/auth/backend/js/catatan.js') }}"></script>
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
        <div class="pg-actions">
            <div class="bc d-none d-xl-flex">
                <a href="#"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i> Tambah Catatan</span>
            </button>
        </div>



    </div>

    <!-- Stat Cards -->
    <div class="stat-row" data-aos="fade-up" data-aos-delay="40">
        <div class="sc">
            <div class="sc-ico c"><i class="bi bi-journal-text"></i></div>
            <div>
                <div class="sc-val" data-count="32">0</div>
                <div class="sc-lbl">Total Catatan</div>
                <div class="sc-tr up"><i class="bi bi-arrow-up-short"></i>+5 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico r"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="sc-val" data-count="6">0</div>
                <div class="sc-lbl">Prioritas Tinggi</div>
                <div class="sc-tr dn"><i class="bi bi-arrow-up-short"></i>+2 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico g"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="sc-val" data-count="4">0</div>
                <div class="sc-lbl">Kategori</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>semua aktif</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico w"><i class="bi bi-kanban-fill"></i></div>
            <div>
                <div class="sc-val" data-count="5">0</div>
                <div class="sc-lbl">Project Terkait</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>aktif</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari judul catatan..." />
        </div>
        <select class="nsel" style="min-width:140px">
            <option value="">Semua Kategori</option>
            <option>Personal</option>
            <option>Project</option>
            <option>Meeting</option>
            <option>Technical</option>
            <option>Task</option>
            <option>Penting</option>
        </select>
        <select class="nsel" style="min-width:160px">
            <option value="">Semua Project</option>
            <option>PPID Kota Pekalongan</option>
            <option>Sistem Absensi</option>
            <option>E-Commerce Mobile</option>
            <option>Manajemen Aset</option>
        </select>
        <select class="nsel" style="min-width:128px">
            <option value="">Semua Prioritas</option>
            <option>Tinggi</option>
            <option>Sedang</option>
            <option>Rendah</option>
        </select>
        <div class="tbar-right">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th style="width:42px;text-align:center">#</th>
                        <th style="min-width:280px">JUDUL CATATAN</th>
                        <th>KATEGORI</th>
                        <th>PROJECT</th>
                        <th>PRIORITAS</th>
                        <th>DIBUAT OLEH</th>
                        <th>TGL DIBUAT</th>
                        <th>DIPERBARUI</th>
                        <th style="text-align:center;width:100px">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-no">01</td>
                        <td>
                            <div class="td-title">
                                <div class="ct-ico project"><i class="bi bi-journal-bookmark-fill"></i></div>
                                <div>
                                    <div class="ct-nm">Sprint 4 Planning &amp; Task Breakdown</div>
                                    <div class="ct-preview">Rencana sprint 4 meliputi finalisasi modul permohonan
                                        informasi, pengujian API integrasi email...</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="kat-badge kb-project"><i class="bi bi-kanban"></i>Project</span></td>
                        <td><span class="proj-chip"><i class="bi bi-kanban" style="font-size:10px;opacity:.6"></i>PPID
                                Kota Pekalongan</span></td>
                        <td><span class="prio prio-h"><span class="prio-dot"></span>Tinggi</span></td>
                        <td>
                            <div class="td-usr">
                                <div class="uav" style="background:linear-gradient(135deg,#0072c6,#00c8ff)">AW
                                </div>Andi Wijaya
                            </div>
                        </td>
                        <td class="td-dt">01 Mar 2025</td>
                        <td class="td-dt">2 jam lalu</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-v" title="Lihat"
                                    data-title="Sprint 4 Planning &amp; Task Breakdown" data-kat="Project"
                                    data-proj="PPID Kota Pekalongan" data-prio="Tinggi" data-by="Andi Wijaya"
                                    data-tgl="01 Mar 2025"
                                    data-content="<h3>Sprint 4 Planning</h3><p>Rencana sprint 4: finalisasi modul permohonan informasi dan pengujian integrasi e-mail pemerintah.</p><ul><li>Finalisasi UI modul permohonan</li><li>Testing API notifikasi</li><li>Bug fixing sprint 3</li><li>Review kode backend</li></ul><p><strong>Target:</strong> 15 Maret 2025</p>"
                                    data-bs-toggle="modal" data-bs-target="#viewModal"><i
                                        class="bi bi-eye"></i></button>
                                <button class="ibtn ib-e" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                                <button class="ibtn ib-x" title="Hapus"
                                    data-nm="Sprint 4 Planning &amp; Task Breakdown" data-bs-toggle="modal"
                                    data-bs-target="#delModal"><i class="bi bi-trash3"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-no">02</td>
                        <td>
                            <div class="td-title">
                                <div class="ct-ico meeting"><i class="bi bi-people-fill"></i></div>
                                <div>
                                    <div class="ct-nm">Notulen Rapat Koordinasi Tim</div>
                                    <div class="ct-preview">Hasil rapat 28 Feb: pembagian tugas sprint 4, target
                                        deadline modul autentikasi, update desain...</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="kat-badge kb-meeting"><i class="bi bi-people"></i>Meeting</span></td>
                        <td><span class="proj-chip"><i class="bi bi-kanban"
                                    style="font-size:10px;opacity:.6"></i>PPID Kota Pekalongan</span></td>
                        <td><span class="prio prio-m"><span class="prio-dot"></span>Sedang</span></td>
                        <td>
                            <div class="td-usr">
                                <div class="uav" style="background:linear-gradient(135deg,#1e3a5f,#3d6080)">BS
                                </div>Budi Santoso
                            </div>
                        </td>
                        <td class="td-dt">28 Feb 2025</td>
                        <td class="td-dt">1 hari lalu</td>
                        <td>
                            <div class="act-row">
                                <button class="ibtn ib-v" title="Lihat" data-title="Notulen Rapat Koordinasi Tim"
                                    data-kat="Meeting" data-proj="PPID Kota Pekalongan" data-prio="Sedang"
                                    data-by="Budi Santoso" data-tgl="28 Feb 2025"
                                    data-content="<h3>Notulen Rapat &mdash; 28 Februari 2025</h3><p>Hadir: Budi Santoso, Andi Wijaya, Siti Rahayu, Deni Kurnia, Rina Marlina</p><p><strong>Agenda:</strong></p><ul><li>Update progress sprint 3</li><li>Review wireframe dashboard v2</li><li>Pembagian task sprint 4</li></ul><p><strong>Kesimpulan:</strong> Sprint 4 dimulai 1 Maret. Target modul autentikasi selesai 8 Maret 2025.</p>"
                                    data-bs-toggle="modal" data-bs-target="#viewModal"><i
                                        class="bi bi-eye"></i></button>
                                <button class="ibtn ib-e" title="Edit" data-bs-toggle="modal"
                                    data-bs-target="#editModal"><i class="bi bi-pencil"></i></button>
                                <button class="ibtn ib-x" title="Hapus" data-nm="Notulen Rapat Koordinasi Tim"
                                    data-bs-toggle="modal" data-bs-target="#delModal"><i
                                        class="bi bi-trash3"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>2</b> dari <b>32</b> catatan</div>
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
        <!-- MODAL: TAMBAH -->
        <div class="modal fade m-dark m-cyan" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-journal-plus"></i> Tambah Catatan Baru</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Judul Catatan<span class="req">*</span></label>
                                <input type="text" class="fmi" placeholder="Masukkan judul catatan..." />
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="fm-lbl">Kategori<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option value="">-- Pilih --</option>
                                    <option>Personal</option>
                                    <option>Project</option>
                                    <option>Meeting</option>
                                    <option>Technical</option>
                                    <option>Task</option>
                                    <option>Penting</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="fm-lbl">Prioritas<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option value="">-- Pilih --</option>
                                    <option>Tinggi</option>
                                    <option>Sedang</option>
                                    <option>Rendah</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Project Terkait</label>
                                <select class="fmsel">
                                    <option value="">-- Tidak ada --</option>
                                    <option>PPID Kota Pekalongan</option>
                                    <option>Sistem Absensi</option>
                                    <option>E-Commerce Mobile</option>
                                    <option>Manajemen Aset</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Dibuat Oleh<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option value="">-- Pilih --</option>
                                    <option>Budi Santoso (Admin)</option>
                                    <option>Andi Wijaya (Developer)</option>
                                    <option>Siti Rahayu (Designer)</option>
                                    <option>Deni Kurnia (Backend Dev)</option>
                                    <option>Rina Marlina (Manager)</option>
                                </select>
                            </div>
                        </div>
                        <label class="fm-lbl">Isi Catatan<span class="req">*</span></label>
                        <div class="tiny-wrap">
                            <textarea id="tinyAdd"></textarea>
                        </div>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                        <button class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Catatan</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT -->
        <div class="modal fade m-dark m-cyan" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-pencil-square"></i> Edit Catatan</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Judul Catatan<span class="req">*</span></label>
                                <input type="text" class="fmi" placeholder="Masukkan judul catatan..." />
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="fm-lbl">Kategori<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option value="">-- Pilih --</option>
                                    <option>Personal</option>
                                    <option>Project</option>
                                    <option>Meeting</option>
                                    <option>Technical</option>
                                    <option>Task</option>
                                    <option>Penting</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="fm-lbl">Prioritas<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option value="">-- Pilih --</option>
                                    <option>Tinggi</option>
                                    <option>Sedang</option>
                                    <option>Rendah</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Project Terkait</label>
                                <select class="fmsel">
                                    <option value="">-- Tidak ada --</option>
                                    <option>PPID Kota Pekalongan</option>
                                    <option>Sistem Absensi</option>
                                    <option>E-Commerce Mobile</option>
                                    <option>Manajemen Aset</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="fm-lbl">Dibuat Oleh<span class="req">*</span></label>
                                <select class="fmsel">
                                    <option>Budi Santoso (Admin)</option>
                                    <option>Andi Wijaya (Developer)</option>
                                    <option>Siti Rahayu (Designer)</option>
                                    <option>Deni Kurnia (Backend Dev)</option>
                                    <option>Rina Marlina (Manager)</option>
                                </select>
                            </div>
                        </div>
                        <label class="fm-lbl">Isi Catatan<span class="req">*</span></label>
                        <div class="tiny-wrap">
                            <textarea id="tinyEdit"></textarea>
                        </div>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                        <button class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Perubahan</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: LIHAT -->
        <div class="modal fade m-dark m-cyan" id="viewModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title" id="viewTitle"><i class="bi bi-journal-text"></i> Detail Catatan</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd" style="padding:0">
                        <div style="padding:18px 20px 14px">
                            <div id="viewBody" class="view-content"></div>
                        </div>
                        <div class="view-meta" id="viewMeta"></div>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Tutup</button>
                        <button class="btn-msave"
                            onclick="bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();setTimeout(function(){new bootstrap.Modal(document.getElementById('editModal')).show();},200)"><span><i
                                    class="bi bi-pencil-fill"></i> Edit</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: HAPUS -->
        <div class="modal fade m-dark m-red" id="delModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-trash3-fill"></i> Hapus Catatan</h5>
                        <button class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <div class="warn-box">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <p>Anda akan menghapus catatan <strong id="delNm">ini</strong>. Tindakan ini tidak dapat
                                dibatalkan.</p>
                        </div>
                        <p style="font-size:12px;color:var(--muted);font-family:var(--mono)"><i
                                class="bi bi-info-circle"></i>&nbsp;Catatan dihapus permanen dari sistem.</p>
                    </div>
                    <div class="m-ft">
                        <button class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
                        <button class="btn-mdel"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
                    </div>
                    <div class="modal-drain">
                        <div class="drain-fill" id="drainDel"></div>
                    </div>
                </div>
            </div>
        </div>
    @endpush
</x-master-layout>
