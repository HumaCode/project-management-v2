<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Akun Belum Aktif — Project Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
  <link href="{{ asset('assets/auth/backend/css/sca.css') }}" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet"/>
  <style>
  /* ─── Variables ─── */
  :root{
    --bg:#040d1a;--bg2:#071428;
    --card:rgba(8,22,46,.72);
    --bd:rgba(0,200,255,.12);
    --cyan:#00c8ff;--blue:#0072c6;--blue2:#1a8fe3;
    --ok:#00e5a0;--warn:#f59e0b;--err:#ff4d6d;--purple:#a78bfa;
    --txt:#e2eaf4;--dim:#7a90a8;--muted:#3d5068;
    --input-bg:rgba(0,30,60,.55);
    --input-bd:rgba(0,160,220,.22);
    --r:14px;--rs:8px;
    --font:'Poppins',sans-serif;--mono:'DM Mono',monospace;
    --ease:cubic-bezier(.4,0,.2,1);
    --spring:cubic-bezier(.34,1.56,.64,1);
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  html,body{font-family:var(--font);background:var(--bg);color:var(--txt);overflow-x:hidden;overflow-y:auto;min-height:100%}

  /* ─── Canvas ─── */
  #bgc{position:fixed;inset:0;z-index:0;pointer-events:none}

  /* ─── Blobs ─── */
  .blob{position:fixed;border-radius:50%;filter:blur(90px);pointer-events:none;z-index:0;animation:blobFloat 14s ease-in-out infinite}
  .blob-1{width:480px;height:480px;background:radial-gradient(circle,rgba(0,114,198,.15) 0%,transparent 70%);top:-120px;left:-80px;animation-duration:18s}
  .blob-2{width:360px;height:360px;background:radial-gradient(circle,rgba(255,77,109,.08) 0%,transparent 70%);bottom:-80px;right:-40px;animation-duration:22s;animation-delay:-9s}
  .blob-3{width:260px;height:260px;background:radial-gradient(circle,rgba(0,200,255,.07) 0%,transparent 70%);top:40%;left:40%;transform:translate(-50%,-50%);animation-duration:26s;animation-delay:-5s}
  @keyframes blobFloat{0%,100%{transform:translate(0,0) scale(1)}33%{transform:translate(28px,-18px) scale(1.04)}66%{transform:translate(-18px,16px) scale(.97)}}

  /* ─── Page wrapper ─── */
  .page-wrap{position:relative;z-index:1;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px 16px}

  /* ─── Card ─── */
  .main-card{
    width:100%;max-width:1060px;
    background:var(--card);
    backdrop-filter:blur(24px) saturate(1.4);
    -webkit-backdrop-filter:blur(24px) saturate(1.4);
    border:1px solid var(--bd);
    border-radius:24px;
    overflow:hidden;
    box-shadow:0 0 0 1px rgba(0,200,255,.05),0 32px 80px rgba(0,0,0,.55),0 0 120px rgba(0,114,198,.1);
    animation:cardIn .9s cubic-bezier(.22,1,.36,1) both;
  }
  @keyframes cardIn{from{opacity:0;transform:translateY(32px) scale(.97)}to{opacity:1;transform:none}}

  /* ─── LEFT PANEL ─── */
  .left-panel{
    background:linear-gradient(150deg,rgba(0,30,70,.92) 0%,rgba(0,12,35,.97) 100%);
    border-right:1px solid var(--bd);
    padding:52px 44px;
    display:flex;flex-direction:column;justify-content:space-between;
    position:relative;overflow:hidden;
  }
  .left-panel::before{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(0,200,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(0,200,255,.035) 1px,transparent 1px);background-size:30px 30px;pointer-events:none}
  .left-panel::after{content:'';position:absolute;bottom:-60px;left:-60px;width:320px;height:320px;background:radial-gradient(circle,rgba(0,200,255,.07) 0%,transparent 65%);pointer-events:none}

  /* logo */
  .lp-logo{display:flex;align-items:center;gap:12px;animation:fadeUp .6s .15s both}
  .logo-ico{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--cyan),var(--blue));display:grid;place-items:center;font-size:22px;color:#fff;box-shadow:0 0 22px rgba(0,200,255,.35);position:relative;overflow:hidden;flex-shrink:0}
  .logo-ico::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.22),transparent 60%)}
  .logo-txt{font-family:var(--mono);font-size:13px;color:var(--dim);line-height:1.3}
  .logo-txt strong{display:block;font-size:16px;color:var(--txt);letter-spacing:.02em}

  /* status badge */
  .lp-status{display:flex;flex-direction:column;gap:20px;position:relative;z-index:1;animation:fadeUp .6s .3s both}
  .status-badge{
    display:inline-flex;align-items:center;gap:8px;
    padding:6px 14px;border-radius:20px;
    background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.28);
    font-family:var(--mono);font-size:11px;color:var(--warn);
    letter-spacing:1px;text-transform:uppercase;
    width:fit-content;margin-bottom:4px;
  }
  .status-dot{width:7px;height:7px;border-radius:50%;background:var(--warn);box-shadow:0 0 8px var(--warn);animation:sdot 2s ease-in-out infinite}
  @keyframes sdot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
  .lp-head h2{font-size:clamp(24px,3vw,34px);font-weight:800;line-height:1.2;margin-bottom:12px}
  .lp-head h2 .hl{background:linear-gradient(90deg,var(--warn),var(--err));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
  .lp-head p{font-size:13.5px;color:var(--dim);line-height:1.75;max-width:300px}

  /* steps */
  .lp-steps{display:flex;flex-direction:column;gap:12px;position:relative;z-index:1;animation:fadeUp .6s .5s both}
  .step-item{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:var(--rs);border:1px solid rgba(0,200,255,.07);background:rgba(0,200,255,.03);transition:background .2s,border-color .2s}
  .step-item:hover{background:rgba(0,200,255,.06);border-color:rgba(0,200,255,.14)}
  .step-num{width:28px;height:28px;border-radius:50%;flex-shrink:0;display:grid;place-items:center;font-family:var(--mono);font-size:11px;font-weight:700}
  .sn-done{background:rgba(0,229,160,.15);color:var(--ok);border:1px solid rgba(0,229,160,.3)}
  .sn-done i{font-size:13px}
  .sn-pending{background:rgba(245,158,11,.12);color:var(--warn);border:1px solid rgba(245,158,11,.28)}
  .sn-wait{background:rgba(100,116,139,.1);color:#64748b;border:1px solid rgba(100,116,139,.2)}
  .step-info .st-title{font-size:13px;font-weight:600;color:var(--txt)}
  .step-info .st-sub{font-size:11.5px;color:var(--muted);font-family:var(--mono);margin-top:1px}
  .step-item.active .st-title{color:var(--warn)}

  /* ─── RIGHT PANEL ─── */
  .right-panel{background:rgba(4,14,32,.6);display:flex;flex-direction:column}
  .rp-scroll{flex:1;padding:48px 44px;overflow-y:auto}

  /* header */
  .rp-head{margin-bottom:28px;animation:fadeUp .6s .25s both}
  .rp-tag{font-family:var(--mono);font-size:11px;color:var(--warn);letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;display:flex;align-items:center;gap:7px}
  .rp-tag-ico{width:28px;height:28px;border-radius:7px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.22);display:grid;place-items:center;font-size:12px;color:var(--warn)}
  .rp-head h1{font-size:clamp(20px,2.8vw,28px);font-weight:700;line-height:1.25;margin-bottom:6px}
  .rp-head p{font-size:13.5px;color:var(--dim);line-height:1.65}

  /* warn box */
  .warn-box{
    display:flex;gap:13px;padding:14px 16px;
    background:rgba(245,158,11,.06);
    border:1px solid rgba(245,158,11,.2);
    border-left:3px solid var(--warn);
    border-radius:var(--rs);
    margin-bottom:24px;
    animation:fadeUp .6s .35s both;
  }
  .warn-box i{font-size:20px;color:var(--warn);flex-shrink:0;margin-top:1px}
  .warn-box p{font-size:13px;color:var(--dim);line-height:1.65;margin:0}
  .warn-box p strong{color:var(--txt)}

  /* progress bar */
  .profile-prog{margin-bottom:24px;animation:fadeUp .6s .38s both}
  .prog-label{display:flex;justify-content:space-between;align-items:center;font-family:var(--mono);font-size:11px;color:var(--muted);margin-bottom:7px}
  .prog-label .prog-pct{color:var(--warn);font-weight:700}
  .prog-track{height:5px;border-radius:4px;background:rgba(255,255,255,.06);overflow:hidden}
  .prog-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,var(--warn),var(--err));transition:width .6s var(--ease)}

  /* section label */
  .fsec{font-family:var(--mono);font-size:10px;text-transform:uppercase;letter-spacing:1.8px;color:var(--muted);padding-bottom:10px;border-bottom:1px solid var(--bd);margin-bottom:16px;display:flex;align-items:center;gap:7px}
  .fsec i{font-size:12px;color:var(--cyan)}

  /* form fields */
  .fg{margin-bottom:18px;animation:fadeUp .5s both}
  .fg:nth-child(1){animation-delay:.4s}.fg:nth-child(2){animation-delay:.45s}.fg:nth-child(3){animation-delay:.5s}.fg:nth-child(4){animation-delay:.55s}.fg:nth-child(5){animation-delay:.6s}.fg:nth-child(6){animation-delay:.65s}
  .fl{display:block;font-family:var(--mono);font-size:11px;font-weight:600;color:var(--dim);text-transform:uppercase;letter-spacing:.8px;margin-bottom:7px;transition:color .2s}
  .fl .req{color:var(--err);margin-left:2px}
  .fg:focus-within .fl{color:var(--cyan)}

  /* input, select, textarea */
  .fi,.fsl,.fta{
    display:block;width:100%;
    background:var(--input-bg);
    border:1px solid var(--input-bd);
    border-radius:var(--rs);
    color:var(--txt);font-family:var(--font);font-size:13.5px;
    outline:none;
    transition:border-color .25s,background .25s,box-shadow .25s;
  }
  .fi{height:46px;padding:0 14px}
  .fta{padding:11px 14px;resize:vertical;min-height:96px;line-height:1.65}
  .fsl{
    height:46px;padding:0 36px 0 14px;cursor:pointer;
    -webkit-appearance:none;appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a90a8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 12px center;
  }
  .fi::placeholder,.fta::placeholder{color:var(--muted);font-size:13px}
  .fi:focus,.fsl:focus,.fta:focus{
    border-color:rgba(0,200,255,.42);
    background:rgba(0,40,80,.7);
    box-shadow:0 0 0 3px rgba(0,200,255,.1);
  }
  .fsl option{background:#071528;color:var(--txt)}
  .fi.err,.fsl.err,.fta.err{border-color:rgba(255,77,109,.45)!important;box-shadow:0 0 0 3px rgba(255,77,109,.07)!important}
  .emsg{font-size:11.5px;color:var(--err);margin-top:5px;display:none;font-family:var(--mono)}
  .fg.has-err .emsg{display:block}

  /* input with icon */
  .fiw{position:relative}
  .fiw .fi{padding-left:42px}
  .fiw .fi-ic{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:15px;pointer-events:none;transition:color .2s}
  .fg:focus-within .fi-ic{color:var(--cyan)}

  /* gender pills */
  .gender-pills{display:flex;gap:10px}
  .gpill{
    flex:1;height:46px;display:flex;align-items:center;justify-content:center;gap:8px;
    border-radius:var(--rs);background:var(--input-bg);
    border:1px solid var(--input-bd);
    color:var(--dim);font-family:var(--font);font-size:13.5px;
    cursor:pointer;transition:all .22s;-webkit-tap-highlight-color:transparent;
    user-select:none;
  }
  .gpill:hover{background:rgba(0,200,255,.08);border-color:rgba(0,200,255,.28);color:var(--txt)}
  .gpill.sel-l{background:rgba(0,200,255,.12);border-color:rgba(0,200,255,.4);color:var(--cyan)}
  .gpill.sel-p{background:rgba(167,139,250,.1);border-color:rgba(167,139,250,.35);color:var(--purple)}
  .gpill input{display:none}
  .gpill i{font-size:16px;transition:transform .2s}
  .gpill:hover i,.gpill.sel-l i,.gpill.sel-p i{transform:scale(1.15)}

  /* char counter */
  .ccnt{font-family:var(--mono);font-size:11px;color:var(--muted);text-align:right;margin-top:5px;transition:color .2s}
  .ccnt.near{color:var(--warn)}.ccnt.full{color:var(--err)}

  /* action row */
  .act-row{
    display:flex;align-items:center;justify-content:space-between;
    gap:10px;padding-top:20px;border-top:1px solid var(--bd);
    margin-top:4px;flex-wrap:wrap;
    animation:fadeUp .5s .7s both;
  }
  .act-row-left{font-size:13px;color:var(--dim);display:flex;align-items:center;gap:6px}
  .act-row-left a{color:var(--cyan);font-weight:600;transition:color .2s}
  .act-row-left a:hover{color:#fff}
  .btn-submit{
    height:46px;display:inline-flex;align-items:center;gap:8px;
    padding:0 26px;border-radius:var(--rs);
    background:linear-gradient(135deg,var(--blue),var(--cyan));
    border:none;color:#fff;
    font-family:var(--font);font-size:14px;font-weight:600;
    cursor:pointer;position:relative;overflow:hidden;
    transition:transform .22s,box-shadow .22s;
    box-shadow:0 4px 22px rgba(0,200,255,.28);
  }
  .btn-submit::before{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--cyan),var(--blue));opacity:0;transition:opacity .22s}
  .btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(0,200,255,.4)}
  .btn-submit:hover::before{opacity:1}
  .btn-submit>span{position:relative;z-index:1;display:flex;align-items:center;gap:8px}
  /* shimmer */
  .btn-submit::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent);transform:skewX(-20deg);animation:shimmer 3.5s 1.5s ease-in-out infinite}
  @keyframes shimmer{0%{left:-100%}40%,100%{left:160%}}
  .btn-submit.loading{pointer-events:none}.btn-submit.loading>span{opacity:0}
  .spinner{display:none;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:22px;height:22px}
  .btn-submit.loading .spinner{display:block}
  .spinner svg{animation:spin .9s linear infinite}
  @keyframes spin{to{transform:rotate(360deg)}}

  /* ─── SUCCESS STATE ─── */
  .success-overlay{
    display:none;flex-direction:column;align-items:center;justify-content:center;
    text-align:center;padding:40px 28px;
    animation:fadeUp .5s var(--spring) both;
  }
  .success-overlay.show{display:flex}
  .form-area.hidden{display:none}
  .sc-ring{
    width:80px;height:80px;border-radius:50%;
    background:rgba(0,229,160,.1);border:2px solid rgba(0,229,160,.3);
    display:grid;place-items:center;font-size:34px;color:var(--ok);
    margin-bottom:20px;
    box-shadow:0 0 32px rgba(0,229,160,.2);
    animation:scpulse 2.5s ease-in-out infinite;
  }
  @keyframes scpulse{0%,100%{box-shadow:0 0 24px rgba(0,229,160,.2)}50%{box-shadow:0 0 44px rgba(0,229,160,.4),0 0 60px rgba(0,229,160,.15)}}
  .sc-title{font-size:22px;font-weight:800;margin-bottom:8px}
  .sc-sub{font-size:14px;color:var(--dim);line-height:1.7;max-width:340px;margin-bottom:24px}
  .sc-info{
    display:flex;flex-direction:column;gap:10px;
    width:100%;max-width:360px;
    background:rgba(0,229,160,.04);border:1px solid rgba(0,229,160,.15);
    border-radius:var(--rs);padding:16px;margin-bottom:24px;
  }
  .sci-row{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--dim)}
  .sci-row i{color:var(--ok);font-size:15px;flex-shrink:0}
  .sci-row strong{color:var(--txt)}
  .btn-logout-sc{
    height:42px;display:inline-flex;align-items:center;gap:7px;
    padding:0 20px;border-radius:var(--rs);
    background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
    color:var(--dim);font-family:var(--font);font-size:13.5px;
    cursor:pointer;transition:all .2s;
  }
  .btn-logout-sc:hover{background:rgba(255,77,109,.1);border-color:rgba(255,77,109,.3);color:var(--err)}

  /* autofill */
  input:-webkit-autofill,input:-webkit-autofill:focus{-webkit-box-shadow:0 0 0 1000px rgba(0,30,60,.9) inset;-webkit-text-fill-color:var(--txt);transition:background-color 9999s ease}

  /* particles */
  .particle{position:fixed;border-radius:50%;pointer-events:none;z-index:0;animation:floatUp linear infinite;opacity:0}
  @keyframes floatUp{0%{transform:translateY(0) translateX(0);opacity:0}10%{opacity:.8}90%{opacity:.3}100%{transform:translateY(-100vh) translateX(var(--drift));opacity:0}}

  /* entrance */
  @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

  /* ─── RESPONSIVE ─── */
  @media(max-width:991.98px){
    .page-wrap{align-items:flex-start;padding:16px}
    .main-card{border-radius:18px}
    .left-panel{padding:32px 28px 28px;border-right:none;border-bottom:1px solid var(--bd);border-radius:18px 18px 0 0}
    .right-panel{border-radius:0 0 18px 18px}
    .lp-steps{flex-direction:row;flex-wrap:wrap;gap:8px}
    .step-item{flex:1 1 calc(50% - 4px);min-width:0}
    .rp-scroll{padding:36px 32px}
  }
  @media(max-width:767.98px){
    .rp-scroll{padding:28px 24px}
    .gender-pills{flex-direction:column;gap:8px}
    .act-row{flex-direction:column;align-items:stretch}
    .btn-submit{width:100%;justify-content:center;height:48px}
    .act-row-left{justify-content:center}
  }
  @media(max-width:575.98px){
    .left-panel{padding:22px 18px 18px}
    .rp-scroll{padding:22px 18px}
    .lp-steps{flex-direction:column}
    .step-item{flex:unset}
    .lp-head h2{font-size:20px}
    .rp-head h1{font-size:20px}
  }
  </style>
</head>
<body>

<!-- Blobs -->
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>
<canvas id="bgc"></canvas>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>

<div class="page-wrap">
  <div class="main-card container-fluid px-0" style="max-width:1060px">
    <div class="row g-0">

      <!-- ══ LEFT PANEL ══ -->
      <div class="col-12 col-lg-5 left-panel">

        <!-- Logo -->
        <div class="lp-logo">
          <div class="logo-ico"><i class="bi bi-diagram-3-fill"></i></div>
          <div class="logo-txt"><strong>PMS</strong>Project Management System</div>
        </div>

        <!-- Status & headline -->
        <div class="lp-status">
          <div>
            <div class="status-badge"><span class="status-dot"></span> Akun Pending</div>
            <div class="lp-head">
              <h2>Selesaikan<br><span class="hl">Profil Anda</span></h2>
              <p>Akun Anda sudah terdaftar namun belum aktif. Lengkapi informasi di bawah agar admin dapat memverifikasi dan mengaktifkan akun Anda.</p>
            </div>
          </div>

          <!-- Steps -->
          <div class="lp-steps">
            <div class="step-item">
              <div class="step-num sn-done"><i class="bi bi-check-lg"></i></div>
              <div class="step-info">
                <div class="st-title">Registrasi</div>
                <div class="st-sub">Akun dibuat</div>
              </div>
            </div>
            <div class="step-item {{ !empty(auth()->user()->bio) ? '' : 'active' }}" id="step2">
              <div class="step-num {{ !empty(auth()->user()->bio) ? 'sn-done' : 'sn-pending' }}" id="sn2">
                @if(!empty(auth()->user()->bio))
                  <i class="bi bi-check-lg"></i>
                @else
                  2
                @endif
              </div>
              <div class="step-info">
                <div class="st-title">Lengkapi Profil</div>
                <div class="st-sub" id="ss2">{{ !empty(auth()->user()->bio) ? 'Selesai' : 'Sedang dilakukan' }}</div>
              </div>
            </div>
            <div class="step-item {{ !empty(auth()->user()->bio) ? '' : '' }}" id="step3">
              <div class="step-num {{ !empty(auth()->user()->bio) ? 'sn-done' : 'sn-wait' }}" id="sn3">
                @if(!empty(auth()->user()->bio))
                  <i class="bi bi-check-lg"></i>
                @else
                  3
                @endif
              </div>
              <div class="step-info">
                <div class="st-title">Verifikasi Admin</div>
                <div class="st-sub" id="ss3">{{ !empty(auth()->user()->bio) ? 'Selesai' : 'Menunggu' }}</div>
              </div>
            </div>
            <div class="step-item {{ !empty(auth()->user()->bio) ? 'active' : '' }}" id="step4">
              <div class="step-num {{ !empty(auth()->user()->bio) ? 'sn-pending' : 'sn-wait' }}" id="sn4">4</div>
              <div class="step-info">
                <div class="st-title">Akun Aktif</div>
                <div class="st-sub" id="ss4">{{ !empty(auth()->user()->bio) ? 'Sedang diproses' : 'Belum' }}</div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /left-panel -->

      <!-- ══ RIGHT PANEL ══ -->
      <div class="col-12 col-lg-7 right-panel">
        <div class="rp-scroll">

          @if(empty(auth()->user()->bio))
          <!-- Header -->
          <div class="rp-head">
            <div class="rp-tag">
              <div class="rp-tag-ico"><i class="bi bi-person-fill-exclamation"></i></div>
              Kelengkapan Data
            </div>
            <h1>Lengkapi Informasi Akun</h1>
            <p>Isi seluruh field di bawah dengan benar. Data ini digunakan admin untuk memverifikasi identitas Anda sebelum mengaktifkan akun.</p>
          </div>

          <!-- Warning box -->
          <div class="warn-box">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <p>Akun <strong>{{ auth()->user()->email }}</strong> terdaftar pada <strong>{{ auth()->user()->created_at->format('d M Y') }}</strong> namun belum diaktifkan. Lengkapi data berikut untuk melanjutkan proses aktivasi.</p>
          </div>

          @php
            $countFields = ['name', 'username', 'email', 'gender', 'city', 'phone'];
            $filledCount = 0;
            foreach($countFields as $f) {
                if(!empty(auth()->user()->$f)) $filledCount++;
            }
            $initialPct = !empty(auth()->user()->bio) ? 100 : round(($filledCount / count($countFields)) * 100);
          @endphp
          <!-- Progress -->
          <div class="profile-prog">
            <div class="prog-label">
              <span>Kelengkapan Profil</span>
              <span class="prog-pct" id="progPct">{{ $initialPct }}%</span>
            </div>
            <div class="prog-track"><div class="prog-fill" id="progFill" style="width: {{ $initialPct }}%"></div></div>
          </div>
          @endif

          <!-- ── FORM ── -->
          <div id="formArea" class="form-area {{ !empty(auth()->user()->bio) ? 'hidden' : '' }}">

            @if(empty(auth()->user()->name) || empty(auth()->user()->username) || empty(auth()->user()->email) || empty(auth()->user()->gender))
            <div class="fsec"><i class="bi bi-person-circle"></i> Identitas Diri</div>
            @endif

            @if(empty(auth()->user()->name))
            <!-- Nama Lengkap (readonly, dari register) -->
            <div class="fg">
              <label class="fl">Nama Lengkap</label>
              <div class="fiw">
                <i class="bi bi-person-fill fi-ic"></i>
                <input type="text" class="fi" value="{{ auth()->user()->name }}" readonly style="opacity:.6;cursor:not-allowed"/>
              </div>
            </div>
            @endif

            @if(empty(auth()->user()->username))
            <!-- Username -->
            <div class="fg" id="fgUsername">
              <label class="fl">Username <span class="req">*</span></label>
              <div class="fiw">
                <i class="bi bi-at fi-ic"></i>
                <input type="text" class="fi" id="fUsername" placeholder="Contoh: budi_santoso" autocomplete="off"/>
              </div>
              <div class="emsg">Username wajib diisi (minimal 3 karakter).</div>
            </div>
            @endif

            @if(empty(auth()->user()->email))
            <!-- Email (readonly) -->
            <div class="fg">
              <label class="fl">Email</label>
              <div class="fiw">
                <i class="bi bi-envelope-fill fi-ic"></i>
                <input type="email" class="fi" value="{{ auth()->user()->email }}" readonly style="opacity:.6;cursor:not-allowed"/>
              </div>
            </div>
            @endif

            @if(empty(auth()->user()->gender))
            <!-- Jenis Kelamin -->
            <div class="fg" id="fgGender">
              <label class="fl">Jenis Kelamin <span class="req">*</span></label>
              <div class="gender-pills">
                <label class="gpill" id="pillL">
                  <input type="radio" name="gender" value="L" id="gL"/>
                  <i class="bi bi-gender-male"></i> Laki-laki
                </label>
                <label class="gpill" id="pillP">
                  <input type="radio" name="gender" value="P" id="gP"/>
                  <i class="bi bi-gender-female"></i> Perempuan
                </label>
              </div>
              <div class="emsg" id="emGender">Jenis kelamin wajib dipilih.</div>
            </div>
            @endif

            @if(!empty(auth()->user()->gender))
              <input type="hidden" name="gender" value="{{ auth()->user()->gender }}" id="g{{ auth()->user()->gender }}">
            @endif



            @if(empty(auth()->user()->city))
            <!-- Kota -->
            <div class="fg" id="fgKota">
              <label class="fl">Kota / Kabupaten <span class="req">*</span></label>
              <div class="fiw">
                <i class="bi bi-building fi-ic"></i>
                <input type="text" class="fi" id="fKota" placeholder="Contoh: Kota Pekalongan" autocomplete="off"/>
              </div>
              <div class="emsg">Kota / Kabupaten wajib diisi.</div>
            </div>
            @endif

            @if(empty(auth()->user()->phone))
            <!-- No. Telepon -->
            <div class="fg">
              <label class="fl">No. Telepon / WhatsApp</label>
              <div class="fiw">
                <i class="bi bi-telephone-fill fi-ic"></i>
                <input type="tel" class="fi" id="fTelp" placeholder="Contoh: 0812-3456-7890" autocomplete="off"/>
              </div>
            </div>
            @endif



            <!-- Keterangan / Bio -->
            <div class="fg" id="fgKet">
              <label class="fl">Keterangan <span class="req">*</span></label>
              <textarea class="fta" id="fKet" placeholder="Jelaskan singkat keperluan atau alasan Anda mendaftar di platform ini..." maxlength="400" rows="4"></textarea>
              <div class="ccnt" id="cKet">0 / 400</div>
              <div class="emsg">Keterangan wajib diisi (minimal 20 karakter).</div>
            </div>

            <!-- Action -->
            <div class="act-row">
              <div class="act-row-left">
                <i class="bi bi-box-arrow-left" style="color:var(--muted)"></i>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Kembali ke Login</a>
              </div>
              <button class="btn-submit" id="btnSubmit">
                <span><i class="bi bi-send-fill"></i> Kirim & Tunggu Verifikasi</span>
                <div class="spinner">
                  <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                    <circle cx="11" cy="11" r="9" stroke="rgba(255,255,255,.3)" stroke-width="2.5"/>
                    <path d="M11 2a9 9 0 0 1 9 9" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
                  </svg>
                </div>
              </button>
            </div>

          </div><!-- /form-area -->

          <!-- ── SUCCESS STATE ── -->
          <div class="success-overlay {{ !empty(auth()->user()->bio) ? 'show' : '' }}" id="successOverlay">
            <div class="sc-ring"><i class="bi bi-check-lg"></i></div>
            <h2 class="sc-title">Data Terkirim!</h2>
            <p class="sc-sub">Profil Anda telah berhasil dilengkapi dan sedang menunggu verifikasi dari administrator sistem.</p>
            <div class="sc-info">
              <div class="sci-row"><i class="bi bi-clock-fill"></i><span>Proses verifikasi biasanya <strong>1×24 jam</strong> hari kerja</span></div>
              <div class="sci-row"><i class="bi bi-envelope-fill"></i><span>Notifikasi akan dikirim ke <strong>{{ auth()->user()->email }}</strong></span></div>
              <div class="sci-row"><i class="bi bi-shield-fill-check"></i><span>Data Anda <strong>aman</strong> dan terenkripsi</span></div>
            </div>
            <button class="btn-logout-sc" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-left"></i> Kembali ke Halaman Login
            </button>
          </div>

        </div>
      </div><!-- /right-panel -->

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/auth/backend/js/sca.js') }}"></script>
<script>
/* ─── Canvas ─── */
(function(){
  var cv=document.getElementById('bgc'),ctx=cv.getContext('2d'),W,H,ns=[];
  var C=['rgba(0,200,255,','rgba(0,114,198,','rgba(245,158,11,'];
  function init(){W=cv.width=innerWidth;H=cv.height=innerHeight;ns=[];var n=Math.max(16,Math.floor(W*H/30000));for(var i=0;i<n;i++)ns.push({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.32,vy:(Math.random()-.5)*.32,r:Math.random()*1.8+.6,c:C[~~(Math.random()*3)],p:Math.random()*6.28});}
  function draw(){ctx.clearRect(0,0,W,H);ns.forEach(function(n){n.x+=n.vx;n.y+=n.vy;n.p+=.015;if(n.x<-8)n.x=W+8;if(n.x>W+8)n.x=-8;if(n.y<-8)n.y=H+8;if(n.y>H+8)n.y=-8;});for(var i=0;i<ns.length;i++)for(var j=i+1;j<ns.length;j++){var a=ns[i],b=ns[j],dx=a.x-b.x,dy=a.y-b.y,d=Math.sqrt(dx*dx+dy*dy);if(d<135){ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);ctx.strokeStyle='rgba(0,140,200,'+(1-d/135)*.12+')';ctx.lineWidth=.65;ctx.stroke();}}
  ns.forEach(function(n){var a=.4+Math.sin(n.p)*.3;ctx.beginPath();ctx.arc(n.x,n.y,n.r,0,6.28);ctx.fillStyle=n.c+a+')';ctx.fill();});requestAnimationFrame(draw);}
  window.addEventListener('resize',init,{passive:true});init();draw();
})();

/* ─── Particles ─── */
(function(){
  var hues=['rgba(0,200,255,','rgba(245,158,11,','rgba(0,229,160,'];
  for(var i=0;i<14;i++){
    var p=document.createElement('div');p.className='particle';
    var sz=Math.random()*3.5+1.5,c=hues[~~(Math.random()*3)],dur=14+Math.random()*22;
    p.style.cssText='width:'+sz+'px;height:'+sz+'px;left:'+Math.random()*100+'vw;bottom:-10px;background:'+c+(0.35+Math.random()*.3)+'));animation-duration:'+dur+'s;animation-delay:'+(Math.random()*-22)+'s;--drift:'+((Math.random()-.5)*100)+'px;box-shadow:0 0 '+(sz*3)+'px '+c+'.5));';
    document.body.appendChild(p);
  }
})();

/* ─── Gender pills ─── */
var pillL=document.getElementById('pillL'),pillP=document.getElementById('pillP');
var gL=document.getElementById('gL'),gP=document.getElementById('gP');
function selGender(v){
  if(pillL) pillL.classList.toggle('sel-l', v==='L');
  if(pillP) pillP.classList.toggle('sel-p', v==='P');
  var fg = document.getElementById('fgGender');
  if(fg) fg.classList.remove('has-err');
  updateProgress();
}
if(pillL) pillL.addEventListener('click',function(){ if(gL){ gL.checked=true; selGender('L'); } });
if(pillP) pillP.addEventListener('click',function(){ if(gP){ gP.checked=true; selGender('P'); } });

/* ─── Char counter ─── */
var ketEl=document.getElementById('fKet'),cntEl=document.getElementById('cKet');
function updKet(){
  if(!ketEl) return;
  var n=ketEl.value.length;
  if(cntEl){
    cntEl.textContent=n+' / 400';
    cntEl.className='ccnt'+(n>=400?' full':n>=340?' near':'');
  }
  updateProgress();
}
if(ketEl) ketEl.addEventListener('input',updKet);

/* ─── Progress bar ─── */
function updateProgress(){
  var fields = {
    name: '{{ !empty(auth()->user()->name) }}',
    username: document.getElementById('fUsername') ? document.getElementById('fUsername').value.trim() : '{{ !empty(auth()->user()->username) }}',
    email: '{{ !empty(auth()->user()->email) }}',
    gender: (document.querySelector('input[name="gender"]:checked') || document.querySelector('input[name="gender"][type="hidden"]')) ? true : false,
    city: document.getElementById('fKota') ? document.getElementById('fKota').value.trim() : '{{ !empty(auth()->user()->city) }}',
    phone: document.getElementById('fTelp') ? document.getElementById('fTelp').value.trim() : '{{ !empty(auth()->user()->phone) }}'
  };

  var total = Object.keys(fields).length;
  var filled = 0;
  
  for (var key in fields) {
    if (fields[key] && fields[key] !== '0' && fields[key] !== '') filled++;
  }

  var pct = Math.round((filled / total) * 100);
  
  var fillEl = document.getElementById('progFill');
  var pctEl = document.getElementById('progPct');
  var step2 = document.getElementById('step2');
  var sn2 = document.getElementById('sn2');
  var ss2 = document.getElementById('ss2');
  var step3 = document.getElementById('step3');
  var sn3 = document.getElementById('sn3');
  var ss3 = document.getElementById('ss3');

  if(fillEl) {
    fillEl.style.width=pct+'%';
    if(pct >= 100) {
      fillEl.style.background = 'linear-gradient(90deg, var(--ok), var(--cyan))';
      if(pctEl) pctEl.style.color = 'var(--ok)';
      
      // Step 2 -> Done
      if(step2) step2.classList.remove('active');
      if(sn2) { sn2.className = 'step-num sn-done'; sn2.innerHTML = '<i class="bi bi-check-lg"></i>'; }
      if(ss2) ss2.textContent = 'Selesai';
      
      // Step 3 -> Done
      if(step3) step3.classList.remove('active');
      if(sn3) { sn3.className = 'step-num sn-done'; sn3.innerHTML = '<i class="bi bi-check-lg"></i>'; }
      if(ss3) ss3.textContent = 'Selesai';

      // Step 4 -> Active
      var step4 = document.getElementById('step4'), sn4 = document.getElementById('sn4'), ss4 = document.getElementById('ss4');
      if(step4) step4.classList.add('active');
      if(sn4) { sn4.className = 'step-num sn-pending'; sn4.textContent = '4'; }
      if(ss4) ss4.textContent = 'Sedang diproses';
    } else {
      fillEl.style.background = '';
      if(pctEl) pctEl.style.color = '';
      
      // Step 2 -> Active
      if(step2) step2.classList.add('active');
      if(sn2) { sn2.className = 'step-num sn-pending'; sn2.textContent = '2'; }
      if(ss2) ss2.textContent = 'Sedang dilakukan';
      
      // Step 3 -> Wait
      if(step3) step3.classList.remove('active');
      if(sn3) { sn3.className = 'step-num sn-wait'; sn3.textContent = '3'; }
      if(ss3) ss3.textContent = 'Menunggu';

      // Step 4 -> Wait
      var step4 = document.getElementById('step4'), sn4 = document.getElementById('sn4'), ss4 = document.getElementById('ss4');
      if(step4) step4.classList.remove('active');
      if(sn4) { sn4.className = 'step-num sn-wait'; sn4.textContent = '4'; }
      if(ss4) ss4.textContent = 'Belum';
    }
  }
  if(pctEl) pctEl.textContent=pct+'%';
}

// Initial run
updateProgress();

// bind to existing inputs
['fUsername','fKota','fTelp'].forEach(function(id){
  var el = document.getElementById(id);
  if(el){
    el.addEventListener('input',updateProgress);
    el.addEventListener('change',updateProgress);
  }
});

/* ─── Validation & Submit ─── */
document.getElementById('btnSubmit').addEventListener('click',function(){
  var ok=true;

  // gender validation if visible
  var gField = document.getElementById('fgGender');
  if(gField){
    var gChecked = document.querySelector('input[name="gender"]:checked');
    if(!gChecked){
      gField.classList.add('has-err');
      ok=false;
    } else {
      gField.classList.remove('has-err');
    }
  }

  // fields validation
  var fieldsToValidate = [];
  if(document.getElementById('fUsername')) {
    fieldsToValidate.push({id:'fUsername', fgId:'fgUsername', check:function(v){return v.trim().length>=3;}});
  }
  if(document.getElementById('fKota')) {
    fieldsToValidate.push({id:'fKota', fgId:'fgKota', check:function(v){return v.trim().length>1;}});
  }
  if(document.getElementById('fKet')) {
    fieldsToValidate.push({id:'fKet', fgId:'fgKet', check:function(v){return v.trim().length>=20;}});
  }

  fieldsToValidate.forEach(function(f){
    var el=document.getElementById(f.id);
    var fg=f.fgId?document.getElementById(f.fgId):el.closest('.fg');
    if(!f.check(el.value)){
      fg.classList.add('has-err');el.classList.add('err');ok=false;
    } else {
      fg.classList.remove('has-err');el.classList.remove('err');
    }
  });

  if(!ok){
    var first=document.querySelector('.fg.has-err');
    if(first)first.scrollIntoView({behavior:'smooth',block:'center'});
    return;
  }

  // Loading
  var btn=this;
  btn.classList.add('loading');

  // Prepare Data
  var formData = new FormData();
  formData.append('_token', '{{ csrf_token() }}');
  
  var genderVal = document.querySelector('input[name="gender"]:checked') || document.querySelector('input[name="gender"][type="hidden"]');
  if(genderVal) formData.append('gender', genderVal.value);
  
  var cityEl = document.getElementById('fKota');
  if(cityEl) formData.append('city', cityEl.value);

  var usernameEl = document.getElementById('fUsername');
  if(usernameEl) formData.append('username', usernameEl.value);
  
  var telpEl = document.getElementById('fTelp');
  if(telpEl) formData.append('phone', telpEl.value);
  
  var ketEl = document.getElementById('fKet');
  if(ketEl) formData.append('bio', ketEl.value);

  // AJAX Submit
  fetch('{{ route('inactive.update') }}', {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(response => response.json())
  .then(data => {
    btn.classList.remove('loading');
    if(data.success){
      SCA.toast({
        type: "success",
        title: "Berhasil!",
        message: data.message || "Profil berhasil dilengkapi dan dikirim ke admin.",
        position: "top-right",
      });
      // Show success
      document.getElementById('formArea').classList.add('hidden');
      document.getElementById('successOverlay').classList.add('show');
      // Update progress to 100%
      if(document.getElementById('progFill')) document.getElementById('progFill').style.width='100%';
      if(document.getElementById('progPct')) document.getElementById('progPct').textContent='100%';
      // Scroll to top of right panel
      var rp=document.querySelector('.rp-scroll');
      if(rp)rp.scrollTo({top:0,behavior:'smooth'});
    } else {
      SCA.toast({
        type: "error",
        title: "Gagal!",
        message: data.message || "Terjadi kesalahan. Silakan coba lagi.",
        position: "top-right",
      });
    }
  })
  .catch(error => {
    btn.classList.remove('loading');
    console.error('Error:', error);
    SCA.toast({
      type: "error",
      title: "Gagal!",
      message: "Terjadi kesalahan jaringan. Silakan coba lagi.",
      position: "top-right",
    });
  });
});

/* ─── Live clear errors on input ─── */
document.querySelectorAll('.fi,.fsl,.fta').forEach(function(el){
  el.addEventListener('input',function(){
    if(this.value){
      this.classList.remove('err');
      var fg=this.closest('.fg');if(fg)fg.classList.remove('has-err');
    }
  });
});
</script>
</body>
</html>
