<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>{{ config('app.name', 'PMS') }} — Project Management System</title>
  
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

<!-- ══════════════════════════════════════════════════════
     NAVBAR
     ══════════════════════════════════════════════════════ -->
<nav id="navbar">
  <div class="nav-inner">
    <a class="nav-logo" href="#beranda" onclick="smoothTo('#beranda')">
      <div class="logo-mark"><i class="bi bi-diagram-3-fill"></i></div>
      <span class="logo-txt">PMS</span>
    </a>
    <ul class="nav-links" id="navLinks">
      <li><a href="#beranda" data-sec="beranda" class="active">Beranda</a></li>
      <li><a href="#fitur" data-sec="fitur">Fitur</a></li>
      <li><a href="#tentang" data-sec="tentang">Tentang</a></li>
      <li><a href="#cara-kerja" data-sec="cara-kerja">Cara Kerja</a></li>
      <li><a href="#statistik" data-sec="statistik">Statistik</a></li>
      <li><a href="#testimoni" data-sec="testimoni">Testimoni</a></li>
      <li><a href="#harga" data-sec="harga">Harga</a></li>
      <li><a href="#kontak" data-sec="kontak">Kontak</a></li>
    </ul>
    
    @auth
      <button class="nav-cta" onclick="window.location.href='{{ route('dashboard') }}'">
        <span><i class="bi bi-grid-3x3-gap-fill"></i> Dashboard</span>
      </button>
    @else
      <div style="display:flex; gap:10px; align-items:center;">
        <a href="{{ route('login') }}" class="nav-links" style="margin-left:0; color:var(--dim); font-weight:500; font-size:13.5px; padding: 7px 13px;" onmouseover="this.style.color='var(--txt)'" onmouseout="this.style.color='var(--dim)'">Masuk</a>
        @if (Route::has('register'))
          <button class="nav-cta" onclick="window.location.href='{{ route('register') }}'">
            <span>Daftar</span>
          </button>
        @endif
      </div>
    @endauth
    
    <button class="nav-ham" id="navHam" aria-label="Menu">
      <i class="bi bi-list" id="hamIco"></i>
    </button>
  </div>
</nav>

<!-- Mobile drawer -->
<div class="nav-drawer" id="navDrawer">
  <a href="#beranda" data-sec="beranda" class="active">Beranda</a>
  <a href="#fitur" data-sec="fitur">Fitur</a>
  <a href="#tentang" data-sec="tentang">Tentang</a>
  <a href="#cara-kerja" data-sec="cara-kerja">Cara Kerja</a>
  <a href="#statistik" data-sec="statistik">Statistik</a>
  <a href="#testimoni" data-sec="testimoni">Testimoni</a>
  <a href="#harga" data-sec="harga">Harga</a>
  <a href="#kontak" data-sec="kontak">Kontak</a>
  
  @auth
    <button class="nav-drawer-cta" onclick="window.location.href='{{ route('dashboard') }}'">
      <i class="bi bi-grid-3x3-gap-fill"></i> Masuk ke Dashboard
    </button>
  @else
    <button class="nav-drawer-cta" onclick="window.location.href='{{ route('login') }}'" style="margin-bottom: 8px;">
      Masuk
    </button>
    @if (Route::has('register'))
      <button class="nav-drawer-cta" onclick="window.location.href='{{ route('register') }}'">
        Daftar Gratis
      </button>
    @endif
  @endauth
</div>

<!-- ══════════════════════════════════════════════════════
     HERO
     ══════════════════════════════════════════════════════ -->
<section id="beranda">
  <div class="hero-glow hg1"></div>
  <div class="hero-glow hg2"></div>
  <div class="hero-glow hg3"></div>
  <div class="container-xl">
    <div class="row align-items-center g-5">
      <div class="col-12 col-xl-6">
        <div class="hero-badge"><span class="hbdot"></span> Platform Manajemen Proyek #1</div>
        <h1 class="hero-h1">
          Kelola Proyek<br>
          <span class="hl">Lebih Cerdas.</span><br>
          Lebih Cepat.
        </h1>
        <p class="hero-desc">Platform manajemen proyek all-in-one yang dirancang untuk tim modern. Pantau progres, kolaborasi real-time, dan capai target dengan lebih efisien.</p>
        <div class="hero-btns">
          <button class="btn-p" onclick="smoothTo('#kontak')"><span><i class="bi bi-rocket-takeoff-fill"></i> Mulai Gratis</span></button>
          <button class="btn-o" onclick="smoothTo('#cara-kerja')"><i class="bi bi-play-circle-fill"></i> Lihat Demo</button>
        </div>
        <div class="hero-stats">
          <div class="hstat"><div class="hstat-v" data-count="500">0</div><div class="hstat-l">Proyek Selesai</div></div>
          <div class="hstat"><div class="hstat-v" data-count="1200">0</div><div class="hstat-l">User Aktif</div></div>
          <div class="hstat"><div class="hstat-v" data-count="99">0</div><div class="hstat-l">% Kepuasan</div></div>
          <div class="hstat"><div class="hstat-v" data-count="48">0</div><div class="hstat-l">Integrasi</div></div>
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
              <div class="mks"><div class="mks-v">24</div><div class="mks-l">Proyek</div></div>
              <div class="mks"><div class="mks-v" style="color:var(--amber)">8</div><div class="mks-l">Tim</div></div>
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

<!-- ══════════════════════════════════════════════════════
     CLIENTS MARQUEE
     ══════════════════════════════════════════════════════ -->
<div id="klien">
  <div class="marquee-wrap">
    <div class="marquee-track" id="mt1">
      <span class="cl-item"><i class="bi bi-building"></i> PT Teknologi Maju</span>
      <span class="cl-item"><i class="bi bi-globe"></i> Startup Digital ID</span>
      <span class="cl-item"><i class="bi bi-bank"></i> BPD Jawa Tengah</span>
      <span class="cl-item"><i class="bi bi-building-fill"></i> Pemerintah Kota</span>
      <span class="cl-item"><i class="bi bi-laptop"></i> DevHouse Studio</span>
      <span class="cl-item"><i class="bi bi-cpu"></i> TechCorp Indonesia</span>
      <span class="cl-item"><i class="bi bi-graph-up"></i> FinTech Nusantara</span>
      <span class="cl-item"><i class="bi bi-cloud"></i> CloudSys Platform</span>
      <span class="cl-item"><i class="bi bi-building"></i> PT Teknologi Maju</span>
      <span class="cl-item"><i class="bi bi-globe"></i> Startup Digital ID</span>
      <span class="cl-item"><i class="bi bi-bank"></i> BPD Jawa Tengah</span>
      <span class="cl-item"><i class="bi bi-building-fill"></i> Pemerintah Kota</span>
      <span class="cl-item"><i class="bi bi-laptop"></i> DevHouse Studio</span>
      <span class="cl-item"><i class="bi bi-cpu"></i> TechCorp Indonesia</span>
      <span class="cl-item"><i class="bi bi-graph-up"></i> FinTech Nusantara</span>
      <span class="cl-item"><i class="bi bi-cloud"></i> CloudSys Platform</span>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════
     FEATURES
     ══════════════════════════════════════════════════════ -->
<section id="fitur" class="sp">
  <div class="container-xl">
    <div class="text-center mb-5 reveal">
      <div class="sec-tag c"><i class="bi bi-stars"></i> Fitur Unggulan</div>
      <h2 class="sec-title">Semua yang Anda Butuhkan<br>dalam <span class="hl">Satu Platform</span></h2>
      <div class="sec-line mx-auto"></div>
      <p class="sec-sub mx-auto text-center">Fitur lengkap dirancang khusus untuk meningkatkan produktivitas tim dan transparansi proyek Anda.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.05s">
        <div class="feat-card">
          <div class="feat-ico fi-c"><i class="bi bi-kanban-fill"></i></div>
          <div class="feat-title">Manajemen Proyek</div>
          <div class="feat-desc">Buat, kelola, dan pantau proyek dengan tampilan kanban, timeline, dan gantt chart interaktif secara real-time.</div>
          <span class="feat-chip">Kanban · Timeline</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.10s">
        <div class="feat-card">
          <div class="feat-ico fi-a"><i class="bi bi-people-fill"></i></div>
          <div class="feat-title">Manajemen Tim</div>
          <div class="feat-desc">Atur peran dan hak akses anggota tim dengan sistem RBAC yang fleksibel dan mudah dikonfigurasi.</div>
          <span class="feat-chip">RBAC · Role Management</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.15s">
        <div class="feat-card">
          <div class="feat-ico fi-g"><i class="bi bi-file-earmark-pdf-fill"></i></div>
          <div class="feat-title">Laporan PDF</div>
          <div class="feat-desc">Generate laporan profesional dalam format PDF dengan template yang dapat dikustomisasi sesuai kebutuhan.</div>
          <span class="feat-chip">PDF · Export</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.20s">
        <div class="feat-card">
          <div class="feat-ico fi-r"><i class="bi bi-journal-text"></i></div>
          <div class="feat-title">Catatan & Dokumen</div>
          <div class="feat-desc">Editor teks kaya fitur dengan versioning dokumen, attachment file, dan kolaborasi catatan tim secara bersamaan.</div>
          <span class="feat-chip">TinyMCE · Versioning</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.25s">
        <div class="feat-card">
          <div class="feat-ico fi-p"><i class="bi bi-graph-up-arrow"></i></div>
          <div class="feat-title">Analitik & Dashboard</div>
          <div class="feat-desc">Visualisasi data proyek secara mendetail dengan grafik interaktif, KPI tracker, dan laporan kemajuan otomatis.</div>
          <span class="feat-chip">Analytics · Charts</span>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 reveal" style="transition-delay:.30s">
        <div class="feat-card">
          <div class="feat-ico fi-o"><i class="bi bi-bell-fill"></i></div>
          <div class="feat-title">Notifikasi Real-time</div>
          <div class="feat-desc">Sistem notifikasi instan via email dan in-app untuk setiap perubahan, deadline, dan aktivitas penting tim Anda.</div>
          <span class="feat-chip">Email · Push Notif</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     ABOUT
     ══════════════════════════════════════════════════════ -->
<section id="tentang" class="sp">
  <div class="container-xl">
    <div class="row align-items-center g-5">
      <div class="col-12 col-lg-6 reveal-l">
        <div class="about-visual">
          <div class="ametric"><div class="am-ico am-a"><i class="bi bi-trophy-fill"></i></div><div><div class="am-v">500+</div><div class="am-l">Proyek diselesaikan</div></div></div>
          <div class="ametric"><div class="am-ico am-c"><i class="bi bi-people-fill"></i></div><div><div class="am-v">1,200+</div><div class="am-l">Pengguna aktif</div></div></div>
          <div class="ametric"><div class="am-ico am-g"><i class="bi bi-clock-fill"></i></div><div><div class="am-v">99.9%</div><div class="am-l">Uptime terjamin</div></div></div>
          <!-- Mini bar chart -->
          <div style="margin-top:16px;padding:14px;background:rgba(0,0,0,.2);border:1px solid var(--bd);border-radius:11px">
            <div style="font-family:var(--mono);font-size:10px;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:1px">Proyek selesai per bulan</div>
            <div style="display:flex;align-items:flex-end;gap:5px;height:50px">
              <div style="flex:1;background:linear-gradient(to top,var(--cyan2),var(--cyan));border-radius:3px 3px 0 0;height:60%;opacity:.8"></div>
              <div style="flex:1;background:linear-gradient(to top,var(--cyan2),var(--cyan));border-radius:3px 3px 0 0;height:75%"></div>
              <div style="flex:1;background:linear-gradient(to top,var(--cyan2),var(--cyan));border-radius:3px 3px 0 0;height:55%;opacity:.8"></div>
              <div style="flex:1;background:linear-gradient(to top,var(--cyan2),var(--cyan));border-radius:3px 3px 0 0;height:90%"></div>
              <div style="flex:1;background:linear-gradient(to top,var(--amber),#fbbf24);border-radius:3px 3px 0 0;height:100%"></div>
              <div style="flex:1;background:linear-gradient(to top,#064e3b,var(--green));border-radius:3px 3px 0 0;height:80%"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6 reveal-r">
        <div class="sec-tag a"><i class="bi bi-info-circle-fill"></i> Tentang Platform</div>
        <h2 class="sec-title">Dibangun untuk<br><span class="hl-a">Tim yang Ambisius</span></h2>
        <div class="sec-line a"></div>
        <p style="font-size:15px;color:var(--dim);line-height:1.75;margin-bottom:16px">PMS adalah platform manajemen proyek yang lahir dari kebutuhan nyata tim-tim profesional di Indonesia. Kami memahami tantangan koordinasi, deadline, dan transparansi dalam sebuah organisasi.</p>
        <ul class="about-list">
          <li><i class="bi bi-check-circle-fill al-ico"></i><span>Antarmuka intuitif yang bisa dipelajari dalam hitungan menit tanpa training panjang</span></li>
          <li><i class="bi bi-check-circle-fill al-ico"></i><span>Integrasi dengan tools yang sudah Anda gunakan: Slack, Google Drive, Trello, dan lebih banyak lagi</span></li>
          <li><i class="bi bi-check-circle-fill al-ico"></i><span>Data tersimpan aman dengan enkripsi SSL dan backup otomatis setiap 6 jam</span></li>
          <li><i class="bi bi-check-circle-fill al-ico"></i><span>Support tim berpengalaman siap membantu 24/7 melalui chat dan email</span></li>
        </ul>
        <div class="about-badge-row">
          <span class="abadge"><i class="bi bi-shield-fill-check"></i> SSL Secured</span>
          <span class="abadge"><i class="bi bi-cloud-fill"></i> Cloud Backup</span>
          <span class="abadge"><i class="bi bi-phone-fill"></i> Mobile Ready</span>
          <span class="abadge"><i class="bi bi-code-slash"></i> REST API</span>
          <span class="abadge"><i class="bi bi-lightning-fill"></i> Blazing Fast</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     HOW IT WORKS
     ══════════════════════════════════════════════════════ -->
<section id="cara-kerja" class="sp">
  <div class="container-xl">
    <div class="text-center mb-5 reveal">
      <div class="sec-tag a"><i class="bi bi-diagram-2-fill"></i> Cara Kerja</div>
      <h2 class="sec-title">Mulai dalam <span class="hl-a">4 Langkah</span> Mudah</h2>
      <div class="sec-line a mx-auto"></div>
      <p class="sec-sub mx-auto text-center">Dari registrasi hingga proyek berjalan, semua bisa dilakukan dalam waktu kurang dari 10 menit.</p>
    </div>
    <div class="row g-4 position-relative">
      <div class="col-12 col-sm-6 col-lg-3 reveal" style="transition-delay:.05s">
        <div class="step-card">
          <div class="step-num">01</div>
          <div class="step-title">Daftar Akun</div>
          <div class="step-desc">Buat akun gratis Anda hanya dalam 60 detik. Tidak perlu kartu kredit untuk memulai.</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3 reveal" style="transition-delay:.12s">
        <div class="step-card">
          <div class="step-num">02</div>
          <div class="step-title">Buat Workspace</div>
          <div class="step-desc">Setup workspace tim Anda, undang anggota, dan atur peran sesuai struktur organisasi.</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3 reveal" style="transition-delay:.20s">
        <div class="step-card">
          <div class="step-num">03</div>
          <div class="step-title">Buat Proyek</div>
          <div class="step-desc">Tambahkan proyek, bagi menjadi task-task kecil, assign ke anggota dan tentukan deadline.</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-3 reveal" style="transition-delay:.28s">
        <div class="step-card">
          <div class="step-num">04</div>
          <div class="step-title">Pantau & Analisis</div>
          <div class="step-desc">Lacak progres real-time, generate laporan, dan optimalkan produktivitas tim Anda setiap hari.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     STATS
     ══════════════════════════════════════════════════════ -->
<section id="statistik" class="sp">
  <div class="container-xl">
    <div class="row g-0">
      <div class="col-6 col-lg-3 reveal" style="transition-delay:.05s">
        <div class="stat-item">
          <div class="snum" data-count="500">0</div>
          <div class="slbl">Proyek Selesai</div>
        </div>
      </div>
      <div class="col-6 col-lg-3 reveal" style="transition-delay:.12s">
        <div class="stat-item">
          <div class="snum" data-count="1200">0</div>
          <div class="slbl">Pengguna Aktif</div>
        </div>
      </div>
      <div class="col-6 col-lg-3 reveal" style="transition-delay:.20s">
        <div class="stat-item">
          <div class="snum" data-count="99">0</div>
          <div class="slbl">% Kepuasan</div>
        </div>
      </div>
      <div class="col-6 col-lg-3 reveal" style="transition-delay:.28s">
        <div class="stat-item">
          <div class="snum" data-count="48">0</div>
          <div class="slbl">Integrasi Aktif</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     TESTIMONIALS
     ══════════════════════════════════════════════════════ -->
<section id="testimoni" class="sp">
  <div class="container-xl">
    <div class="text-center mb-5 reveal">
      <div class="sec-tag g"><i class="bi bi-chat-quote-fill"></i> Testimoni</div>
      <h2 class="sec-title">Dipercaya oleh <span class="hl">Tim Terbaik</span></h2>
      <div class="sec-line g mx-auto"></div>
      <p class="sec-sub mx-auto text-center">Bergabung bersama ratusan tim yang sudah merasakan manfaat nyata dari platform kami.</p>
    </div>
    <div class="row g-4">
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.05s">
        <div class="tcard">
          <div class="tquote">"</div>
          <p class="ttext">PMS mengubah cara kerja tim kami sepenuhnya. Sekarang semua progres proyek bisa dipantau secara real-time dan laporan bisa dibuat dalam hitungan detik.</p>
          <div class="tauthor">
            <div class="tav" style="background:linear-gradient(135deg,#0072c6,#00d4ff)">AR</div>
            <div>
              <div class="tnm">Andi Rahmat</div>
              <div class="trole">Project Manager · PT Digital Nusantara</div>
              <div class="tstars">★★★★★</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.12s">
        <div class="tcard">
          <div class="tquote">"</div>
          <p class="ttext">Fitur manajemen tim dengan RBAC-nya sangat membantu. Kami bisa mengatur akses setiap anggota dengan presisi tanpa ribet konfigurasi yang panjang.</p>
          <div class="tauthor">
            <div class="tav" style="background:linear-gradient(135deg,#064e3b,#00e5a0)">SW</div>
            <div>
              <div class="tnm">Sari Widyaningsih</div>
              <div class="trole">CTO · StartupIDBand</div>
              <div class="tstars">★★★★★</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.20s">
        <div class="tcard">
          <div class="tquote">"</div>
          <p class="ttext">Laporan PDF otomatis adalah fitur yang paling kami sukai. Presentasi ke klien jadi lebih profesional dan menghemat banyak waktu tim kami setiap minggu.</p>
          <div class="tauthor">
            <div class="tav" style="background:linear-gradient(135deg,#92400e,#f59e0b)">DK</div>
            <div>
              <div class="tnm">Deni Kurniawan</div>
              <div class="trole">Team Lead · GovTech Pekalongan</div>
              <div class="tstars">★★★★★</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     PRICING
     ══════════════════════════════════════════════════════ -->
<section id="harga" class="sp">
  <div class="container-xl">
    <div class="text-center mb-5 reveal">
      <div class="sec-tag g"><i class="bi bi-tag-fill"></i> Harga</div>
      <h2 class="sec-title">Pilih Paket yang <span class="hl">Sesuai Kebutuhan</span></h2>
      <div class="sec-line g mx-auto"></div>
      <p class="sec-sub mx-auto text-center">Mulai gratis, upgrade kapan saja. Tidak ada biaya tersembunyi, tidak ada kontrak jangka panjang.</p>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.05s">
        <div class="pcard">
          <div class="pname">Starter</div>
          <div class="pdesc">Cocok untuk tim kecil & personal project</div>
          <div class="pval"><span class="pcur">Rp</span><span class="pnum">0</span><span class="pper">/bln</span></div>
          <ul class="plist">
            <li><i class="bi bi-check-circle-fill pi-y"></i> 3 Proyek aktif</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> 5 Anggota tim</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> 1GB penyimpanan</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Dashboard dasar</li>
            <li><i class="bi bi-dash-circle-fill pi-n"></i> Laporan PDF</li>
            <li><i class="bi bi-dash-circle-fill pi-n"></i> API access</li>
            <li><i class="bi bi-dash-circle-fill pi-n"></i> Priority support</li>
          </ul>
          @auth
            <button class="pbtn out" onclick="window.location.href='{{ route('dashboard') }}'">Ke Dashboard</button>
          @else
            <button class="pbtn out" onclick="window.location.href='{{ route('register') }}'">Mulai Gratis</button>
          @endauth
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.12s">
        <div class="pcard pop">
          <div class="pop-tag">PALING POPULER</div>
          <div class="pname">Professional</div>
          <div class="pdesc">Untuk tim profesional yang berkembang pesat</div>
          <div class="pval"><span class="pcur">Rp</span><span class="pnum">149K</span><span class="pper">/bln</span></div>
          <ul class="plist">
            <li><i class="bi bi-check-circle-fill pi-y"></i> Proyek tidak terbatas</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> 25 Anggota tim</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> 20GB penyimpanan</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Dashboard lengkap</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Laporan PDF</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> API access</li>
            <li><i class="bi bi-dash-circle-fill pi-n"></i> Priority support</li>
          </ul>
          @auth
            <button class="pbtn fil" onclick="window.location.href='{{ route('dashboard') }}'">Ke Dashboard</button>
          @else
            <button class="pbtn fil" onclick="window.location.href='{{ route('register') }}'">Coba 14 Hari Gratis</button>
          @endauth
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-4 reveal" style="transition-delay:.20s">
        <div class="pcard">
          <div class="pname">Enterprise</div>
          <div class="pdesc">Solusi penuh untuk perusahaan & instansi</div>
          <div class="pval"><span class="pcur">Rp</span><span class="pnum">399K</span><span class="pper">/bln</span></div>
          <ul class="plist">
            <li><i class="bi bi-check-circle-fill pi-y"></i> Proyek tidak terbatas</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Anggota tidak terbatas</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> 100GB penyimpanan</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Custom branding</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Laporan PDF custom</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Full API access</li>
            <li><i class="bi bi-check-circle-fill pi-y"></i> Priority 24/7 support</li>
          </ul>
          <button class="pbtn out" onclick="smoothTo('#kontak')">Hubungi Sales</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     CONTACT
     ══════════════════════════════════════════════════════ -->
<section id="kontak" class="sp">
  <div class="container-xl">
    <div class="text-center mb-5 reveal">
      <div class="sec-tag c"><i class="bi bi-envelope-fill"></i> Kontak</div>
      <h2 class="sec-title">Siap untuk <span class="hl">Memulai?</span></h2>
      <div class="sec-line mx-auto"></div>
      <p class="sec-sub mx-auto text-center">Hubungi kami sekarang dan tim kami siap membantu Anda memulai perjalanan menuju proyek yang lebih terorganisir.</p>
    </div>
    <div class="row g-5 align-items-start">
      <div class="col-12 col-lg-7 reveal-l">
        <div class="cform">
          <div class="row g-3">
            <div class="col-12 col-sm-6">
              <label class="flbl">Nama Lengkap <span style="color:var(--red)">*</span></label>
              <input type="text" class="finput" placeholder="Nama Anda..."/>
            </div>
            <div class="col-12 col-sm-6">
              <label class="flbl">Email <span style="color:var(--red)">*</span></label>
              <input type="email" class="finput" placeholder="email@domain.com"/>
            </div>
            <div class="col-12 col-sm-6">
              <label class="flbl">Nama Perusahaan</label>
              <input type="text" class="finput" placeholder="PT / CV / Instansi..."/>
            </div>
            <div class="col-12 col-sm-6">
              <label class="flbl">Paket yang Diminati</label>
              <select class="finput" style="cursor:pointer;appearance:none;background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a99b8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E&quot;);background-repeat:no-repeat;background-position:right 12px center">
                <option value="" style="background:#071320">-- Pilih paket --</option>
                <option style="background:#071320">Starter (Gratis)</option>
                <option style="background:#071320">Professional (Rp149K)</option>
                <option style="background:#071320">Enterprise (Rp399K)</option>
                <option style="background:#071320">Custom / Enterprise Plus</option>
              </select>
            </div>
            <div class="col-12">
              <label class="flbl">Pesan</label>
              <textarea class="fta" placeholder="Ceritakan kebutuhan atau pertanyaan Anda..."></textarea>
            </div>
            <div class="col-12">
              <button class="bsend"><span><i class="bi bi-send-fill"></i> Kirim Pesan</span></button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-5 reveal-r">
        <div style="padding-left:clamp(0px,2vw,20px)">
          <div class="cinfo-item">
            <div class="ci-ico ci-c"><i class="bi bi-geo-alt-fill"></i></div>
            <div>
              <div class="ci-title">Alamat Kantor</div>
              <div class="ci-val">Jl. Hayam Wuruk No.28, Kota Pekalongan,<br>Jawa Tengah 51115</div>
            </div>
          </div>
          <div class="cinfo-item">
            <div class="ci-ico ci-a"><i class="bi bi-envelope-fill"></i></div>
            <div>
              <div class="ci-title">Email</div>
              <div class="ci-val">info@pms-system.co.id<br>support@pms-system.co.id</div>
            </div>
          </div>
          <div class="cinfo-item">
            <div class="ci-ico ci-g"><i class="bi bi-telephone-fill"></i></div>
            <div>
              <div class="ci-title">Telepon / WhatsApp</div>
              <div class="ci-val">+62 812 3456 7890<br>Senin–Jumat, 09.00–18.00 WIB</div>
            </div>
          </div>
          <div class="cinfo-item">
            <div class="ci-ico ci-p"><i class="bi bi-clock-fill"></i></div>
            <div>
              <div class="ci-title">Response Time</div>
              <div class="ci-val">Rata-rata balasan dalam &lt; 2 jam<br>Support 24/7 untuk paket Enterprise</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════════════
     FOOTER
     ══════════════════════════════════════════════════════ -->
<footer id="footer">
  <div class="ft-bg-glow"></div>
  <div class="container-xl">
    <div class="row g-5">
      <!-- Brand -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="nav-logo mb-2" style="font-size:20px;cursor:default">
          <div class="logo-mark"><i class="bi bi-diagram-3-fill"></i></div>
          <span class="logo-txt">PMS</span>
        </div>
        <p class="ft-desc">Platform manajemen proyek modern yang membantu tim Indonesia bekerja lebih cerdas, terorganisir, dan produktif setiap harinya.</p>
        <div class="ft-social">
          <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
          <a href="#" aria-label="GitHub"><i class="bi bi-github"></i></a>
          <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <!-- Product -->
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="ft-h">Produk</div>
        <ul class="ft-ul">
          <li><a href="#fitur">Fitur</a></li>
          <li><a href="#harga">Harga</a></li>
          <li><a href="#">Changelog</a></li>
          <li><a href="#">Roadmap</a></li>
          <li><a href="#">Status</a></li>
        </ul>
      </div>
      <!-- Perusahaan -->
      <div class="col-6 col-sm-4 col-lg-2">
        <div class="ft-h">Perusahaan</div>
        <ul class="ft-ul">
          <li><a href="#tentang">Tentang</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#kontak">Kontak</a></li>
          <li><a href="#">Partner</a></li>
        </ul>
      </div>
      <!-- Newsletter -->
      <div class="col-12 col-sm-4 col-lg-4">
        <div class="ft-h">Newsletter</div>
        <div class="ft-nl">
          <p>Dapatkan update terbaru, tips produktivitas, dan promo eksklusif langsung ke inbox Anda.</p>
          <div class="ft-nl-row">
            <input type="email" class="ft-nl-in" placeholder="Email Anda..."/>
            <button class="ft-nl-btn"><i class="bi bi-send-fill"></i> Subscribe</button>
          </div>
        </div>
      </div>
    </div>
    <div class="ft-sep"></div>
    <div class="ft-bottom">
      <div class="ft-copy">© 2025 <span>PMS</span> · Dibuat dengan ♥ di Indonesia</div>
      <div class="ft-online"><div class="ft-sdot"></div> Semua sistem operasional</div>
      <div class="ft-blinks">
        <a href="#">Privasi</a>
        <a href="#">Syarat</a>
        <a href="#">Cookie</a>
      </div>
    </div>
  </div>
</footer>

<!-- ══════════════════════════════════════════════════════
     FAB
     ══════════════════════════════════════════════════════ -->
<div class="fab-flash" id="fabFlash"></div>
<button class="fab" id="fab" aria-label="Kembali ke atas">
  <div class="fab-trail"></div>
  <div class="fab-trail2"></div>
  <div class="fab-ping"></div>
  <div class="fab-core" id="fabCore"><i class="bi bi-chevron-up" id="fabIco"></i></div>
  <!-- SVG progress ring -->
  <svg class="fab-svg" viewBox="0 0 58 58">
    <circle cx="29" cy="29" r="26" id="fabRing"/>
  </svg>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/auth/js/welcome.js') }}?v={{ time() }}"></script>
</body>
</html>
