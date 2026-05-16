<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #040d1a;
            margin: 0;
            padding: 0;
            color: #e1e7ef;
        }
        .wrapper {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #071528;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            border: 1px solid #1e293b;
        }
        .header {
            background: linear-gradient(135deg, #0061ff, #60efff);
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #ffffff;
            font-weight: 800;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            color: #f59e0b;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .message {
            line-height: 1.6;
            font-size: 15px;
            color: #94a3b8;
            margin-bottom: 30px;
        }
        .card {
            background-color: rgba(255,255,255,0.03);
            border: 1px solid rgba(0, 200, 255, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .card-row {
            margin-bottom: 10px;
            display: flex;
        }
        .card-label {
            width: 100px;
            color: #64748b;
            font-size: 13px;
        }
        .card-value {
            color: #f1f5f9;
            font-weight: 600;
            font-size: 14px;
        }
        .btn-wrap {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            background: linear-gradient(135deg, #00c8ff, #0061ff);
            color: #ffffff !important;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 200, 255, 0.4);
        }
        .footer {
            padding: 25px;
            text-align: center;
            border-top: 1px solid #1e293b;
            font-size: 11px;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>PROJECT MANAGEMENT SYSTEM</h1>
        </div>
        <div class="content">
            <div class="greeting">Halo Admin! 🔔</div>
            <div class="message">
                Email ini dikirim secara otomatis untuk menguji konfigurasi SMTP pada aplikasi Anda. Jika Anda melihat pesan ini, berarti sistem notifikasi telah terhubung dengan sempurna.
            </div>
            
            <div class="card">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="color: #64748b; font-size: 13px; padding-bottom: 8px;" width="100">Status</td>
                        <td style="color: #10b981; font-weight: 700; font-size: 14px; padding-bottom: 8px;">: TERKONEKSI</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 13px; padding-bottom: 8px;">Server</td>
                        <td style="color: #f1f5f9; font-weight: 600; font-size: 14px; padding-bottom: 8px;">: {{ config('mail.mailers.smtp.host') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; font-size: 13px;">Waktu Tes</td>
                        <td style="color: #f1f5f9; font-weight: 600; font-size: 14px;">: {{ date('d M Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            <div class="btn-wrap">
                <a href="{{ config('app.url') }}" class="btn">Kembali ke Dashboard</a>
            </div>

            <div style="color: #475569; font-size: 12px; font-style: italic; border-left: 3px solid #f59e0b; padding-left: 12px;">
                "Konfigurasi email adalah kunci komunikasi sistem yang handal."
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }} v2.0 &bull; Keamanan Terjamin &bull; Pesan Otomatis
        </div>
    </div>
</body>
</html>
