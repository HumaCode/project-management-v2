<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dokumen.css') }}">
        <style>
            .builder-wrap { display: flex; flex-direction: column; gap: 20px; }
            .builder-hd { 
                background: var(--bg-card); border: 1px solid var(--bd); border-radius: 16px; 
                padding: 20px; display: flex; align-items: center; justify-content: space-between;
                backdrop-filter: blur(10px);
            }
            .item-list { display: flex; flex-direction: column; gap: 15px; }
            .b-item { 
                background: var(--bg-card); border: 1px solid var(--bd); border-radius: 16px; 
                padding: 0; overflow: hidden; transition: all 0.3s ease;
                position: relative;
            }
            .b-item:hover { border-color: var(--cyan); box-shadow: 0 0 20px rgba(0, 200, 255, 0.1); }
            .item-hd { 
                padding: 12px 20px; background: rgba(255,255,255,0.03); 
                border-bottom: 1px solid var(--bd); display: flex; align-items: center; justify-content: space-between;
            }
            .item-type { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
            .item-type.text { color: var(--cyan); }
            .item-type.code { color: var(--warn); }
            
            .item-bd { padding: 20px; }
            .code-editor { height: 300px; border-radius: 8px; border: 1px solid var(--bd); overflow: hidden; }
            
            .add-actions { 
                display: flex; gap: 10px; justify-content: center; margin-top: 20px; padding: 20px;
                border: 2px dashed var(--bd); border-radius: 16px; transition: all 0.3s;
            }
            .add-actions:hover { border-color: var(--cyan); background: rgba(0,200,255,0.02); }
            .btn-add-item { 
                background: var(--bg-body); border: 1px solid var(--bd); color: var(--fg);
                padding: 10px 20px; border-radius: 10px; font-size: 14px; display: flex; align-items: center; gap: 8px;
                transition: 0.2s;
            }
            .btn-add-item:hover { border-color: var(--cyan); color: var(--cyan); transform: translateY(-2px); }
            
            .sticky-save {
                position: sticky; bottom: 20px; z-index: 100;
                display: flex; justify-content: center;
            }
            .btn-save-all {
                background: var(--cyan); color: #000; border: none; padding: 12px 40px; 
                border-radius: 30px; font-weight: 700; box-shadow: 0 10px 20px rgba(0,200,255,0.3);
                display: flex; align-items: center; gap: 10px; transition: 0.3s;
            }
            .btn-save-all:hover { transform: scale(1.05); filter: brightness(1.1); }
        </style>
    @endpush

    @push('js')
        <!-- Monaco Editor loader -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
        <script>
            require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
            
            window.editors = [];

            function createCodeEditor(containerId, initialValue = '', language = 'javascript') {
                require(['vs/editor/editor.main'], function() {
                    const editor = monaco.editor.create(document.getElementById(containerId), {
                        value: initialValue,
                        language: language,
                        theme: 'vs-dark',
                        automaticLayout: true,
                        fontSize: 14,
                        fontFamily: 'JetBrains Mono, Fira Code, monospace',
                        minimap: { enabled: false },
                        scrollBeyondLastLine: false,
                        padding: { top: 16, bottom: 16 }
                    });
                    window.editors.push({ id: containerId, editor: editor });
                });
            }

            $(function() {
                // Initialize existing code items
                $('.code-editor').each(function() {
                    const id = $(this).attr('id');
                    const val = $(this).data('content') || '';
                    const lang = $(this).data('lang') || 'javascript';
                    createCodeEditor(id, val, lang);
                });
            });

            function addNewItem(type) {
                const id = 'item-' + Date.now();
                let html = '';
                
                if(type === 'text') {
                    html = `
                        <div class="b-item" data-type="text" id="${id}">
                            <div class="item-hd">
                                <div class="item-type text"><i class="bi bi-text-left"></i> Paragraf Teks</div>
                                <button class="btn-mdel-sm" onclick="$('#${id}').remove()"><i class="bi bi-trash"></i></button>
                            </div>
                            <div class="item-bd">
                                <textarea class="fmta" style="height: 120px;" placeholder="Tulis penjelasan di sini..."></textarea>
                            </div>
                        </div>
                    `;
                    $('.item-list').append(html);
                } else {
                    const editorId = 'editor-' + Date.now();
                    html = `
                        <div class="b-item" data-type="code" id="${id}">
                            <div class="item-hd">
                                <div class="item-type code"><i class="bi bi-code-slash"></i> Snippet Kode</div>
                                <div style="display:flex; gap:10px; align-items:center;">
                                    <select class="nsel-sm" style="background:transparent; border:1px solid var(--bd); font-size:11px; color:var(--muted);">
                                        <option value="javascript">JavaScript</option>
                                        <option value="php">PHP</option>
                                        <option value="html">HTML</option>
                                        <option value="css">CSS</option>
                                        <option value="sql">SQL</option>
                                    </select>
                                    <button class="btn-mdel-sm" onclick="$('#${id}').remove()"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <div class="item-bd">
                                <div id="${editorId}" class="code-editor"></div>
                            </div>
                        </div>
                    `;
                    $('.item-list').append(html);
                    createCodeEditor(editorId, '// Tulis kode Anda di sini...');
                }
                
                // Scroll to bottom
                window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            }
        </script>
    @endpush

    <div class="builder-wrap">
        <!-- Header Info -->
        <div class="builder-hd">
            <div style="display:flex; align-items:center; gap:20px;">
                <a href="{{ route('dokumen.index') }}" class="ibtn ib-v" style="width:40px; height:40px;"><i class="bi bi-arrow-left"></i></a>
                <div>
                    <h4 style="margin:0; font-weight:700;">{{ $dokumen->nama }}</h4>
                    <div style="font-size:13px; color:var(--muted); display:flex; gap:15px; margin-top:4px;">
                        <span><i class="bi bi-kanban"></i> {{ $dokumen->project->name }}</span>
                        <span><i class="bi bi-tags"></i> {{ $dokumen->kategori_label }}</span>
                        <span><i class="bi bi-person"></i> {{ $dokumen->uploader->name }}</span>
                    </div>
                </div>
            </div>
            <div style="text-align:right">
                <span class="vbadge">Versi {{ $dokumen->versi }}</span>
            </div>
        </div>

        <!-- Content Items -->
        <div class="item-list">
            @forelse($dokumen->items as $item)
                <div class="b-item" data-type="{{ $item->type }}" id="item-{{ $item->id }}">
                    <div class="item-hd">
                        <div class="item-type {{ $item->type }}">
                            <i class="bi {{ $item->type == 'text' ? 'bi-text-left' : 'bi-code-slash' }}"></i>
                            {{ $item->type == 'text' ? 'Paragraf Teks' : 'Snippet Kode' }}
                        </div>
                        <button class="btn-mdel-sm" onclick="$('#item-{{ $item->id }}').remove()"><i class="bi bi-trash"></i></button>
                    </div>
                    <div class="item-bd">
                        @if($item->type == 'text')
                            <textarea class="fmta" style="height: 120px;">{{ $item->content }}</textarea>
                        @else
                            <div id="editor-{{ $item->id }}" class="code-editor" data-content="{{ $item->content }}" data-lang="javascript"></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state" style="background:var(--bg-card); border-radius:16px; padding:60px;">
                    <i class="bi bi-magic" style="font-size:48px; opacity:0.3;"></i>
                    <h5 style="margin-top:20px; opacity:0.6;">Belum ada konten di dokumen ini.</h5>
                    <p style="opacity:0.4;">Mulai susun dokumentasi Anda dengan menambahkan blok di bawah.</p>
                </div>
            @endforelse
        </div>

        <!-- Add Actions -->
        <div class="add-actions">
            <button class="btn-add-item" onclick="addNewItem('text')">
                <i class="bi bi-plus-circle-fill"></i> Tambah Teks Penjelasan
            </button>
            <button class="btn-add-item" onclick="addNewItem('code')">
                <i class="bi bi-plus-circle-fill"></i> Tambah Snippet Kode
            </button>
        </div>

        <!-- Sticky Save -->
        <div class="sticky-save">
            <button class="btn-save-all">
                <i class="bi bi-floppy-fill"></i> Simpan Dokumentasi
            </button>
        </div>
    </div>
</x-master-layout>
