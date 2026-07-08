<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <script>
    (function() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
  </script>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>{{ config('app.name', 'PMS') }} — Layanan Permohonan Aplikasi</title>
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  
  <!-- Custom Style -->
  <link rel="stylesheet" href="{{ asset('assets/auth/css/welcome.css') }}?v={{ time() }}"/>
</head>
<body>

<!-- Custom cursor -->
<div class="cursor-dot" id="cd"></div>
<div class="cursor-ring" id="cr"></div>

<!-- Background -->
<canvas id="bgCanvas"></canvas>
<div class="hex-grid"></div>
<div class="scan-line"></div>

<!-- NAVBAR -->
<nav id="navbar">
  <div class="nav-inner" style="justify-content: space-between;">
    <a class="nav-logo" href="{{ url('/') }}">
      <div class="logo-mark"><i class="bi bi-diagram-3-fill"></i></div>
      <span class="logo-txt">PMS</span>
    </a>
    
    @auth
      <button class="nav-cta" onclick="window.location.href='{{ route('dashboard') }}'">
        <span><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</span>
      </button>
    @else
      <div class="nav-auth-group">
        <a href="{{ route('login') }}" class="nav-login-link">Masuk</a>
        @if (Route::has('register'))
          <button class="nav-cta" onclick="window.location.href='{{ route('register') }}'">
            <span>Daftar</span>
          </button>
        @endif
      </div>
    @endauth
  </div>
</nav>

<!-- HERO -->
<section id="beranda" style="min-height: calc(100vh - 68px);">
  <div class="hero-glow hg1"></div>
  <div class="hero-glow hg2"></div>
  <div class="hero-glow hg3"></div>
  <div class="container-xl">
    <div class="row align-items-center g-5">
      <div class="col-12 col-xl-6">
        <div class="hero-badge"><span class="hbdot"></span> Layanan Permohonan Aplikasi</div>
        <h1 class="hero-h1">
          Buat Permohonan<br>
          Pembuatan Aplikasi<br>
          <span class="hl">Di Sini.</span>
        </h1>
        <p class="hero-desc">Sampaikan kebutuhan sistem atau aplikasi Anda secara mudah, pantau proses review secara transparan, dan kolaborasi dengan tim pengembang kami langsung dalam satu platform.</p>
        <div class="hero-btns" style="margin-top: 32px;">
          @auth
            <a href="{{ route('project-request.create') }}" class="btn-interactive-glow">
              <span class="btn-content">
                <i class="bi bi-patch-plus-fill"></i>
                <span>Buat Permohonan Sekarang</span>
              </span>
              <span class="glow-effect"></span>
            </a>
          @else
            <a href="{{ route('project-request.create') }}" class="btn-interactive-glow">
              <span class="btn-content">
                <i class="bi bi-patch-plus-fill"></i>
                <span>Buat Permohonan Sekarang</span>
              </span>
              <span class="glow-effect"></span>
            </a>
          @endauth
        </div>
        <div class="hero-stats">
          <div class="hstat"><div class="hstat-v" data-count="{{ $totalCompletedProjects }}">0</div><div class="hstat-l">Proyek Selesai</div></div>
          <div class="hstat"><div class="hstat-v" data-count="{{ $totalUsers }}">0</div><div class="hstat-l">User Aktif</div></div>
          <div class="hstat"><div class="hstat-v" data-count="99">0</div><div class="hstat-l">% Kepuasan</div></div>
          <div class="hstat"><div class="hstat-v" data-count="{{ $totalDocuments }}">0</div><div class="hstat-l">Dokumen Proyek</div></div>
        </div>
      </div>
      <div class="col-xl-6 d-none d-xl-block">
        <div class="hero-right">
          <!-- Orbit rings -->
          <div class="hero-orbit" style="inset:-60px;animation-duration:18s"></div>
          <div class="hero-orbit" style="inset:-110px;animation-duration:28s;animation-direction:reverse"></div>
          <!-- Floating cards -->
          <div class="floatcard fc1">
            <div class="fc-ico">🚀</div>
            <div class="fc-v" style="color:var(--cyan)">+24%</div>
            <div class="fc-l">Produktivitas</div>
          </div>
          <div class="floatcard fc2">
            <div class="fc-ico">✅</div>
            <div class="fc-v" style="color:var(--green)">128</div>
            <div class="fc-l">Task selesai hari ini</div>
          </div>
          <!-- Main mockup card -->
          <div class="mockup">
            <div class="mkbar">
              <span class="mkdot d-r"></span><span class="mkdot d-y"></span><span class="mkdot d-g"></span>
              <span class="mktitle">pms-dashboard.app</span>
            </div>
            <!-- Progress rows -->
            <div class="mkrow"><div class="mkav" style="background:linear-gradient(135deg,#0072c6,#00d4ff)"></div><div class="mkb bc" style="width:75%"></div><span style="font-family:var(--mono);font-size:10px;color:var(--cyan);white-space:nowrap">75%</span></div>
            <div class="mkrow"><div class="mkav" style="background:linear-gradient(135deg,#92400e,#f59e0b)"></div><div class="mkb ba" style="width:54%"></div><span style="font-family:var(--mono);font-size:10px;color:var(--amber);white-space:nowrap">54%</span></div>
            <div class="mkrow"><div class="mkav" style="background:linear-gradient(135deg,#064e3b,#00e5a0)"></div><div class="mkb bgr" style="width:88%"></div><span style="font-family:var(--mono);font-size:10px;color:var(--green);white-space:nowrap">88%</span></div>
            <div class="mkrow"><div class="mkav" style="background:linear-gradient(135deg,#5b21b6,#a78bfa)"></div><div class="mkb" style="background:linear-gradient(90deg,#5b21b6,#a78bfa);width:42%;animation:bl 3s 1.2s ease-in-out infinite"></div><span style="font-family:var(--mono);font-size:10px;color:#a78bfa;white-space:nowrap">42%</span></div>
            <!-- Mini stats -->
            <div class="mkstats">
              <div class="mks"><div class="mks-v">{{ $totalProjects }}</div><div class="mks-l">Proyek</div></div>
              <div class="mks"><div class="mks-v" style="color:var(--amber)">{{ $totalTeams }}</div><div class="mks-l">Tim</div></div>
              <div class="mks"><div class="mks-v" style="color:var(--green)">98%</div><div class="mks-l">On-time</div></div>
            </div>
            <!-- Chart bars -->
            <div class="mkchart" id="mkChart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer id="footer" style="padding: 30px 0 0;">
  <div class="ft-bg-glow"></div>
  <div class="container-xl">
    <div class="ft-bottom" style="border-top: none; padding: 18px 0; justify-content: space-between;">
      <div class="ft-copy">© {{ date('Y') }} <span>PMS</span> · Dibuat dengan ♥ di Indonesia</div>
      <div class="ft-online"><div class="ft-sdot"></div> Semua sistem operasional</div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/auth/js/welcome.js') }}?v={{ time() }}"></script>
</body>
</html>
