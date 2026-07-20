<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $cms_settings['app_name'] ?? config('app.name', 'PMS') }}</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="{{ $cms_settings['app_description'] ?? 'Platform manajemen proyek terpadu' }}" />
    <meta name="author" content="PMS Team" />
    <meta name="robots" content="index, follow" />
    
    <!-- Favicon -->
    @if(!empty($cms_settings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ $cms_settings['app_favicon'] }}" />
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    @endif

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $cms_settings['app_name'] ?? 'PMS' }}" />
    <meta property="og:description" content="{{ $cms_settings['app_description'] ?? 'Platform manajemen proyek terpadu' }}" />
    <meta property="og:image" content="{{ $cms_settings['app_logo'] ?? asset('logo.png') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/global.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/sca.css') }}">

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/js/app.js'])
    @endif

    <style>
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 0px !important;
            margin-left: 15px !important;
        }
    </style>
    @stack('css')
</head>

<body>

    <canvas id="bgc"></canvas>

    <!-- SIDEBAR — sibling of .layout, NOT inside it -->
    @include('layouts.partials.sidebar')

    <!-- OVERLAY — sibling of sidebar & .layout, toggled via display:none/block -->
    <div class="sb-overlay" id="sbOverlay"></div>

    <!-- MAIN LAYOUT -->
    <div class="layout">
        <div class="main-wrap" id="mainWrap">

            <!-- Topbar -->
            @include('layouts.partials.topbar')



            <!-- Page Content -->
            <main class="page-body" id="">
                {{ $slot }}
            </main>

            {{-- footer --}}
            @include('layouts.partials.footer')

        </div><!-- /main-wrap -->
    </div><!-- /layout -->

    <!-- FAB -->
    <button class="fab" id="fab" onclick="scrollToTop()" aria-label="Kembali ke atas">
        <div class="fab-p1"></div>
        <div class="fab-p2"></div>
        <div class="fab-ring"></div>
        <div class="fab-orbit"></div>
        <div class="fab-inner"><i class="bi bi-chevron-up"></i></div>
        <div class="fab-tip">Kembali ke atas</div>
    </button>

    <!-- MODAL CONTAINER — must be at body root to avoid z-index trapping -->
    <div id="modal"></div>

    <!-- LOGOUT MODAL -->
    @include('layouts.partials.modal-logout')

    @stack('modals')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('assets/auth/backend/js/global-js.js') }}"></script>
    <script src="{{ asset('assets/auth/backend/js/main.js') }}"></script>
    <script src="{{ asset('assets/auth/backend/js/sca.js') }}"></script>

    @stack('js')
</body>

</html>
