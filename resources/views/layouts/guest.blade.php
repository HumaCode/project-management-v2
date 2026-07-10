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
    <meta name="recaptcha-site-key" content="{{ config('services.recaptcha.site_key') }}">

    <title>{{ $cms_settings['app_name'] ?? config('app.name', 'PMS') }}</title>

    <!-- Favicon -->
    @if(!empty($cms_settings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ $cms_settings['app_favicon'] }}" />
    @else
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/sca.css') }}">



    @stack('auth-css')
</head>

<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="page-wrapper">
        <div class="container-fluid px-0" style="max-width:1080px; width:100%">
            {{ $slot }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        // Setup global untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/auth/js/global-script.js') }}"></script>

    <script src="{{ asset('assets/auth/backend/js/sca.js') }}"></script>

    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif

    @stack('auth-js')

    @if (session('sca_error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Pastikan library SCA sudah dimuat sebelum memanggil ini
                if (typeof SCA !== 'undefined' && typeof SCA.toast === 'function') {
                    SCA.toast({
                        type: 'danger', // atau 'error' tergantung CSS SCA kamu
                        title: 'Akses Ditolak!',
                        message: '{{ session('sca_error') }}',
                        position: 'top-right'
                    });
                } else {
                    // Fallback jika SCA belum ter-load (untuk jaga-jaga)
                    alert('Akses Ditolak!\n' + '{{ session('sca_error') }}');
                }
            });
        </script>
    @endif
</body>

</html>
