<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f4f7f9;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #4a4a4a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-top: 40px;
        }
        .header {
            background-color: #040d1a;
            padding: 30px;
            text-align: center;
        }
        .header .logo {
            color: #00c8ff;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content h1 {
            font-size: 22px;
            color: #333333;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .content p {
            font-size: 16px;
            color: #666666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-container {
            background-color: #f0f9ff;
            border: 2px dashed #00c8ff;
            border-radius: 12px;
            padding: 24px;
            display: inline-block;
            margin-bottom: 30px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #0072c6;
            letter-spacing: 8px;
            margin: 0;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
        .expiry-note {
            font-size: 13px;
            color: #ff4d6d;
            font-weight: 600;
            margin-top: 10px;
        }
        @media screen and (max-width: 600px) {
            .main {
                border-radius: 0;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <div class="logo">PROJECT MANAGEMENT SYSTEM</div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>Kode Verifikasi Login</h1>
                    <p>Halo,<br>Gunakan kode OTP di bawah ini untuk masuk ke akun Anda secara aman. Kode ini hanya berlaku untuk sesi login ini.</p>
                    
                    <div class="otp-container">
                        <div class="otp-code">{{ $otp }}</div>
                    </div>

                    <div class="expiry-note">
                        ⚠️ Kode ini akan kedaluwarsa dalam 10 menit.
                    </div>

                    <p style="margin-top: 40px; font-size: 14px; color: #888;">
                        Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini atau hubungi tim dukungan kami jika Anda merasa ada aktivitas mencurigakan.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; 2025 PMS v2.0 - Secure Project Management Platform.<br>
                    Jakarta, Indonesia.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
