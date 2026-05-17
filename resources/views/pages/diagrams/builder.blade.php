<x-master-layout>
    @section('title', 'Builder Diagram - ' . $diagram->name)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/diagram.css') }}">
        <style>
            .builder-container {
                display: flex;
                height: calc(100vh - 140px);
                min-height: 600px;
                gap: 20px;
            }
            .builder-sidebar {
                width: 350px;
                background: var(--card, #1e293b);
                border: 1px solid var(--bd, #334155);
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }
            .builder-sidebar-header {
                padding: 15px 20px;
                border-bottom: 1px solid var(--bd, #334155);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(255, 255, 255, 0.02);
            }
            .builder-sidebar-body {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }
            .builder-canvas {
                flex: 1;
                background: var(--card, #1e293b);
                border: 1px solid var(--bd, #334155);
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                position: relative;
            }
            .canvas-header {
                padding: 15px 20px;
                border-bottom: 1px solid var(--bd, #334155);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(255, 255, 255, 0.02);
            }
            .canvas-body {
                flex: 1;
                overflow: auto;
                background: #0f172a; /* Darker bg for canvas */
                display: grid;
                place-items: center;
                padding: 20px;
                position: relative;
            }
            
            /* Dot Grid Background for Canvas */
            .canvas-bg {
                background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                background-size: 20px 20px;
            }

            .mermaid-wrapper {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: transform 0.2s ease-out;
                transform-origin: center center;
            }

            .node-card {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid var(--bd, #334155);
                border-radius: 8px;
                padding: 12px;
                margin-bottom: 12px;
                position: relative;
            }
            
            .node-card .btn-remove {
                position: absolute;
                top: 8px;
                right: 8px;
                padding: 2px 6px;
                font-size: 12px;
            }

            /* Overrides for inputs in sidebar */
            .builder-sidebar .form-control, 
            .builder-sidebar .form-select {
                background-color: rgba(255, 255, 255, 0.05);
                border: 1px solid var(--bd, #334155);
                color: #ffffff !important;
                color-scheme: dark;
            }
            .builder-sidebar .form-select option {
                background-color: #1e293b;
                color: #ffffff;
            }
            .builder-sidebar .form-control:focus, 
            .builder-sidebar .form-select:focus {
                border-color: var(--cyan, #00c8ff);
                box-shadow: 0 0 0 3px rgba(0, 200, 255, 0.1);
            }

            /* Zoom Controls */
            .zoom-controls {
                position: absolute;
                bottom: 20px;
                right: 20px;
                background: var(--card, #1e293b);
                border: 1px solid var(--bd, #334155);
                border-radius: 8px;
                display: flex;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                z-index: 10;
            }
            .zoom-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: transparent;
                border: none;
                color: var(--white);
                border-right: 1px solid var(--bd, #334155);
                cursor: pointer;
            }
            .zoom-btn:last-child { border-right: none; }
            .zoom-btn:hover { background: rgba(255,255,255,0.05); color: var(--cyan); }
            
            /* Dashed Add Button */
            .btn-dashed {
                border: 1px dashed var(--bd, #334155);
                background: rgba(255, 255, 255, 0.02);
                color: var(--muted, #94a3b8);
                width: 100%;
                padding: 10px;
                border-radius: 8px;
                transition: 0.2s;
                font-size: 13px;
                font-weight: 500;
            }
            .btn-dashed:hover {
                border-color: var(--cyan, #00c8ff);
                color: var(--cyan, #00c8ff);
                background: rgba(0, 200, 255, 0.05);
            }

            /* Responsive */
            @media(max-width: 991px) {
                .builder-container { flex-direction: column; height: auto; }
                .builder-sidebar { width: 100%; height: 50vh; }
                .builder-canvas { height: 60vh; }
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/mermaid@10.6.1/dist/mermaid.min.js"></script>
    @endpush

    <!-- Header (Simple) -->
    <div class="pg-hd mb-3">
        <div class="pg-hd-left">
            <a href="{{ route('diagrams.index') }}" class="btn btn-outline-secondary btn-sm me-2 text-white">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="pg-title">{{ $diagram->name }}</div>
                <div class="pg-sub text-uppercase text-info" style="font-size: 11px; letter-spacing: 1px;">Builder {{ $diagram->type }}</div>
            </div>
        </div>
        <div class="pg-actions">
            <span id="saveStatus" class="me-3 text-muted" style="font-size:12px; display:none;"><i class="bi bi-check-circle"></i> Tersimpan</span>
        </div>
    </div>

    <!-- Alpine Builder App -->
    <div x-data="diagramBuilder()" class="builder-container" wire:ignore>
        
        <!-- Sidebar Form -->
        <div class="builder-sidebar" data-bs-theme="dark">
            <div class="builder-sidebar-header">
                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-sliders me-2"></i> Konfigurasi</h6>
                <button type="button" class="btn btn-primary btn-sm" @click="saveDiagram" :disabled="isSaving">
                    <span x-show="!isSaving"><i class="bi bi-save me-1"></i> Simpan</span>
                    <span x-show="isSaving"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
            </div>
            <div class="builder-sidebar-body">
                
                <!-- FLOWCHART BUILDER -->
                <template x-if="type === 'flowchart'">
                    <div>
                        <div class="mb-3">
                            <label class="form-label text-white fw-semibold" style="font-size: 13px;">Arah Diagram</label>
                            <select class="form-select form-select-sm" x-model="diagramData.direction" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                <option value="TD" style="background-color: #1e293b; color: white;">Atas ke Bawah (Top-Down)</option>
                                <option value="LR" style="background-color: #1e293b; color: white;">Kiri ke Kanan (Left-Right)</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                            <label class="form-label text-white fw-semibold mb-0" style="font-size: 13px;">Daftar Node (Langkah)</label>
                            <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" @click="addNode" style="font-size: 12px;"><i class="bi bi-plus"></i> Tambah</button>
                        </div>
                        
                        <template x-for="(node, index) in diagramData.nodes" :key="index">
                            <div class="node-card">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeNode(index)"><i class="bi bi-trash"></i></button>
                                
                                <div class="mb-2">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">ID Node (Unik tanpa spasi)</label>
                                    <input type="text" class="form-control form-control-sm" x-model="node.id" @input.debounce.500ms="generateMermaid" placeholder="Contoh: A">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Label / Teks</label>
                                    <input type="text" class="form-control form-control-sm" x-model="node.label" @input.debounce.500ms="generateMermaid" placeholder="Mulai...">
                                </div>
                                <div>
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Bentuk</label>
                                    <select class="form-select form-select-sm" x-model="node.shape" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                        <option value="pill" style="background-color: #1e293b; color: white;">Pil (Mulai/Selesai)</option>
                                        <option value="square" style="background-color: #1e293b; color: white;">Kotak Persegi (Proses)</option>
                                        <option value="round" style="background-color: #1e293b; color: white;">Kotak Tumpul (Proses Alternatif)</option>
                                        <option value="diamond" style="background-color: #1e293b; color: white;">Ketupat (Keputusan)</option>
                                        <option value="parallelogram" style="background-color: #1e293b; color: white;">Jajar Genjang (Input / Output)</option>
                                        <option value="database" style="background-color: #1e293b; color: white;">Tabung (Database / Penyimpanan)</option>
                                        <option value="subroutine" style="background-color: #1e293b; color: white;">Kotak Garis Ganda (Sub-proses)</option>
                                        <option value="hexagon" style="background-color: #1e293b; color: white;">Segi Enam (Persiapan)</option>
                                        <option value="circle" style="background-color: #1e293b; color: white;">Lingkaran (Konektor)</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Tambah Node di Bawah -->
                        <button type="button" class="btn btn-dashed mb-4 mt-1" @click="addNode">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Node
                        </button>

                        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                            <label class="form-label text-white fw-semibold mb-0" style="font-size: 13px;">Sambungan (Garis)</label>
                        </div>
                        
                        <template x-for="(link, index) in diagramData.links" :key="index">
                            <div class="node-card">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeLink(index)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Dari Node</label>
                                        <select class="form-select form-select-sm" x-model="link.from" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                            <option value="" style="background-color: #1e293b; color: white;">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.from == n.id" style="background-color: #1e293b; color: white;"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Ke Node</label>
                                        <select class="form-select form-select-sm" x-model="link.to" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                            <option value="" style="background-color: #1e293b; color: white;">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.to == n.id" style="background-color: #1e293b; color: white;"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Teks Garis (Opsional)</label>
                                    <input type="text" class="form-control form-control-sm" x-model="link.text" @input.debounce.500ms="generateMermaid" placeholder="Ya / Tidak">
                                </div>
                            </div>
                        </template>
                        
                        <!-- Tambah Garis di Bawah -->
                        <button type="button" class="btn btn-dashed mb-2 mt-1" @click="addLink">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Sambungan
                        </button>
                    </div>
                </template>
                
                <!-- ERD BUILDER -->
                <template x-if="type === 'erd'">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                            <label class="form-label text-white fw-semibold mb-0" style="font-size: 13px;">Daftar Tabel (Entitas)</label>
                            <button type="button" class="btn btn-outline-success btn-sm py-0 px-2" @click="addEntity" style="font-size: 12px;"><i class="bi bi-plus"></i> Tambah Tabel</button>
                        </div>
                        
                        <template x-for="(entity, eIndex) in diagramData.entities" :key="'e'+eIndex">
                            <div class="node-card mb-3" style="border-left-color: #10b981;">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeEntity(eIndex)"><i class="bi bi-trash"></i></button>
                                
                                <div class="mb-2">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Nama Tabel (Tanpa Spasi)</label>
                                    <input type="text" class="form-control form-control-sm" x-model="entity.name" @input.debounce.500ms="generateMermaid" placeholder="Contoh: USERS">
                                </div>
                                
                                <div class="mt-3 mb-1 d-flex justify-content-between align-items-center">
                                    <label class="form-label text-light mb-0" style="font-size: 11px;">Kolom / Atribut</label>
                                    <button type="button" class="btn btn-link text-info p-0" @click="addAttribute(eIndex)" style="font-size: 11px; text-decoration: none;"><i class="bi bi-plus-circle"></i> Tambah Kolom</button>
                                </div>
                                
                                <div class="border rounded p-2" style="border-color: #334155 !important; background-color: #0f172a;">
                                    <template x-for="(attr, aIndex) in entity.attributes" :key="'a'+aIndex">
                                        <div class="row g-1 mb-2 align-items-center">
                                            <div class="col-4">
                                                <input type="text" class="form-control form-control-sm" x-model="attr.name" @input.debounce.500ms="generateMermaid" placeholder="Nama" style="font-size: 10px;">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" class="form-control form-control-sm" x-model="attr.type" @input.debounce.500ms="generateMermaid" placeholder="Tipe" style="font-size: 10px;">
                                            </div>
                                            <div class="col-3 d-flex justify-content-around">
                                                <div class="form-check form-check-inline m-0" title="Primary Key">
                                                    <input class="form-check-input" type="checkbox" x-model="attr.pk" @change="generateMermaid" style="transform: scale(0.8);">
                                                    <label class="form-check-label text-warning" style="font-size: 10px;">PK</label>
                                                </div>
                                                <div class="form-check form-check-inline m-0" title="Foreign Key">
                                                    <input class="form-check-input" type="checkbox" x-model="attr.fk" @change="generateMermaid" style="transform: scale(0.8);">
                                                    <label class="form-check-label text-info" style="font-size: 10px;">FK</label>
                                                </div>
                                            </div>
                                            <div class="col-1 text-end">
                                                <button type="button" class="btn btn-sm text-danger p-0" @click="removeAttribute(eIndex, aIndex)"><i class="bi bi-x"></i></button>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="!entity.attributes || entity.attributes.length === 0" class="text-center text-muted" style="font-size: 10px;">Belum ada kolom</div>
                                </div>
                            </div>
                        </template>

                        <!-- Tambah Tabel di Bawah -->
                        <button type="button" class="btn btn-dashed mb-4 mt-1" @click="addEntity">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Tabel
                        </button>

                        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                            <label class="form-label text-white fw-semibold mb-0" style="font-size: 13px;">Relasi Antar Tabel</label>
                            <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" @click="addRelationship" style="font-size: 12px;"><i class="bi bi-plus"></i> Tambah Relasi</button>
                        </div>
                        
                        <template x-for="(rel, rIndex) in diagramData.relationships" :key="'r'+rIndex">
                            <div class="node-card mb-2" style="border-left-color: #3b82f6;">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeRelationship(rIndex)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Tabel 1</label>
                                        <select class="form-select form-select-sm" x-model="rel.from" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                            <option value="" style="background-color: #1e293b; color: white;">Pilih...</option>
                                            <template x-for="e in diagramData.entities" :key="e.name">
                                                <option :value="e.name" x-text="e.name" :selected="rel.from == e.name" style="background-color: #1e293b; color: white;"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Tabel 2</label>
                                        <select class="form-select form-select-sm" x-model="rel.to" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                            <option value="" style="background-color: #1e293b; color: white;">Pilih...</option>
                                            <template x-for="e in diagramData.entities" :key="e.name">
                                                <option :value="e.name" x-text="e.name" :selected="rel.to == e.name" style="background-color: #1e293b; color: white;"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Tipe Relasi (Kardinalitas)</label>
                                    <select class="form-select form-select-sm" x-model="rel.type" @change="generateMermaid" style="background-color: #1e293b; color: white;">
                                        <option value="||--o{" style="background-color: #1e293b; color: white;">1 ke Banyak (1 to Many)</option>
                                        <option value="||--||" style="background-color: #1e293b; color: white;">1 ke 1 (1 to 1)</option>
                                        <option value="}o--o{" style="background-color: #1e293b; color: white;">Banyak ke Banyak (M to M)</option>
                                        <option value="|o--o{" style="background-color: #1e293b; color: white;">0/1 ke Banyak (0/1 to M)</option>
                                    </select>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Label Garis (Opsional)</label>
                                    <input type="text" class="form-control form-control-sm" x-model="rel.label" @input.debounce.500ms="generateMermaid" placeholder="contoh: memiliki">
                                </div>
                            </div>
                        </template>
                        
                        <!-- Tambah Relasi di Bawah -->
                        <button type="button" class="btn btn-dashed mb-2 mt-1" @click="addRelationship">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Relasi
                        </button>
                    </div>
                </template>

                <!-- Advanced Syntax Mode -->
                <div class="mt-4 pt-3 border-top border-secondary">
                    <label class="form-label text-white fw-semibold" style="font-size: 13px;">
                        Kode Mermaid
                        <i class="bi bi-info-circle text-muted ms-1" title="Otomatis diisi dari pengaturan di atas. Anda juga bisa mengedit langsung."></i>
                    </label>
                    <textarea class="form-control" rows="8" x-model="mermaidSyntax" @input.debounce.500ms="renderMermaid" style="font-family: monospace; font-size: 12px; background: #0b1120; border-color: #334155; color: #a78bfa !important;"></textarea>
                </div>
            </div>
        </div>

        <!-- Canvas Preview -->
        <div class="builder-canvas">
            <div class="canvas-header">
                <h6 class="mb-0 fw-bold text-white"><i class="bi bi-eye me-2"></i> Pratinjau Diagram</h6>
                <div>
                    <button class="btn btn-outline-info btn-sm px-3 border-0" @click="exportPNG" title="Download Gambar (PNG)">
                        <i class="bi bi-download me-1"></i> Ekspor PNG
                    </button>
                </div>
            </div>
            <div class="canvas-body canvas-bg" id="canvasContainer" 
                @wheel.prevent="handleWheel"
                @mousedown="startPan" 
                @mousemove="doPan" 
                @mouseup="endPan" 
                @mouseleave="endPan"
                :style="`background-position: ${panX}px ${panY}px; cursor: ${isDragging ? 'grabbing' : 'grab'};`">
                
                <div class="mermaid-wrapper" :style="`transform: translate(${panX}px, ${panY}px) scale(${zoom})`" id="mermaidContainer">
                    <!-- Mermaid SVG akan dirender di sini -->
                </div>
                
                <!-- Zoom Controls -->
                <div class="zoom-controls">
                    <button class="zoom-btn" @click="zoomOut" title="Zoom Out"><i class="bi bi-dash-lg"></i></button>
                    <button class="zoom-btn" @click="resetZoom" title="Reset Posisi & Zoom" style="font-size: 11px; width:45px; font-weight:bold" x-text="Math.round(zoom * 100) + '%'"></button>
                    <button class="zoom-btn" @click="zoomIn" title="Zoom In"><i class="bi bi-plus-lg"></i></button>
                </div>
                
                <div x-show="renderError" class="position-absolute bottom-0 start-0 m-3 w-75">
                    <div class="alert alert-danger bg-danger text-white border-0" style="font-size: 12px; padding: 8px 12px;" x-text="renderErrorMsg"></div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('diagramBuilder', () => ({
                id: '{{ $diagram->id }}',
                type: '{{ $diagram->type }}',
                diagramData: {
                    direction: 'TD',
                    nodes: [],
                    links: []
                },
                mermaidSyntax: '',
                isSaving: false,
                renderError: false,
                renderErrorMsg: '',
                zoom: 1,
                
                // Pan state
                panX: 0,
                panY: 0,
                isDragging: false,
                startX: 0,
                startY: 0,
                
                init() {
                    // Coba muat konten sebelumnya
                    let savedContent = @json($diagram->content);
                    let savedSyntax = @json($diagram->mermaid_syntax);
                    
                    if (savedContent && typeof savedContent === 'object') {
                        this.diagramData = Object.assign(this.diagramData, savedContent);
                    } else if (this.type === 'flowchart' && this.diagramData.nodes.length === 0) {
                        // Inisialisasi default flowchart jika kosong
                        this.diagramData.nodes.push(
                            { id: 'A', label: 'Mulai', shape: 'pill' },
                            { id: 'B', label: 'Proses 1', shape: 'round' }
                        );
                        this.diagramData.links.push(
                            { from: 'A', to: 'B', text: '' }
                        );
                    } else if (this.type === 'erd' && !this.diagramData.entities) {
                        // Inisialisasi default ERD jika kosong
                        this.diagramData.entities = [
                            {
                                name: "USERS",
                                attributes: [
                                    { name: "id", type: "int", pk: true, fk: false },
                                    { name: "name", type: "varchar", pk: false, fk: false }
                                ]
                            },
                            {
                                name: "POSTS",
                                attributes: [
                                    { name: "id", type: "int", pk: true, fk: false },
                                    { name: "user_id", type: "int", pk: false, fk: true },
                                    { name: "title", type: "varchar", pk: false, fk: false }
                                ]
                            }
                        ];
                        this.diagramData.relationships = [
                            { from: "USERS", to: "POSTS", type: "||--o{", label: "has" }
                        ];
                    }
                    
                    if (savedSyntax) {
                        this.mermaidSyntax = savedSyntax;
                    } else {
                        this.generateMermaid();
                    }
                    
                    // Inisialisasi mermaid
                    mermaid.initialize({
                        startOnLoad: false,
                        theme: 'dark',
                        themeVariables: {
                            fontFamily: 'Inter, sans-serif',
                            primaryColor: '#1e293b',
                            primaryBorderColor: '#3b82f6', // Biru terang untuk border
                            primaryTextColor: '#ffffff',
                            lineColor: '#64748b',
                            textColor: '#ffffff',
                            mainBkg: '#1e293b',
                            secondaryColor: '#0f172a', // Background baris tabel ERD
                            tertiaryColor: '#1e293b', // Background baris tabel ERD selang-seling
                            nodeBorder: '#3b82f6',
                            clusterBkg: '#1e293b',
                            clusterBorder: '#334155',
                            defaultLinkColor: '#94a3b8',
                            labelBoxBkgColor: '#0f172a', // Background teks label sama dgn kanvas
                            edgeLabelBackground: '#0f172a',
                            labelBoxBorderColor: 'transparent', // Hilangkan border di label teks
                        },
                        securityLevel: 'loose',
                        flowchart: { 
                            nodeSpacing: 70,
                            rankSpacing: 70,
                            htmlLabels: true
                        }
                    });
                    
                    setTimeout(() => {
                        this.renderMermaid();
                    }, 100);
                },

                // Flowchart Builders
                addNode() {
                    const id = 'N' + (this.diagramData.nodes.length + 1) + Math.random().toString(36).substr(2, 4).toUpperCase();
                    this.diagramData.nodes.push({ id: id, label: 'Node Baru', shape: 'round' });
                    this.generateMermaid();
                },
                
                removeNode(index) {
                    const node = this.diagramData.nodes[index];
                    this.diagramData.nodes.splice(index, 1);
                    // Hapus link yang terhubung
                    this.diagramData.links = this.diagramData.links.filter(l => l.from !== node.id && l.to !== node.id);
                    this.generateMermaid();
                },
                
                addLink() {
                    this.diagramData.links.push({ from: '', to: '', text: '' });
                },
                
                removeLink(index) {
                    this.diagramData.links.splice(index, 1);
                    this.generateMermaid();
                },

                // ERD Builders
                addEntity() {
                    const newName = 'TABLE_' + (this.diagramData.entities.length + 1);
                    this.diagramData.entities.push({ name: newName, attributes: [{ name: "id", type: "int", pk: true, fk: false }] });
                    this.generateMermaid();
                },
                
                removeEntity(index) {
                    const entity = this.diagramData.entities[index];
                    this.diagramData.entities.splice(index, 1);
                    // Hapus relasi terkait
                    this.diagramData.relationships = this.diagramData.relationships.filter(r => r.from !== entity.name && r.to !== entity.name);
                    this.generateMermaid();
                },
                
                addAttribute(eIndex) {
                    if(!this.diagramData.entities[eIndex].attributes) {
                        this.diagramData.entities[eIndex].attributes = [];
                    }
                    this.diagramData.entities[eIndex].attributes.push({ name: "col_name", type: "varchar", pk: false, fk: false });
                    this.generateMermaid();
                },
                
                removeAttribute(eIndex, aIndex) {
                    this.diagramData.entities[eIndex].attributes.splice(aIndex, 1);
                    this.generateMermaid();
                },
                
                addRelationship() {
                    this.diagramData.relationships.push({ from: '', to: '', type: '||--o{', label: '' });
                },
                
                removeRelationship(index) {
                    this.diagramData.relationships.splice(index, 1);
                    this.generateMermaid();
                },

                // Generate Syntax
                generateMermaid() {
                    if (this.type === 'flowchart') {
                        let code = `%%{init: {'flowchart': {'curve': 'basis'}}}%%\nflowchart ${this.diagramData.direction}\n`;
                        
                        // Define Colors (Premium Look)
                        code += `    classDef default fill:#1e293b,stroke:#00c8ff,stroke-width:2px,color:#fff,rx:8,ry:8;\n`;
                        code += `    classDef decision fill:#1e293b,stroke:#a855f7,stroke-width:2px,color:#fff;\n`; // Ungu untuk Keputusan
                        code += `    classDef square fill:#1e293b,stroke:#f59e0b,stroke-width:2px,color:#fff;\n`; // Oranye untuk Proses
                        code += `    classDef pill fill:#10b981,stroke:#059669,stroke-width:2px,color:#fff;\n`; // Hijau untuk Mulai/Selesai
                        code += `    classDef io fill:#1e293b,stroke:#ec4899,stroke-width:2px,color:#fff;\n`; // Pink untuk Input/Output
                        code += `    classDef database fill:#1e293b,stroke:#eab308,stroke-width:2px,color:#fff;\n`; // Kuning untuk Database
                        code += `    classDef subroutine fill:#1e293b,stroke:#3b82f6,stroke-width:2px,color:#fff;\n`; // Biru untuk Sub-proses
                        code += `    classDef circle fill:#1e293b,stroke:#f43f5e,stroke-width:2px,color:#fff;\n`; // Merah Mawar untuk Konektor
                        code += `    classDef hexagon fill:#1e293b,stroke:#14b8a6,stroke-width:2px,color:#fff;\n\n`; // Teal untuk Persiapan

                        // Nodes
                        if (this.diagramData.nodes && this.diagramData.nodes.length > 0) {
                            this.diagramData.nodes.forEach(n => {
                                let shapeStart = '(', shapeEnd = ')';
                                let className = 'default';
                                
                                if(n.shape === 'diamond') { shapeStart = '{'; shapeEnd = '}'; className = 'decision'; }
                                else if(n.shape === 'square') { shapeStart = '['; shapeEnd = ']'; className = 'square'; }
                                else if(n.shape === 'pill') { shapeStart = '(['; shapeEnd = '])'; className = 'pill'; }
                                else if(n.shape === 'parallelogram') { shapeStart = '[/'; shapeEnd = '/]'; className = 'io'; }
                                else if(n.shape === 'database') { shapeStart = '[('; shapeEnd = ')]'; className = 'database'; }
                                else if(n.shape === 'subroutine') { shapeStart = '[['; shapeEnd = ']]'; className = 'subroutine'; }
                                else if(n.shape === 'circle') { shapeStart = '(('; shapeEnd = '))'; className = 'circle'; }
                                else if(n.shape === 'hexagon') { shapeStart = '{{'; shapeEnd = '}}'; className = 'hexagon'; }
                                else { shapeStart = '('; shapeEnd = ')'; className = 'default'; }
                                
                                // Pastikan label tidak kosong, jika kosong beri spasi
                                let label = n.label ? n.label : ' ';
                                code += `    ${n.id}${shapeStart}"${label}"${shapeEnd}:::${className}\n`;
                            });
                        }
                        
                        code += `\n`;
                        
                        // Links
                        if (this.diagramData.links && this.diagramData.links.length > 0) {
                            this.diagramData.links.forEach(l => {
                                if (l.from && l.to) {
                                    if (l.text) {
                                        code += `    ${l.from} -->|"${l.text}"| ${l.to}\n`;
                                    } else {
                                        code += `    ${l.from} --> ${l.to}\n`;
                                    }
                                }
                            });
                        }
                        
                        this.mermaidSyntax = code;
                        this.renderMermaid();
                    } else if (this.type === 'erd') {
                        let code = "erDiagram\n";
                        
                        // Render Entities
                        if (this.diagramData.entities && this.diagramData.entities.length > 0) {
                            this.diagramData.entities.forEach(e => {
                                let eName = e.name ? e.name.replace(/\s+/g, '_') : 'UNTITLED';
                                code += `    ${eName} {\n`;
                                if (e.attributes && e.attributes.length > 0) {
                                    e.attributes.forEach(a => {
                                        let keys = [];
                                        if (a.pk) keys.push("PK");
                                        if (a.fk) keys.push("FK");
                                        let keyStr = keys.length > 0 ? ` ${keys.join(",")}` : "";
                                        
                                        let attrType = a.type ? a.type.replace(/\s+/g, '_') : 'string';
                                        let attrName = a.name ? a.name.replace(/\s+/g, '_') : 'column';
                                        
                                        code += `        ${attrType} ${attrName}${keyStr}\n`;
                                    });
                                }
                                code += `    }\n`;
                            });
                        }
                        
                        // Render Relationships
                        if (this.diagramData.relationships && this.diagramData.relationships.length > 0) {
                            this.diagramData.relationships.forEach(r => {
                                if (r.from && r.to && r.type) {
                                    let label = r.label ? ` : "${r.label}"` : ' : ""';
                                    code += `    ${r.from} ${r.type} ${r.to}${label}\n`;
                                }
                            });
                        }
                        
                        this.mermaidSyntax = code;
                        this.renderMermaid();
                    }
                },

                // Render Canvas
                async renderMermaid() {
                    this.renderError = false;
                    const container = document.getElementById('mermaidContainer');
                    
                    if (!this.mermaidSyntax || !this.mermaidSyntax.trim()) {
                        container.innerHTML = '<div class="text-muted"><i class="bi bi-diagram-3 me-2"></i> Kanvas Kosong</div>';
                        return;
                    }
                    
                    try {
                        // Gunakan ID unik setiap kali render untuk mencegah konflik duplicate ID di Mermaid v10+
                        const uniqueId = 'mermaid-' + Date.now();
                        const { svg } = await mermaid.render(uniqueId, this.mermaidSyntax);
                        container.innerHTML = svg;
                        
                        // Tweak SVG width to be responsive
                        const svgEl = container.querySelector('svg');
                        if(svgEl) {
                            svgEl.style.maxWidth = '100%';
                            svgEl.style.height = 'auto';
                        }
                    } catch (error) {
                        console.error('Mermaid Render Error:', error);
                        this.renderError = true;
                        this.renderErrorMsg = error.message || 'Terjadi kesalahan sintaks pada diagram. Periksa kembali ID atau Garis Anda.';
                    }
                },

                // Zoom controls
                zoomIn() {
                    if(this.zoom < 3) this.zoom += 0.1;
                },
                zoomOut() {
                    if(this.zoom > 0.3) this.zoom -= 0.1;
                },
                resetZoom() {
                    this.zoom = 1;
                    this.panX = 0;
                    this.panY = 0;
                },
                handleWheel(e) {
                    if (e.ctrlKey) {
                        // Zoom with Ctrl + Scroll
                        if (e.deltaY < 0) {
                            this.zoomIn();
                        } else {
                            this.zoomOut();
                        }
                    } else {
                        // Pan with normal Scroll
                        this.panY -= e.deltaY;
                        this.panX -= e.deltaX;
                    }
                },

                // Drag to Pan controls
                startPan(e) {
                    // Only start pan on left click
                    if(e.button !== 0) return; 
                    this.isDragging = true;
                    this.startX = e.clientX - this.panX;
                    this.startY = e.clientY - this.panY;
                },
                doPan(e) {
                    if (!this.isDragging) return;
                    this.panX = e.clientX - this.startX;
                    this.panY = e.clientY - this.startY;
                },
                endPan() {
                    this.isDragging = false;
                },

                // Save to DB
                saveDiagram() {
                    this.isSaving = true;
                    
                    let csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
                    
                    axios.put('/diagrams/' + this.id, {
                        content: this.diagramData,
                        mermaid_syntax: this.mermaidSyntax
                    }, {
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(res => {
                        if(res.data.status === 'success') {
                            // Tampilkan notifikasi Toast bawaan sistem
                            SCA.toast({
                                type: "success",
                                title: "Berhasil!",
                                message: res.data.message ?? "Diagram berhasil disimpan.",
                            });
                            
                            // Animasi tombol
                            const statusEl = document.getElementById('saveStatus');
                            if(statusEl) {
                                statusEl.style.display = 'inline-block';
                                statusEl.style.color = '#10b981';
                                setTimeout(() => { statusEl.style.display = 'none'; }, 3000);
                            }
                        } else {
                            SCA.toast({
                                type: "danger",
                                title: "Gagal!",
                                message: res.data.message ?? "Terjadi kesalahan sistem.",
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        SCA.toast({
                            type: "danger",
                            title: "Gagal!",
                            message: "Terjadi kesalahan saat menghubungi server.",
                        });
                    })
                    .finally(() => {
                        this.isSaving = false;
                    });
                },
                exportPNG() {
                    const svgEl = document.querySelector('#mermaidContainer svg');
                    if (!svgEl) {
                        SCA.toast({type: 'danger', title: 'Error', message: 'Diagram belum siap untuk diekspor.'});
                        return;
                    }

                    SCA.toast({type: 'success', title: 'Mengekspor...', message: 'Sedang memproses gambar.'});

                    // Kloning SVG agar modifikasi tidak merusak tampilan live di browser
                    const clonedSvg = svgEl.cloneNode(true);
                    
                    // Modifikasi teks garis (edge labels)
                    clonedSvg.querySelectorAll('.edgeLabel, .edgeLabel span, .edgeLabel div').forEach(el => {
                        el.style.backgroundColor = 'transparent';
                        el.style.color = '#000000'; // Teks hitam
                        
                        if (el.tagName.toLowerCase() === 'span' || el.tagName.toLowerCase() === 'div') {
                            // Berikan efek 'Halo' (Outline Putih tebal) pada teks agar memotong garis di belakangnya secara elegan
                            el.style.textShadow = '2px 2px 0 #ffffff, -2px -2px 0 #ffffff, 2px -2px 0 #ffffff, -2px 2px 0 #ffffff, 0px 2px 0 #ffffff, 0px -2px 0 #ffffff, 2px 0px 0 #ffffff, -2px 0px 0 #ffffff';
                            el.style.fontSize = '12px'; // Perkecil sedikit agar aman dari pemotongan (clipping) kotak SVG
                            el.style.lineHeight = '1.2';
                        }
                    });
                    
                    // Hilangkan kotak bawaan mermaid di belakang teks
                    clonedSvg.querySelectorAll('.edgeLabel rect').forEach(rect => {
                        rect.setAttribute('fill', 'transparent');
                        rect.style.fill = 'transparent';
                        rect.style.stroke = 'transparent';
                    });

                    const svgData = new XMLSerializer().serializeToString(clonedSvg);
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    const img = new Image();
                    
                    const svgBlob = new Blob([svgData], {type: "image/svg+xml;charset=utf-8"});
                    const url = URL.createObjectURL(svgBlob);
                    
                    // High resolution export (2x scale)
                    const viewBox = svgEl.getAttribute('viewBox');
                    let svgWidth, svgHeight;
                    
                    if (viewBox) {
                        const vbParts = viewBox.split(' ');
                        svgWidth = parseFloat(vbParts[2]);
                        svgHeight = parseFloat(vbParts[3]);
                    } else {
                        const rect = svgEl.getBoundingClientRect();
                        svgWidth = rect.width;
                        svgHeight = rect.height;
                    }
                    
                    canvas.width = svgWidth * 2;
                    canvas.height = svgHeight * 2;
                    
                    img.onload = () => {
                        // Background transparan (tidak di-fill warna gelap lagi)
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        URL.revokeObjectURL(url);
                        
                        const a = document.createElement("a");
                        a.download = `Diagram_{{ Str::slug($diagram->name) }}.png`;
                        a.href = canvas.toDataURL("image/png");
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    };
                    img.src = url;
                }
            }));
        });
    </script>
    @endpush
</x-master-layout>
