<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
        <style>
            .av-stack { display: flex; align-items: center; }
            .av-item {
                width: 32px; height: 32px; border-radius: 50%;
                border: 2px solid #050e1d; background: var(--cyan);
                display: flex; align-items: center; justify-content: center;
                font-size: 11px; font-weight: bold; color: #fff;
                overflow: hidden; margin-left: -10px;
                transition: transform 0.2s;
                position: relative;
            }
            .av-item:first-child { margin-left: 0; }
            .av-item:hover { transform: translateY(-3px); z-index: 10; }
            .av-item img { width: 100%; height: 100%; object-fit: cover; }
            .bg-cyan-soft { background: rgba(0, 200, 255, 0.1) !important; }
            .text-dim { color: var(--dim) !important; }

            /* Specific for Team Module if needed */
            .item-sel.active {
                border-color: var(--cyan) !important;
                background: rgba(0, 200, 255, 0.08) !important;
            }
            .item-sel.active .check-ico { display: block !important; }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
        <script>
            window.urlBase = "{{ url('teams') }}";
            window.urlData = "{{ route('teams.getData') }}";
            window.urlUsers = "{{ route('teams.getUsers') }}";
            window.urlStore = "{{ route('teams.store') }}";
        </script>
        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/team.js') }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="pg-hd" data-aos="fade-down" data-aos-duration="500">
        <div class="pg-hd-left">
            <div class="pg-ico"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="pg-title">{{ $title }}</div>
                <div class="pg-sub">{{ $subtitle }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <div class="bc d-none d-md-flex">
                <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="50">
        <div class="col-md-6">
            <div class="sc c w-100 m-0">
                <div class="sc-ico c"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="sc-val">{{ $total_teams }}</div>
                    <div class="sc-lbl">Total Tim</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="sc g w-100 m-0">
                <div class="sc-ico g"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="sc-val">{{ $total_members }}</div>
                    <div class="sc-lbl">Anggota Terdaftar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="tbar" data-aos="fade-up" data-aos-delay="60">
        <div class="tbar-search">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Cari nama tim atau deskripsi..." />
        </div>

        <div class="filter-wrap" style="display: flex; gap: 8px; align-items: center;">
            <button class="btn-reset" id="btnReset" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </div>

        <div class="tbar-right">
            <button class="btn-add" id="btnAdd">
                <span><i class="bi bi-plus-lg"></i> Tambah Tim</span>
            </button>
        </div>
    </div>

    <!-- Table card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl" id="tblTeam">
                <thead>
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:200px">Nama Tim</th>
                        <th style="min-width:250px">Deskripsi</th>
                        <th style="min-width:150px">Anggota</th>
                        <th style="min-width:150px">Dibuat Oleh</th>
                        <th style="min-width:120px">Tanggal</th>
                        <th style="text-align:center;width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataBody">
                    <!-- Data loaded via AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="tbl-empty d-none">
            <i class="bi bi-folder-x"></i>
            <div class="et">Belum Ada Tim</div>
            <div class="es">Buat tim baru untuk mengelola anggota proyek Anda.</div>
        </div>

        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info" id="pagiInfo">
                Menampilkan <span>0</span> – <span>0</span> dari <span>0</span> tim
            </div>
            <div class="pag" id="pagiBtns">
                <!-- Buttons generated by JS -->
            </div>
        </div>
    </div>

    @push('modals')
    <!-- Modal Detail -->
    <div class="modal fade m-dark m-cyan" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="m-hd">
                    <div class="m-hd-title">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>Detail Tim</span>
                    </div>
                    <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="m-bd">
                    <div class="text-center mb-4">
                        <div class="mb-2" style="display:inline-flex; align-items:center; justify-content:center; width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg, var(--blue), var(--cyan)); color:#fff; font-size:28px; box-shadow:0 8px 24px rgba(0, 200, 255, 0.3)">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4 id="detName" class="mb-1" style="font-weight:800; color:var(--txt)">-</h4>
                        <p id="detCreator" class="text-muted small mb-0" style="font-family:var(--mono)">Dibuat oleh: <span>-</span></p>
                    </div>

                    <div class="m-section">Deskripsi Tim</div>
                    <p id="detDesc" class="text-dim mb-4" style="line-height:1.6; font-size:13.5px">-</p>

                    <div class="m-section d-flex justify-content-between align-items:center">
                        <span>Daftar Anggota</span>
                        <span id="detCount" class="badge bg-cyan-soft text-cyan" style="font-size:10px; padding:4px 8px">0 Orang</span>
                    </div>
                    <div id="detMemberList" class="row g-2 mt-2">
                        <!-- Member list loaded via JS -->
                    </div>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Form -->
    <div class="modal fade m-dark m-cyan" id="modalForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="m-hd">
                    <div class="m-hd-title">
                        <i class="bi bi-people-fill"></i>
                        <span id="modalTitle">Tambah Tim Baru</span>
                    </div>
                    <button type="button" class="m-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form id="teamForm">
                    <div class="m-bd">
                        <div class="m-section" style="border-top:none;padding-top:0;margin-top:0">Informasi Tim</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="fm-lbl">Nama Tim <span class="req">*</span></label>
                                <input type="text" name="name" class="fmi" placeholder="Contoh: Tim Pengembang Core" required>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Deskripsi</label>
                                <textarea name="description" class="fmta" placeholder="Jelaskan tujuan atau tugas tim ini..."></textarea>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="m-section">Pilih Anggota Tim</div>
                                <div class="row g-2" id="userSelectionWrap" style="max-height: 250px; overflow-y: auto; padding: 5px; margin-top: 5px;">
                                    <!-- User list loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-ft">
                        <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Batal
                        </button>
                        <button type="submit" class="btn-msave" id="btnSave">
                            <span><i class="bi bi-check-lg"></i> Simpan Tim</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

</x-master-layout>
