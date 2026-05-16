<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Maintenance Mode &mdash; Project Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <style>
        /* ══ Reset & Variables ══ */
        :root {
            --bg: #050e1d;
            --clr: #f59e0b; /* Maintenance Orange */
            --clr-rgb: 245, 158, 11;
            --cyan: #00c8ff;
            --blue: #0072c6;
            --txt: #e2eaf4;
            --dim: #7a90a8;
            --muted: #3d5068;
            --bd: rgba(245, 158, 11, 0.15);
            --card: rgba(7, 21, 40, 0.85);
            --font: "Poppins", sans-serif;
            --mono: "DM Mono", monospace;
            --r: 20px;
            --rs: 12px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--txt);
            min-height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--clr) transparent;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ══ Grid lines background ══ */
        .grid-bg {
            position: fixed;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(245, 158, 11, 0.04) 1px,
                    transparent 1px),
                linear-gradient(90deg,
                    rgba(245, 158, 11, 0.04) 1px,
                    transparent 1px);
            background-size: 60px 60px;
            animation: gridmove 20s linear infinite;
        }

        @keyframes gridmove {
            to {
                background-position: 60px 60px;
            }
        }

        /* ══ Scan line ══ */
        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--clr),
                    transparent);
            z-index: 10;
            pointer-events: none;
            animation: scan 4s ease-in-out infinite;
            box-shadow:
                0 0 20px var(--clr),
                0 0 40px var(--clr);
        }

        @keyframes scan {
            0% { top: -5px; opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { top: 105%; opacity: 0; }
        }

        /* ══ Corner decorations ══ */
        .corner {
            position: fixed;
            width: 60px;
            height: 60px;
            z-index: 2;
            pointer-events: none;
            opacity: 0.4;
        }

        .corner-tl { top: 20px; left: 20px; border-top: 2px solid var(--clr); border-left: 2px solid var(--clr); border-radius: 4px 0 0 0; }
        .corner-tr { top: 20px; right: 20px; border-top: 2px solid var(--clr); border-right: 2px solid var(--clr); border-radius: 0 4px 0 0; }
        .corner-bl { bottom: 20px; left: 20px; border-bottom: 2px solid var(--clr); border-left: 2px solid var(--clr); border-radius: 0 0 0 4px; }
        .corner-br { bottom: 20px; right: 20px; border-bottom: 2px solid var(--clr); border-right: 2px solid var(--clr); border-radius: 0 0 4px 0; }

        .corner-tl, .corner-tr, .corner-bl, .corner-br { animation: cornerpulse 3s ease-in-out infinite; }
        @keyframes cornerpulse {
            0%, 100% { opacity: 0.25; }
            50% { opacity: 0.65; filter: drop-shadow(0 0 8px var(--clr)); }
        }

        /* ══ Main layout ══ */
        .page-wrap {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }

        .card-wrap {
            width: 100%;
            max-width: 620px;
            text-align: center;
            animation: cardin 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }

        @keyframes cardin {
            from { opacity: 0; transform: translateY(40px) scale(0.94); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ══ Error code display ══ */
        .err-code {
            font-family: var(--mono);
            font-size: clamp(60px, 12vw, 100px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -2px;
            background: linear-gradient(135deg, var(--clr), color-mix(in srgb, var(--clr) 60%, #fff 40%));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 30px var(--clr));
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        /* ══ Icon area ══ */
        .icon-zone {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-bg {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: radial-gradient(circle, color-mix(in srgb, var(--clr) 15%, transparent), transparent 70%);
            animation: iconbg 3s ease-in-out infinite;
        }

        @keyframes iconbg {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .icon-main {
            position: relative;
            z-index: 2;
            font-size: 64px;
            color: var(--clr);
            filter: drop-shadow(0 0 16px var(--clr));
            animation: iconbounce 2s ease-in-out infinite;
            display: block;
        }

        @keyframes iconbounce {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-12px) scale(1.05); }
        }

        /* ══ Text ══ */
        .err-type {
            font-family: var(--mono);
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--clr);
            margin-bottom: 10px;
            opacity: 0.85;
        }

        .err-title { font-size: clamp(22px, 4vw, 32px); font-weight: 800; line-height: 1.2; margin-bottom: 12px; }
        .err-desc { font-size: 15px; color: var(--dim); line-height: 1.7; max-width: 500px; margin: 0 auto 28px; }

        /* ══ Neon divider ══ */
        .neon-div {
            width: 80px; height: 2px;
            background: linear-gradient(90deg, transparent, var(--clr), transparent);
            margin: 0 auto 24px; border-radius: 2px; box-shadow: 0 0 12px var(--clr);
            animation: divpulse 2s ease-in-out infinite;
        }

        @keyframes divpulse {
            0%, 100% { width: 60px; opacity: 0.7; }
            50% { width: 120px; opacity: 1; }
        }

        /* ══ Info box ══ */
        .info-box {
            background: rgba(245, 158, 11, 0.05);
            border: 1px solid rgba(245, 158, 11, 0.15);
            border-left: 3px solid var(--clr);
            border-radius: var(--rs);
            padding: 18px;
            margin-bottom: 28px;
            text-align: left;
            font-family: var(--mono);
            font-size: 12px;
            color: var(--dim);
            line-height: 1.8;
        }

        .info-box .ib-row { display: flex; gap: 8px; align-items: baseline; }
        .info-box .ib-lbl { color: var(--clr); font-weight: 600; min-width: 100px; flex-shrink: 0; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }

        /* ══ Buttons ══ */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0 30px;
            height: 48px;
            border-radius: var(--rs);
            background: linear-gradient(135deg, color-mix(in srgb, var(--clr) 70%, #000), var(--clr));
            border: none;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(245, 158, 11, 0.4);
            color: #fff;
        }

        /* ══ Status bar ══ */
        .status-bar {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 40px; font-family: var(--mono); font-size: 11px; color: var(--muted);
        }

        .status-dot {
            width: 8px; height: 8px; border-radius: 50%; background: var(--clr);
            box-shadow: 0 0 10px var(--clr); animation: sdot 2s ease-in-out infinite;
        }

        @keyframes sdot { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    </style>
</head>

<body>
    <div class="grid-bg"></div>
    <div class="scanline"></div>
    <div class="corner corner-tl"></div>
    <div class="corner corner-tr"></div>
    <div class="corner corner-bl"></div>
    <div class="corner corner-br"></div>

    <div class="page-wrap">
        <div class="card-wrap">
            <div class="icon-zone">
                <div class="icon-bg"></div>
                <i class="bi bi-cone-striped icon-main"></i>
            </div>

            <div class="err-type">System Status</div>
            <div class="err-code">Maintenance</div>
            <h1 class="err-title">Pembaruan Sistem Rutin</h1>
            <p class="err-desc">Kami sedang melakukan pemeliharaan infrastruktur untuk meningkatkan performa platform Anda. Mohon tunggu sebentar.</p>

            <div class="neon-div"></div>

            <div class="info-box text-center">
                <div class="maint-msg mb-4">
                    "{{ $message }}"
                </div>
                
                @if($endTime)
                <!-- ══ Countdown Timer ══ -->
                <div class="countdown-container">
                    <div class="cd-item">
                        <span class="cd-val" id="days">00</span>
                        <span class="cd-lbl">Hari</span>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-item">
                        <span class="cd-val" id="hours">00</span>
                        <span class="cd-lbl">Jam</span>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-item">
                        <span class="cd-val" id="minutes">00</span>
                        <span class="cd-lbl">Menit</span>
                    </div>
                    <div class="cd-sep">:</div>
                    <div class="cd-item">
                        <span class="cd-val" id="seconds">00</span>
                        <span class="cd-lbl">Detik</span>
                    </div>
                </div>
                @endif
            </div>

            <style>
                .info-box {
                    background: rgba(245, 158, 11, 0.05);
                    border: 1px solid rgba(245, 158, 11, 0.15);
                    border-radius: var(--rs);
                    padding: 30px;
                    margin-bottom: 28px;
                    position: relative;
                    overflow: hidden;
                }
                .maint-msg {
                    font-size: 18px;
                    color: var(--txt);
                    line-height: 1.6;
                    font-style: italic;
                    opacity: 0.9;
                }
                .countdown-container {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 15px;
                    padding: 20px;
                    background: rgba(0,0,0,0.2);
                    border-radius: var(--rs);
                    border: 1px dashed var(--bd);
                }
                .cd-item { display: flex; flex-direction: column; align-items: center; min-width: 60px; }
                .cd-val { 
                    font-family: var(--mono); 
                    font-size: 32px; 
                    font-weight: 700; 
                    color: var(--clr); 
                    line-height: 1;
                    text-shadow: 0 0 15px rgba(245, 158, 11, 0.5);
                }
                .cd-lbl { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-top: 5px; }
                .cd-sep { font-size: 24px; font-weight: 700; color: var(--muted); opacity: 0.5; margin-bottom: 15px; }
            </style>

            <script>
                @if($endTime)
                const countDownDate = new Date("{{ $endTime }}").getTime();

                const x = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = countDownDate - now;

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("days").innerHTML = days.toString().padStart(2, '0');
                    document.getElementById("hours").innerHTML = hours.toString().padStart(2, '0');
                    document.getElementById("minutes").innerHTML = minutes.toString().padStart(2, '0');
                    document.getElementById("seconds").innerHTML = seconds.toString().padStart(2, '0');

                    if (distance < 0) {
                        clearInterval(x);
                        document.querySelector(".countdown-container").innerHTML = "<span style='color: var(--clr); font-family: var(--mono); font-weight: bold;'>PEMELIHARAAN SELESAI. SISTEM SEGERA KEMBALI ONLINE.</span>";
                    }
                }, 1000);
                @endif
            </script>

            <div class="btn-row">
                <a href="mailto:{{ config('app.email', 'admin@pmssystem.id') }}" class="btn-primary">
                    <i class="bi bi-envelope-fill"></i> Hubungi Dukungan
                </a>
            </div>

            <div class="status-bar">
                <div class="status-dot"></div>
                <span>MODE PEMELIHARAAN AKTIF &bull; PMS v2.0</span>
            </div>
        </div>
    </div>
</body>

</html>
