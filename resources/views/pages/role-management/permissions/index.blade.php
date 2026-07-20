<x-master-layout>

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/role-management.css') }}">
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        @php
            $currentUser = auth()->user();
            $isSuperAdmin = $currentUser && ($currentUser->hasRole('dev') || $currentUser->hasRole('super admin'));
        @endphp

        <script>
            // Konfigurasi Global (Dinamis dari PHP)
            window.dataTableId = @json($dataTableId);
            window.urlData = @json($dataUrl);
            window.urlEdit = @json($editUrl);
            window.urlShow = @json($showUrl);
            window.urlDestroy = @json($destroyUrl);
            window.canRead = @json($isSuperAdmin || ($currentUser && $currentUser->can('read ' . $permissionAkses)));
            window.canUpdate = @json($isSuperAdmin || ($currentUser && $currentUser->can('update ' . $permissionAkses)));
            window.canDelete = @json($isSuperAdmin || ($currentUser && $currentUser->can('delete ' . $permissionAkses)));
        </script>

        <script src="{{ asset('assets/auth/backend/js/custom-table.js') }}"></script>
        <script src="{{ asset('assets/auth/backend/js/permission.js') }}"></script>
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
                <a href="dashboard.html"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                <span class="sep"><i class="bi bi-chevron-right"></i></span>
                <span class="here">{{ $title }}</span>
            </div>
        </div>
    </div>



    <!-- Filter Toolbar -->
    @include('pages.role-management.permissions.partials.filter-view')

    <!-- Table Card -->
    <div class="tbl-card" data-aos="fade-up" data-aos-delay="80">
        <div class="table-responsive">
            <table class="dtbl">
                <thead class="text-center">
                    <tr>
                        <th style="text-align:center;width:42px">#</th>
                        <th style="min-width:180px">NAMA</th>
                        <th style="min-width:80px">GUARD</th>
                        <th style="min-width:110px">DIBUAT</th>
                        <th style="min-width:110px">DIPERBARUI</th>
                        <th style="text-align:center;width:110px">AKSI</th>
                    </tr>
                </thead>
                <tbody id="dataBody">

                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="tbl-foot">
            <div class="tbl-info">Menampilkan <b>0</b> dari <b>0</b> data</div>
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

</x-master-layout>
