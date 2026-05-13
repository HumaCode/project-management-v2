<x-master-layout>
    @section('title', $title)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/user.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/catatan.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            window.catatanUrl = "{{ $dataUrl }}";
            window.storeUrl = "{{ $storeUrl }}";
            window.tableId = "{{ $dataTableId }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/catatan.js') }}?v={{ time() }}"></script>
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
                <div class="sc-val" data-count="{{ $total_catatan }}">0</div>
                <div class="sc-lbl">Total Catatan</div>
                <div class="sc-tr up"><i class="bi bi-arrow-up-short"></i>+0 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico r"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div>
                <div class="sc-val" data-count="{{ $total_high_priority }}">0</div>
                <div class="sc-lbl">Prioritas Tinggi</div>
                <div class="sc-tr dn"><i class="bi bi-arrow-up-short"></i>+0 minggu ini</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico g"><i class="bi bi-tags-fill"></i></div>
            <div>
                <div class="sc-val" data-count="{{ $total_categories }}">0</div>
                <div class="sc-lbl">Kategori</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>semua aktif</div>
            </div>
        </div>
        <div class="sc">
            <div class="sc-ico w"><i class="bi bi-kanban-fill"></i></div>
            <div>
                <div class="sc-val" data-count="{{ $total_projects_related }}">0</div>
                <div class="sc-lbl">Project Terkait</div>
                <div class="sc-tr neu"><i class="bi bi-dash"></i>aktif</div>
            </div>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari judul catatan..." />
        </div>
        <select class="nsel" id="filterCategory" style="min-width:140px">
            <option value="">Semua Kategori</option>
            <option value="Personal">Personal</option>
            <option value="Project">Project</option>
            <option value="Meeting">Meeting</option>
            <option value="Technical">Technical</option>
            <option value="Task">Task</option>
            <option value="Penting">Penting</option>
        </select>
        <select class="nsel select2" id="filterProject">
            <option value="">Semua Project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
        <select class="nsel" id="filterPriority" style="min-width:128px">
            <option value="">Semua Prioritas</option>
            <option value="tinggi">Tinggi</option>
            <option value="sedang">Sedang</option>
            <option value="rendah">Rendah</option>
        </select>
        <button class="btn-refresh" id="btnResetFilter" title="Reset Filter">
            <i class="bi bi-arrow-counterclockwise"></i>
        </button>
        <div class="tbar-right">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
                <span><i class="bi bi-plus-lg"></i><span class="d-none d-sm-inline"> Tambah</span></span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl" id="{{ $dataTableId }}">
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
                <tbody id="dataBody">
                    <!-- Data loaded via AJAX -->
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="spinner-border text-cyan" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="mt-2" style="font-family:var(--mono);font-size:12px;color:var(--muted)">Memuat data...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info"></div>
            <div class="pag"></div>
        </div>
    </div>

    @push('modals')
        <!-- MODAL: TAMBAH -->
        <div class="modal fade m-dark m-cyan" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="form_add" action="{{ $storeUrl }}" method="POST">
                        <div class="m-hd">
                            <h5 class="m-hd-title"><i class="bi bi-journal-plus"></i> Tambah Catatan Baru</h5>
                            <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="m-bd">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="fm-lbl">Judul Catatan<span class="req">*</span></label>
                                    <input type="text" name="title" class="fmi" placeholder="Masukkan judul catatan..." required />
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="fm-lbl">Kategori<span class="req">*</span></label>
                                    <select name="category" class="fmsel" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Project">Project</option>
                                        <option value="Meeting">Meeting</option>
                                        <option value="Technical">Technical</option>
                                        <option value="Task">Task</option>
                                        <option value="Penting">Penting</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="fm-lbl">Prioritas<span class="req">*</span></label>
                                    <select name="priority" class="fmsel" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="tinggi">Tinggi</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="rendah">Rendah</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="fm-lbl">Project Terkait</label>
                                    <select name="project_id" class="fmsel select2">
                                        <option value="">-- Tidak ada --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="fm-lbl">Isi Catatan<span class="req">*</span></label>
                            <div class="ck-wrap">
                                <textarea name="content" id="ckAdd"></textarea>
                            </div>
                        </div>
                        <div class="m-ft">
                            <button type="button" class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                            <button type="submit" class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Catatan</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL: EDIT -->
        <div class="modal fade m-dark m-cyan" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <form id="form_edit" action="" method="POST">
                        @method('PUT')
                        <div class="m-hd">
                            <h5 class="m-hd-title"><i class="bi bi-pencil-square"></i> Edit Catatan</h5>
                            <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="m-bd">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="fm-lbl">Judul Catatan<span class="req">*</span></label>
                                    <input type="text" name="title" id="edit_title" class="fmi" placeholder="Masukkan judul catatan..." required />
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="fm-lbl">Kategori<span class="req">*</span></label>
                                    <select name="category" id="edit_category" class="fmsel" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Project">Project</option>
                                        <option value="Meeting">Meeting</option>
                                        <option value="Technical">Technical</option>
                                        <option value="Task">Task</option>
                                        <option value="Penting">Penting</option>
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="fm-lbl">Prioritas<span class="req">*</span></label>
                                    <select name="priority" id="edit_priority" class="fmsel" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="tinggi">Tinggi</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="rendah">Rendah</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="fm-lbl">Project Terkait</label>
                                    <select name="project_id" id="edit_project_id" class="fmsel select2">
                                        <option value="">-- Tidak ada --</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="fm-lbl">Isi Catatan<span class="req">*</span></label>
                            <div class="ck-wrap">
                                <textarea name="content" id="ckEdit"></textarea>
                            </div>
                        </div>
                        <div class="m-ft">
                            <button type="button" class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                            <button type="submit" class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Perubahan</span></button>
                        </div>
                    </form>
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
