<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #040d1a; color: #e2eaf4;
        }
        .wrapper {
            width: 100%; table-layout: fixed; background-color: #040d1a; padding: 40px 0;
        }
        .main {
            background-color: #071428; margin: 0 auto; width: 100%; max-width: 600px;
            border-spacing: 0; color: #e2eaf4; border-radius: 16px; border: 1px solid rgba(0, 200, 255, 0.1);
            overflow: hidden; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        }
        .header {
            background: linear-gradient(135deg, #0072c6, #00c8ff);
            padding: 30px; text-align: center;
        }
        .header h1 {
            margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 1px; font-weight: 800;
        }
        .content {
            padding: 40px 30px; line-height: 1.6;
        }
        .greeting {
            font-size: 20px; font-weight: 700; color: #f59e0b; margin-bottom: 20px;
        }
        .info-box {
            background-color: rgba(0, 200, 255, 0.05); border: 1px solid rgba(0, 200, 255, 0.15);
            border-radius: 12px; padding: 20px; margin: 25px 0;
        }
        .info-item {
            margin-bottom: 10px; font-size: 14px;
        }
        .info-label {
            color: #7a90a8; font-weight: 600; width: 100px; display: inline-block;
        }
        .info-value {
            color: #e2eaf4; font-weight: 500;
        }
        .bio-quote {
            border-left: 3px solid #f59e0b; padding-left: 15px; font-style: italic;
            color: #7a90a8; margin: 20px 0; font-size: 15px;
        }
        .footer {
            background-color: rgba(0, 0, 0, 0.2); padding: 20px; text-align: center;
            font-size: 12px; color: #3d5068; border-top: 1px solid rgba(0, 200, 255, 0.05);
        }
        .btn-wrap {
            text-align: center; margin-top: 30px;
        }
        .button {
            display: inline-block; padding: 14px 30px; background: linear-gradient(135deg, #0072c6, #00c8ff);
            color: #ffffff !important; text-decoration: none; border-radius: 8px;
            font-weight: 700; font-size: 14px; box-shadow: 0 4px 15px rgba(0, 200, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td class="header">
                    <h1>PROJECT MANAGEMENT SYSTEM</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="greeting">Halo Admin! 🔔</div>
                    <p>Seorang pengguna baru baru saja melengkapi profil mereka dan saat ini sedang menunggu verifikasi serta aktivasi akun dari Anda.</p>
                    
                    <div class="info-box">
                        <div class="info-item">
                            <span class="info-label">Nama</span>
                            <span class="info-value">: {{ $user->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">: {{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Username</span>
                            <span class="info-value">: {{ $user->username ?? '-' }}</span>
                        </div>
                    </div>

                    <p style="font-weight: 600; color: #f59e0b; margin-bottom: 5px;">Pesan / Keterangan:</p>
                    <div class="bio-quote">
                        "{{ $bio }}"
                    </div>

                    <div class="btn-wrap">
                        <a href="{{ $actionUrl }}" class="button">Lihat Detail & Aktifkan</a>
                    </div>

                    <p style="font-size: 12px; color: #3d5068; text-align: center; margin-top: 30px; border-top: 1px solid rgba(0, 200, 255, 0.05); padding-top: 20px;">
                        <strong>Catatan:</strong> Ini adalah notifikasi otomatis dari sistem. Mohon untuk tidak membalas email ini secara langsung.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; 2026 PMS v2.0 &bull; Divisi Pengembangan Sistem &bull; Keamanan Terjamin &bull; Email Otomatis
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
