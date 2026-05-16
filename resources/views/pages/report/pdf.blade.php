<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* Pengaturan Margin Halaman Native */
        @page {
            margin: 2.5cm 2cm 2.5cm 2cm;
        }
        
        /* Margin 0 hanya jika ada cover */
        @if(isset($cover_image) && $cover_image)
        @page:first {
            margin: 0 !important;
        }
        @endif

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.5;
            text-align: left;
        }

        /* Halaman Cover */
        .cover-page {
            width: 100%;
            height: 100%;
            page-break-after: always;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }
        .cover-image {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Header (Statis) */
        .header-static {
            width: 100%;
            margin-bottom: 30px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #00c8ff;
            padding-bottom: 15px;
        }
        
        /* Footer (Fixed) */
        .footer {
            position: fixed;
            bottom: -1.5cm;
            left: 0;
            right: 0;
            height: 1.2cm;
        }
        .footer-table {
            width: 100%;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-size: 10px;
            color: #888;
        }

        /* Konten Utama */
        .content-wrapper {
            /* Margin diatur otomatis oleh @page */
        }

        .doc-item {
            margin-bottom: 45px;
            page-break-inside: avoid;
        }
        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #0088cc;
            border-left: 6px solid #00c8ff;
            padding-left: 15px;
            margin-bottom: 15px;
        }

        .text-content {
            font-size: 13px;
            text-align: justify;
            margin-bottom: 15px;
            color: #444;
        }
        
        .image-content {
            text-align: center;
            margin: 25px 0;
        }
        .image-content img {
            max-width: 100%;
            max-height: 380px; /* Diturunkan sedikit lagi agar lebih pas */
            width: auto;
            height: auto;
            border-radius: 8px;
            border: 1px solid #e0e0e0; 
            display: inline-block;
        }

        /* PREMIUM CODE WINDOW STYLE */
        .code-window {
            margin: 20px 0;
            background: #1e1e1e;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid #333;
        }
        .code-window-header {
            background: #323232;
            padding: 10px 15px;
            display: block;
            border-bottom: 1px solid #444;
        }
        .dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }
        .code-title {
            float: right;
            color: #888;
            font-size: 10px;
            font-family: Arial, sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
        }
        .code-body {
            padding: 15px 20px;
            margin: 0;
            color: #d4d4d4;
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            background: #1e1e1e;
        }
        
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    @if(isset($cover_image) && $cover_image)
        <div class="cover-page">
            <img src="{{ $cover_image }}" class="cover-image">
        </div>
    @endif

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="text-align: left; text-transform: uppercase;">Laporan Resmi &bull; {{ $project->name ?? 'PROJECT' }}</td>
                <td style="text-align: right; font-weight: bold;">HALAMAN <span class="page-number"></span></td>
            </tr>
        </table>
    </div>

    <div class="content-wrapper">
        @if(!isset($cover_image) || !$cover_image)
            <div class="header-static">
                <table class="header-table">
                    <tr>
                        <td style="text-align: center;">
                            <h1 style="margin:0; font-size: 20px; text-transform: uppercase; color: #071528;">{{ $title }}</h1>
                            <div style="font-size: 11px; color: #999; margin-top: 5px;">{{ $project->name ?? '-' }} &bull; {{ $date }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        @foreach($documents as $index => $doc)
            <div class="doc-item">
                <div class="doc-title">{{ ($index + 1) . '. ' . $doc->nama }}</div>
                
                <div class="doc-body">
                    @if($doc->type === 'file')
                        @if($doc->is_image && isset($doc->file_path))
                            <div class="image-content"><img src="{{ $doc->file_path }}"></div>
                        @endif
                        @if($doc->custom_description)
                            <div class="text-content">{!! nl2br(e($doc->custom_description)) !!}</div>
                        @endif
                    @else
                        @foreach($doc->items as $item)
                            @if($item->type === 'text' || $item->type === 'paragraph')
                                <div class="text-content">{!! nl2br(e($item->content)) !!}</div>
                            @elseif($item->type === 'image' && isset($item->file_path))
                                <div class="image-content"><img src="{{ $item->file_path }}"></div>
                            @elseif($item->type === 'code')
                                <div class="code-window">
                                    <div class="code-window-header">
                                        <span class="dot dot-red"></span>
                                        <span class="dot dot-yellow"></span>
                                        <span class="dot dot-green"></span>
                                        <span class="code-title">Source Code</span>
                                    </div>
                                    <pre class="code-body">{{ $item->content }}</pre>
                                </div>
                            @elseif($item->type === 'heading')
                                <div style="font-size: 15px; font-weight: bold; margin: 20px 0 10px 0; color: #333;">{{ $item->content }}</div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
