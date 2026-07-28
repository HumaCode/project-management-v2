<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dokumen.css') }}">
        <style>
            .builder-wrap { display: flex; flex-direction: column; gap: 20px; }
            .builder-hd { 
                background: linear-gradient(135deg, rgba(2, 132, 199, 0.05) 0%, rgba(0, 200, 255, 0.02) 100%);
                border: 1px solid var(--bd, rgba(148, 163, 184, 0.2)); 
                border-top: 3px solid var(--cyan, #0284c7);
                border-radius: 16px; 
                padding: 20px 24px; 
                display: flex; 
                align-items: center; 
                justify-content: space-between;
                backdrop-filter: blur(12px);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
            }
            .document-paper-container {
                background: var(--bg-card, #ffffff);
                border: 1px solid var(--bd, rgba(148, 163, 184, 0.2));
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
            }
            /* Custom TinyMCE Wrapper */
            .tox-tinymce {
                border: none !important;
                border-radius: 16px !important;
            }
            .btn-save-all {
                background: #0284c7;
                color: #ffffff !important;
                border: none;
                padding: 9px 24px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s ease;
                box-shadow: 0 4px 14px rgba(2, 132, 199, 0.25);
                cursor: pointer;
            }
            .btn-save-all:hover {
                background: #0369a1;
                color: #ffffff !important;
                box-shadow: 0 6px 20px rgba(2, 132, 199, 0.35);
                transform: translateY(-2px);
            }
            html[data-theme="dark"] .btn-save-all {
                background: var(--cyan, #00c8ff);
                color: #050e1d !important;
                box-shadow: 0 4px 14px rgba(0, 200, 255, 0.2);
            }
            html[data-theme="dark"] .btn-save-all:hover {
                background: #1ad1ff;
                color: #050e1d !important;
                box-shadow: 0 6px 18px rgba(0, 200, 255, 0.35);
            }
        </style>
    @endpush

    @push('js')
        <!-- Official TinyMCE 7 CDN with User API Key -->
        <script src="https://cdn.tiny.cloud/1/re1hyyagcsptel9z6bg836dptpkbrbpua7kjc4rgae0ap8kj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            $(function() {
                const isDark = $('html').attr('data-theme') === 'dark';
                const dynamicHeight = Math.max(650, $(window).height() - 210);

                // Initialize TinyMCE 7 Full Suite Editor
                tinymce.init({
                    selector: '#documentEditor',
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount codesample accordion emoticons directionality',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table codesample accordion emoticons | removeformat fullscreen code preview',
                    menubar: 'file edit view insert format table tools help',
                    height: dynamicHeight,
                    branding: false,
                    promotion: false,
                    skin: isDark ? 'oxide-dark' : 'oxide',
                    content_css: isDark ? 'dark' : 'default',
                    content_style: 'body { font-family: Inter, system-ui, sans-serif; font-size: 15px; line-height: 1.8; padding: 25px 35px; color: ' + (isDark ? '#f8fafc' : '#0f172a') + '; } img { max-width: 100%; height: auto; border-radius: 10px; margin: 12px 0; } pre { background: #0f172a; color: #f8fafc; padding: 16px; border-radius: 10px; font-family: "JetBrains Mono", monospace; }',
                    images_upload_handler: function (blobInfo, progress) {
                        return new Promise(function (resolve, reject) {
                            const formData = new FormData();
                            formData.append('image', blobInfo.blob(), blobInfo.filename());
                            formData.append('_token', "{{ csrf_token() }}");

                            $.ajax({
                                url: "{{ route('dokumen.builder.upload', $dokumen->id) }}",
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    if (res.success) {
                                        resolve(res.data.url);
                                        SCA.toast({ type: "success", title: "Berhasil!", message: "Gambar berhasil disisipkan." });
                                    } else {
                                        reject(res.message || "Gagal mengunggah gambar");
                                    }
                                },
                                error: function(err) {
                                    reject(err.responseJSON?.message || "Gagal mengunggah gambar");
                                }
                            });
                        });
                    }
                });
            });

            $('.btn-save-all').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                const htmlContent = tinymce.get('documentEditor').getContent();

                const mediaIds = [];
                const regex = /\/(?:storage|media)\/[^"'\s>]+\/(\d+)\//gi;
                let match;
                while ((match = regex.exec(htmlContent)) !== null) {
                    if (match[1]) mediaIds.push(parseInt(match[1]));
                }

                const items = [
                    {
                        type: 'text',
                        content: htmlContent,
                        metadata: {
                            is_tinymce: true,
                            media_ids: mediaIds
                        }
                    }
                ];

                $btn.prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Menyimpan...');

                $.ajax({
                    url: "{{ route('dokumen.builder.save', $dokumen->id) }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        items: items
                    },
                    success: function(res) {
                        SCA.toast({
                            type: res.success ? "success" : "danger",
                            title: res.success ? "Berhasil!" : "Gagal!",
                            message: res.message ?? "Dokumentasi berhasil disimpan.",
                        });

                        if (res.success) {
                            setTimeout(() => {
                                window.location.href = "{{ route('dokumen.index') }}";
                            }, 1200);
                        }
                    },
                    error: function(err) {
                        SCA.toast({
                            type: "danger",
                            title: "Gagal!",
                            message: err.responseJSON?.message || "Terjadi kesalahan sistem.",
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        </script>
    @endpush

    @php
        $fixImageUrl = function ($url) {
            if (empty($url)) return '';
            $path = parse_url($url, PHP_URL_PATH);
            if ($path) {
                return asset(ltrim($path, '/'));
            }
            return asset(ltrim($url, '/'));
        };

        $initialHtml = '';
        if ($dokumen->items->count() > 0) {
            if ($dokumen->items->count() == 1 && ( ($dokumen->items->first()->metadata['is_tinymce'] ?? false) || ($dokumen->items->first()->metadata['is_wysiwyg'] ?? false) )) {
                $rawContent = $dokumen->items->first()->content;
                $initialHtml = preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function ($m) use ($fixImageUrl) {
                    $newSrc = $fixImageUrl($m[1]);
                    return str_replace($m[1], $newSrc, $m[0]);
                }, $rawContent);
            } else {
                foreach ($dokumen->items as $item) {
                    if ($item->type == 'text') {
                        $initialHtml .= $item->content;
                    } elseif ($item->type == 'code') {
                        $lang = e($item->metadata['language'] ?? 'javascript');
                        $initialHtml .= '<pre style="background:#0f172a; color:#f8fafc; padding:16px; border-radius:10px;"><code class="language-' . $lang . '">' . e($item->content) . '</code></pre>';
                    } elseif ($item->type == 'image') {
                        $caption = e($item->metadata['caption'] ?? '');
                        $imgSrc = $fixImageUrl($item->content);

                        if (empty($item->content) && !empty($item->metadata['media_id'])) {
                            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($item->metadata['media_id']);
                            if ($media) {
                                $imgSrc = asset(parse_url($media->getUrl(), PHP_URL_PATH));
                            }
                        }

                        if (!empty($imgSrc)) {
                            $initialHtml .= '<p style="text-align:center;"><img src="' . $imgSrc . '" class="img-fluid rounded shadow-sm"></p>';
                        }
                        if ($caption) {
                            $initialHtml .= '<p style="text-align:center;" class="text-muted small"><em>' . $caption . '</em></p>';
                        }
                    }
                }
            }
        }
    @endphp

    <div class="builder-wrap">
        <!-- Header Info -->
        <div class="builder-hd">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('dokumen.index') }}" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center p-0" style="width:42px; height:42px; border-color: var(--bd, rgba(148,163,184,0.3));" title="Kembali ke Daftar Dokumen">
                    <i class="bi bi-arrow-left" style="font-size: 16px;"></i>
                </a>
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <h4 class="fw-bold mb-0" style="font-size: 19px; letter-spacing: -0.3px;">{{ $dokumen->nama }}</h4>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                            <i class="bi bi-git me-1"></i>Versi {{ $dokumen->versi }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mt-2 flex-wrap" style="gap: 12px !important;">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 me-1" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-kanban me-1.5"></i>{{ $dokumen->project->name }}
                        </span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1.5 me-1" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-tags me-1.5"></i>{{ $dokumen->kategori_label }}
                        </span>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1.5" style="font-size: 12px; font-weight: 600;">
                            <i class="bi bi-person me-1.5"></i>{{ $dokumen->uploader->name }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-save-all">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Dokumentasi
                </button>
            </div>
        </div>

        <!-- Document Paper Canvas -->
        <div class="document-paper-container">
            <textarea id="documentEditor">{!! $initialHtml !!}</textarea>
        </div>
    </div>
</x-master-layout>
