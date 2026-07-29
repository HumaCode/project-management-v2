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
                background: var(--card);
                border: 1px solid var(--bd);
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            .builder-sidebar-header {
                padding: 15px 20px;
                border-bottom: 1px solid var(--bd);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(255, 255, 255, 0.01);
            }
            html[data-theme="light"] .builder-sidebar-header {
                background: rgba(15, 23, 42, 0.01);
            }
            .builder-sidebar-body {
                flex: 1;
                overflow-y: auto;
                padding: 20px;
            }
            .builder-canvas {
                flex: 1;
                background: var(--card);
                border: 1px solid var(--bd);
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                overflow: hidden;
                position: relative;
                transition: all 0.3s ease;
            }
            .canvas-header {
                padding: 15px 20px;
                border-bottom: 1px solid var(--bd);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: rgba(255, 255, 255, 0.01);
            }
            html[data-theme="light"] .canvas-header {
                background: rgba(15, 23, 42, 0.01);
            }
            .canvas-body {
                flex: 1;
                overflow: auto;
                background: #0b0f19; /* Default premium dark bg */
                display: grid;
                place-items: center;
                padding: 20px;
                position: relative;
                transition: background 0.3s ease;
                cursor: grab;
            }
            .canvas-body:active {
                cursor: grabbing;
            }
            html[data-theme="light"] .canvas-body {
                background: #f4f6fa; /* Light canvas bg */
            }
            
            /* Distinct Node Cursor */
            .mermaid-wrapper g.node, .mermaid-wrapper .node {
                cursor: move !important;
            }
            .mermaid-wrapper g.node:active, .mermaid-wrapper .node:active {
                cursor: grabbing !important;
            }
            
            /* Dot Grid Background for Canvas */
            .canvas-bg {
                background-image: radial-gradient(rgba(255, 255, 255, 0.07) 1.2px, transparent 1.2px);
                background-size: 20px 20px;
                transition: background-image 0.3s ease;
            }
            html[data-theme="light"] .canvas-bg {
                background-image: radial-gradient(rgba(15, 23, 42, 0.08) 1.2px, transparent 1.2px);
            }

            .mermaid-wrapper {
                width: 100%;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                transition: transform 0.2s ease-out;
                transform-origin: center center;
                overflow: visible !important;
            }
            .mermaid-wrapper svg {
                overflow: visible !important;
            }

            .node-card {
                background: rgba(255, 255, 255, 0.015);
                border: 1px solid var(--bd) !important;
                border-radius: 10px;
                padding: 16px;
                margin-bottom: 16px;
                position: relative;
                transition: all 0.25s ease;
            }
            html[data-theme="light"] .node-card {
                background: rgba(15, 23, 42, 0.015);
            }
            .node-card:hover {
                border-color: var(--cyan) !important;
                box-shadow: 0 4px 12px rgba(0, 200, 255, 0.05);
            }
            
            .node-card .btn-remove {
                position: absolute;
                top: 12px;
                right: 12px;
                padding: 2px 6px;
                font-size: 12px;
                border-radius: 6px;
            }

            /* Overrides for inputs in sidebar */
            .builder-sidebar .form-label {
                color: var(--txt) !important;
                font-size: 11.5px !important;
                font-weight: 700 !important;
                margin-bottom: 6px !important;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                opacity: 0.85;
            }
            .builder-sidebar .form-control, 
            .builder-sidebar .form-select {
                background-color: rgba(255, 255, 255, 0.02) !important;
                border: 1px solid var(--bd) !important;
                color: var(--txt) !important;
                border-radius: 8px;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                transition: all 0.2s ease;
            }
            html[data-theme="light"] .builder-sidebar .form-control, 
            html[data-theme="light"] .builder-sidebar .form-select {
                background-color: #ffffff !important;
            }
            .builder-sidebar .form-select option {
                background-color: var(--crd-bg, #1e293b);
                color: var(--txt, #ffffff);
            }
            .builder-sidebar .form-control:focus, 
            .builder-sidebar .form-select:focus {
                border-color: var(--cyan, #00c8ff) !important;
                box-shadow: 0 0 0 3px rgba(0, 200, 255, 0.1) !important;
            }
            
            /* Attribute Container inside ERD Node Card */
            .attribute-container {
                border: 1px solid var(--bd) !important;
                background-color: rgba(255, 255, 255, 0.01) !important;
                border-radius: 8px;
            }
            html[data-theme="light"] .attribute-container {
                background-color: rgba(15, 23, 42, 0.02) !important;
            }

            /* Zoom Controls */
            .zoom-controls {
                position: absolute;
                bottom: 20px;
                right: 20px;
                background: #1e293b;
                border: 1px solid var(--bd, #334155);
                border-radius: 8px;
                display: flex;
                box-shadow: 0 4px 15px rgba(0,0,0,0.25);
                z-index: 10;
            }
            html[data-theme="light"] .zoom-controls {
                background: #ffffff !important;
                border-color: rgba(15, 23, 42, 0.15) !important;
                box-shadow: 0 4px 20px rgba(0,0,0,0.12) !important;
            }
            .zoom-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: transparent;
                border: none;
                color: var(--txt, #ffffff);
                border-right: 1px solid var(--bd, #334155);
                cursor: pointer;
                transition: all 0.2s ease;
            }
            html[data-theme="light"] .zoom-btn {
                color: #334155 !important;
                border-right: 1px solid rgba(15, 23, 42, 0.1) !important;
            }
            .zoom-btn:last-child { border-right: none !important; }
            .zoom-btn:hover { background: rgba(255,255,255,0.08); color: var(--cyan, #00c8ff) !important; }
            html[data-theme="light"] .zoom-btn:hover { background: rgba(15, 23, 42, 0.05) !important; color: #0284c7 !important; }

            .zoom-btn-target {
                color: var(--cyan, #00c8ff) !important;
            }
            html[data-theme="light"] .zoom-btn-target {
                color: #0284c7 !important;
            }

            /* Export PNG Button */
            .btn-export-png {
                background: rgba(0, 200, 255, 0.08);
                color: var(--cyan, #00c8ff);
                border: 1px solid rgba(0, 200, 255, 0.3);
                font-size: 12px;
                font-weight: 600;
                padding: 6px 14px;
                border-radius: 8px;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                cursor: pointer;
            }
            .btn-export-png:hover {
                background: var(--cyan, #00c8ff);
                color: #0f172a !important;
                border-color: var(--cyan, #00c8ff);
                box-shadow: 0 4px 12px rgba(0, 200, 255, 0.3);
                transform: translateY(-1px);
            }
            html[data-theme="light"] .btn-export-png {
                background: rgba(2, 132, 199, 0.08);
                color: #0284c7;
                border-color: rgba(2, 132, 199, 0.25);
            }
            html[data-theme="light"] .btn-export-png:hover {
                background: #0284c7;
                color: #ffffff !important;
                border-color: #0284c7;
                box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            }

            /* Clean Form Select Inputs */
            .builder-sidebar .form-select-sm, .builder-sidebar .form-control-sm {
                font-size: 12px;
                padding-right: 22px;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden;
            }
            html[data-theme="light"] .builder-sidebar .form-select-sm,
            html[data-theme="light"] .builder-sidebar .form-control-sm {
                background-color: #ffffff;
                color: #1e293b;
                border: 1px solid rgba(148, 163, 184, 0.35);
            }
            html[data-theme="light"] .builder-sidebar .form-select-sm:focus,
            html[data-theme="light"] .builder-sidebar .form-control-sm:focus {
                border-color: #0284c7;
                box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.12);
            }

            /* Hover Expand on Collapsed App Sidebar */
            .sidebar.collapsed {
                transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
            }
            .sidebar.collapsed:hover {
                width: 260px !important;
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.5) !important;
                z-index: 500 !important;
            }
            .sidebar.collapsed:hover .sb-title,
            .sidebar.collapsed:hover .nav-text,
            .sidebar.collapsed:hover .nav-badge,
            .sidebar.collapsed:hover .nav-section,
            .sidebar.collapsed:hover .sb-user-info,
            .sidebar.collapsed:hover .sb-user-chevron,
            .sidebar.collapsed:hover .sb-chev {
                opacity: 1 !important;
                width: auto !important;
                pointer-events: auto !important;
                overflow: visible !important;
                transition: opacity 0.2s ease 0.05s;
            }
            .sidebar.collapsed:hover .nav-link {
                justify-content: flex-start !important;
                padding: 10px 16px !important;
            }
            .sidebar.collapsed:hover .sb-user {
                justify-content: flex-start !important;
                padding: 10px 16px !important;
            }
            .sidebar.collapsed:hover .nav-link::after {
                display: none !important;
            }
            
            /* Dashed Add Button */
            .btn-dashed {
                border: 1px dashed var(--bd);
                background: rgba(255, 255, 255, 0.02);
                color: var(--dim, #94a3b8);
                width: 100%;
                padding: 10px;
                border-radius: 8px;
                transition: 0.2s;
                font-size: 13px;
                font-weight: 500;
            }
            html[data-theme="light"] .btn-dashed {
                background: rgba(15, 23, 42, 0.01);
            }
            .btn-dashed:hover {
                border-color: var(--cyan, #00c8ff) !important;
                color: var(--cyan, #00c8ff) !important;
                background: rgba(0, 200, 255, 0.05) !important;
            }

            /* Back button */
            .btn-back {
                border: 1px solid var(--bd) !important;
                color: var(--txt) !important;
                background: transparent !important;
                border-radius: 8px;
                padding: 6px 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }
            .btn-back:hover {
                background: rgba(255, 255, 255, 0.05) !important;
                border-color: var(--cyan) !important;
                color: var(--cyan) !important;
            }
            html[data-theme="light"] .btn-back:hover {
                background: rgba(15, 23, 42, 0.04) !important;
            }

            /* Textarea Syntax Editor */
            .syntax-editor {
                font-family: var(--mono, monospace) !important;
                font-size: 12px !important;
                background: #0b1120 !important;
                border-color: var(--bd) !important;
                color: #a78bfa !important;
            }
            html[data-theme="light"] .syntax-editor {
                background: #f8fafc !important;
                color: #6d28d9 !important;
            }

            /* Custom Layout Switcher Control */
            .layout-switcher {
                display: inline-flex;
                align-items: center;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid var(--bd, #334155);
                border-radius: 8px;
                padding: 3px;
                gap: 2px;
            }
            html[data-theme="light"] .layout-switcher {
                background: rgba(15, 23, 42, 0.03);
                border-color: rgba(15, 23, 42, 0.12);
            }
            .layout-switcher-label {
                font-size: 11px;
                font-weight: 600;
                color: var(--dim, #94a3b8);
                padding: 0 8px;
                display: flex;
                align-items: center;
                gap: 4px;
            }
            html[data-theme="light"] .layout-switcher-label {
                color: #64748b;
            }
            .layout-btn {
                border: none;
                background: transparent;
                color: var(--dim, #94a3b8);
                font-size: 11.5px;
                font-weight: 500;
                padding: 3px 10px;
                border-radius: 6px;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                cursor: pointer;
            }
            html[data-theme="light"] .layout-btn {
                color: #475569;
            }
            .layout-btn:hover {
                color: var(--txt, #ffffff);
                background: rgba(255, 255, 255, 0.06);
            }
            html[data-theme="light"] .layout-btn:hover {
                color: #0f172a;
                background: rgba(15, 23, 42, 0.06);
            }
            .layout-btn.active {
                background: #0284c7 !important;
                color: #ffffff !important;
                font-weight: 600;
                box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
            }

            /* Sidebar Tab Nav */
            .sidebar-tab-nav {
                display: flex;
                background: rgba(0, 0, 0, 0.02);
                border-bottom: 1px solid var(--bd, #334155);
                padding: 6px 12px 0 12px;
                gap: 6px;
            }
            html[data-theme="light"] .sidebar-tab-nav {
                background: rgba(15, 23, 42, 0.02);
            }
            .sidebar-tab-btn {
                border: none;
                background: transparent;
                color: var(--dim, #94a3b8);
                font-size: 12px;
                font-weight: 600;
                padding: 7px 14px;
                border-radius: 8px 8px 0 0;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                gap: 6px;
                cursor: pointer;
                border-bottom: 2px solid transparent;
            }
            html[data-theme="light"] .sidebar-tab-btn {
                color: #64748b;
            }
            .sidebar-tab-btn:hover {
                color: var(--txt, #ffffff);
                background: rgba(255, 255, 255, 0.04);
            }
            html[data-theme="light"] .sidebar-tab-btn:hover {
                color: #0f172a;
                background: rgba(15, 23, 42, 0.04);
            }
            .sidebar-tab-btn.active {
                color: var(--cyan, #00c8ff) !important;
                border-bottom-color: var(--cyan, #00c8ff) !important;
                background: rgba(0, 200, 255, 0.08) !important;
            }
            html[data-theme="light"] .sidebar-tab-btn.active {
                color: #0284c7 !important;
                border-bottom-color: #0284c7 !important;
                background: rgba(2, 132, 199, 0.08) !important;
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

    <!-- Header (Rich & Complete) -->
    <div class="pg-hd mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="pg-hd-left d-flex align-items-center gap-2">
            <a href="{{ route('diagrams.index') }}" class="btn btn-back me-1" title="Kembali ke Daftar Diagram">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h5 class="pg-title mb-0 fw-bold">{{ $diagram->name }}</h5>
                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-0-5 text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                        {{ $diagram->type }}
                    </span>
                </div>
                <div class="pg-sub text-muted" style="font-size: 11px;">
                    Terakhir diperbarui: {{ $diagram->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <div class="pg-actions d-flex align-items-center gap-2">
            <!-- Sync Status -->
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1.5" style="font-size: 11px;">
                <i class="bi bi-cloud-check-fill text-success me-1"></i> Sinkronisasi Aktif
            </span>

            <!-- Pintasan Keyboard Button -->
            <button type="button" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center px-3 py-1-5 rounded-pill" data-bs-toggle="modal" data-bs-target="#shortcutsModal" style="font-size: 11px;">
                <i class="bi bi-keyboard me-2 text-info"></i> Pintasan
            </button>

            <!-- Main Save Button -->
            <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center px-3 py-1-5 rounded-pill shadow-sm" @click="saveDiagram" :disabled="isSaving" style="font-size: 12px; font-weight: 600;">
                <i class="bi bi-cloud-arrow-up-fill me-1.5" x-show="!isSaving"></i>
                <span class="spinner-border spinner-border-sm me-1.5" x-show="isSaving" style="display: none;"></span>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Diagram'"></span>
            </button>
        </div>
    </div>

    <!-- Alpine Builder App -->
    <div x-data="diagramBuilder()" class="builder-container" wire:ignore>
        
        <!-- Sidebar Form -->
        <div class="builder-sidebar">
            <div class="builder-sidebar-header">
                <h6 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i> Konfigurasi</h6>
                <button type="button" class="btn btn-primary btn-sm" @click="saveDiagram" :disabled="isSaving">
                    <span x-show="!isSaving"><i class="bi bi-save me-1"></i> Simpan</span>
                    <span x-show="isSaving"><span class="spinner-border spinner-border-sm"></span></span>
                </button>
            </div>

            <!-- Tab Navigation Bar -->
            <div class="sidebar-tab-nav">
                <button type="button" class="sidebar-tab-btn" :class="sidebarTab === 'visual' ? 'active' : ''" @click="sidebarTab = 'visual'">
                    <i class="bi bi-ui-checks"></i> Form Visual
                </button>
                <button type="button" class="sidebar-tab-btn" :class="sidebarTab === 'code' ? 'active' : ''" @click="sidebarTab = 'code'">
                    <i class="bi bi-code-slash"></i> Editor Kode
                </button>
            </div>

            <div class="builder-sidebar-body d-flex flex-column">
                
                <!-- TAB 1: FORM VISUAL -->
                <div x-show="sidebarTab === 'visual'">
                
                <!-- FLOWCHART BUILDER -->
                <template x-if="type === 'flowchart'">
                    <div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label mb-1" style="font-size: 11px;">Arah Tata Letak</label>
                                <select class="form-select form-select-sm" x-model="diagramData.direction" @change="updateDirection(diagramData.direction)">
                                    <option value="LR">Horizontal (LR)</option>
                                    <option value="TD">Vertikal (TD)</option>
                                    <option value="RL">Kanan-Kiri (RL)</option>
                                    <option value="BT">Bawah-Atas (BT)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label mb-1" style="font-size: 11px;">Gaya Garis</label>
                                <select class="form-select form-select-sm" x-model="diagramData.curve" @change="updateCurve(diagramData.curve)">
                                    <option value="basis">Lengkung</option>
                                    <option value="linear">Lurus</option>
                                    <option value="stepBefore">Siku-siku</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                            <label class="form-label mb-0">Daftar Node (Langkah)</label>
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
                                    <select class="form-select form-select-sm" x-model="node.shape" @change="generateMermaid">
                                        <option value="pill">Pil (Mulai/Selesai)</option>
                                        <option value="square">Kotak Persegi (Proses)</option>
                                        <option value="round">Kotak Tumpul (Proses Alternatif)</option>
                                        <option value="diamond">Ketupat (Keputusan)</option>
                                        <option value="parallelogram">Jajar Genjang (Input / Output)</option>
                                        <option value="database">Tabung (Database / Penyimpanan)</option>
                                        <option value="subroutine">Kotak Garis Ganda (Sub-proses)</option>
                                        <option value="hexagon">Segi Enam (Persiapan)</option>
                                        <option value="circle">Lingkaran (Konektor)</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Tambah Node di Bawah -->
                        <button type="button" class="btn btn-dashed mb-4 mt-1" @click="addNode">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Node
                        </button>

                        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                            <label class="form-label mb-0">Sambungan (Garis)</label>
                        </div>
                        
                        <template x-for="(link, index) in diagramData.links" :key="index">
                            <div class="node-card">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeLink(index)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Dari Node</label>
                                        <select class="form-select form-select-sm" x-model="link.from" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.from == n.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Ke Node</label>
                                        <select class="form-select form-select-sm" x-model="link.to" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.to == n.id"></option>
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
                            <label class="form-label mb-0">Daftar Tabel (Entitas)</label>
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
                                
                                <div class="attribute-container border rounded p-2 mb-2">
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
                            <label class="form-label mb-0">Relasi Antar Tabel</label>
                            <button type="button" class="btn btn-outline-info btn-sm py-0 px-2" @click="addRelationship" style="font-size: 12px;"><i class="bi bi-plus"></i> Tambah Relasi</button>
                        </div>
                        
                        <template x-for="(rel, rIndex) in diagramData.relationships" :key="'r'+rIndex">
                            <div class="node-card mb-2" style="border-left-color: #3b82f6;">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeRelationship(rIndex)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Tabel 1</label>
                                        <select class="form-select form-select-sm" x-model="rel.from" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="e in diagramData.entities" :key="e.name">
                                                <option :value="e.name" x-text="e.name" :selected="rel.from == e.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Tabel 2</label>
                                        <select class="form-select form-select-sm" x-model="rel.to" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="e in diagramData.entities" :key="e.name">
                                                <option :value="e.name" x-text="e.name" :selected="rel.to == e.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Tipe Relasi (Kardinalitas)</label>
                                    <select class="form-select form-select-sm" x-model="rel.type" @change="generateMermaid">
                                        <option value="||--o{">1 ke Banyak (1 to Many)</option>
                                        <option value="||--||">1 ke 1 (1 to 1)</option>
                                        <option value="}o--o{">Banyak ke Banyak (M to M)</option>
                                        <option value="|o--o{">0/1 ke Banyak (0/1 to M)</option>
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
                
                <!-- DFD BUILDER -->
                <template x-if="type === 'dfd'">
                    <div>
                        <div class="mb-3">
                            <label class="form-label">Arah Aliran Data</label>
                            <select class="form-select form-select-sm" x-model="diagramData.direction" @change="generateMermaid">
                                <option value="TD">Atas ke Bawah (Top-Down)</option>
                                <option value="LR">Kiri ke Kanan (Left-Right)</option>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Komponen DFD</label>
                        </div>
                        
                        <template x-for="(node, index) in diagramData.nodes" :key="index">
                            <div class="node-card">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeNode(index)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-4">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">ID (Unik)</label>
                                        <input type="text" class="form-control form-control-sm" x-model="node.id" @input.debounce.500ms="generateMermaid" placeholder="A, B, dll">
                                    </div>
                                    <div class="col-8">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Teks (Label)</label>
                                        <input type="text" class="form-control form-control-sm" x-model="node.label" @input.debounce.500ms="generateMermaid" placeholder="Teks komponen">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Jenis Komponen (Standar DFD)</label>
                                    <select class="form-select form-select-sm" x-model="node.shape" @change="generateMermaid">
                                        <option value="square">Entitas Eksternal (Kotak)</option>
                                        <option value="circle">Proses (Lingkaran)</option>
                                        <option value="database">Data Store (Tabung)</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                        
                        <button type="button" class="btn btn-dashed mb-4 mt-1" @click="addNode">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Komponen
                        </button>

                        <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                            <label class="form-label mb-0">Aliran Data (Garis)</label>
                        </div>
                        
                        <template x-for="(link, index) in diagramData.links" :key="'l'+index">
                            <div class="node-card">
                                <button type="button" class="btn btn-outline-danger btn-remove" @click="removeLink(index)"><i class="bi bi-trash"></i></button>
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Dari</label>
                                        <select class="form-select form-select-sm" x-model="link.from" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.from == n.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label text-light mb-1" style="font-size: 11px;">Ke</label>
                                        <select class="form-select form-select-sm" x-model="link.to" @change="generateMermaid">
                                            <option value="">Pilih...</option>
                                            <template x-for="n in diagramData.nodes" :key="n.id">
                                                <option :value="n.id" x-text="n.id + ' - ' + n.label" :selected="link.to == n.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label text-light mb-1" style="font-size: 11px;">Keterangan Data</label>
                                    <input type="text" class="form-control form-control-sm" x-model="link.text" @input.debounce.500ms="generateMermaid" placeholder="Info Login, dll">
                                </div>
                            </div>
                        </template>
                        
                        <button type="button" class="btn btn-dashed mb-2 mt-1" @click="addLink">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Aliran Data
                        </button>
                    </div>
                </template>

                </div>
                <!-- END TAB 1: FORM VISUAL -->

                <!-- TAB 2: EDITOR KODE MERMAID -->
                <div x-show="sidebarTab === 'code'" class="flex-grow-1 d-flex flex-column h-100" style="min-height: 500px;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0 fw-bold d-flex align-items-center gap-1" style="font-size: 12px;">
                            <i class="bi bi-code-square text-info"></i> Editor Kode Mermaid
                        </label>
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2" @click="navigator.clipboard.writeText(mermaidSyntax); showToast('success', 'Kode Mermaid berhasil disalin.');" title="Salin Kode" style="font-size: 11px;">
                            <i class="bi bi-clipboard me-1"></i> Salin Kode
                        </button>
                    </div>
                    <textarea class="form-control syntax-editor flex-grow-1 w-100" style="min-height: 480px; font-family: monospace; line-height: 1.5; resize: vertical;" x-model="mermaidSyntax" @input.debounce.500ms="parseAndRenderFromCode()" placeholder="Tulis atau paste kode Mermaid di sini..."></textarea>
                    <div class="form-text text-muted mt-2 d-flex align-items-center gap-1" style="font-size: 11px;">
                        <i class="bi bi-arrow-repeat text-info"></i> Kode tersinkronisasi 2-arah secara otomatis dengan Form Visual.
                    </div>
                </div>
                <!-- END TAB 2: EDITOR KODE MERMAID -->
            </div>
        </div>

        <!-- Canvas Preview -->
        <div class="builder-canvas">
            <div class="canvas-header">
                <div class="d-flex align-items-center gap-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-eye me-2"></i> Pratinjau Diagram</h6>
                </div>
                <div>
                    <button type="button" class="btn-export-png" @click="exportPNG" title="Download Gambar (PNG)">
                        <i class="bi bi-download"></i> Ekspor PNG
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
                    <button class="zoom-btn zoom-btn-target" @click="centerDiagram" title="Pusatkan Diagram ke Layar (Focus ala Google Maps)">
                        <i class="bi bi-crosshair"></i>
                    </button>
                    <button class="zoom-btn" @click="zoomOut" title="Zoom Out"><i class="bi bi-dash-lg"></i></button>
                    <button class="zoom-btn" @click="resetZoom" title="Reset Zoom" style="font-size: 11px; width:45px; font-weight:bold" x-text="Math.round(zoom * 100) + '%'"></button>
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
                sidebarTab: 'visual',
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
                    } else if (this.type === 'dfd' && this.diagramData.nodes.length === 0) {
                        this.diagramData.nodes.push(
                            { id: 'User', label: 'Pelanggan', shape: 'square' },
                            { id: 'Proses1', label: 'Verifikasi Order', shape: 'circle' },
                            { id: 'DB1', label: 'Data Pesanan', shape: 'database' }
                        );
                        this.diagramData.links.push(
                            { from: 'User', to: 'Proses1', text: 'Detail Order' },
                            { from: 'Proses1', to: 'DB1', text: 'Simpan' }
                        );
                        this.diagramData.direction = 'LR';
                    }
                    
                    if (savedSyntax && savedSyntax.trim()) {
                        this.mermaidSyntax = savedSyntax;
                        this.parseMermaidToData();
                    } else {
                        this.generateMermaid();
                    }
                    
                    this.initializeMermaid();

                    // Observe theme change to re-render Mermaid with correct colors
                    const observer = new MutationObserver((mutations) => {
                        mutations.forEach((mutation) => {
                            if (mutation.attributeName === 'data-theme') {
                                this.initializeMermaid();
                                this.renderMermaid();
                            }
                        });
                    });
                    observer.observe(document.documentElement, { attributes: true });
                    
                    setTimeout(() => {
                        this.renderMermaid();
                    }, 100);
                },

                initializeMermaid() {
                    const isLight = document.documentElement.getAttribute('data-theme') === 'light';
                    
                    mermaid.initialize({
                        startOnLoad: false,
                        theme: isLight ? 'default' : 'dark',
                        themeVariables: {
                            fontFamily: 'Inter, sans-serif',
                            primaryColor: isLight ? '#ffffff' : '#1e293b',
                            primaryBorderColor: isLight ? '#008eb3' : '#3b82f6',
                            primaryTextColor: isLight ? '#0f172a' : '#ffffff',
                            lineColor: isLight ? '#475569' : '#64748b',
                            textColor: isLight ? '#0f172a' : '#ffffff',
                            mainBkg: isLight ? '#ffffff' : '#1e293b',
                            secondaryColor: isLight ? '#f8fafc' : '#0f172a',
                            tertiaryColor: isLight ? '#ffffff' : '#1e293b',
                            nodeBorder: isLight ? '#008eb3' : '#3b82f6',
                            clusterBkg: isLight ? '#ffffff' : '#1e293b',
                            clusterBorder: isLight ? '#cbd5e1' : '#334155',
                            defaultLinkColor: isLight ? '#475569' : '#94a3b8',
                            labelBoxBkgColor: isLight ? '#f4f6fa' : '#0f172a',
                            edgeLabelBackground: isLight ? '#f4f6fa' : '#0f172a',
                            labelBoxBorderColor: 'transparent',
                        },
                        securityLevel: 'loose',
                        flowchart: { 
                            nodeSpacing: 70,
                            rankSpacing: 70,
                            htmlLabels: true
                        }
                    });
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
                    if (this.type === 'flowchart' || this.type === 'dfd') {
                        const curve = this.diagramData.curve || 'basis';
                        const dir = this.diagramData.direction || 'TD';
                        let code = `%%{init: {'flowchart': {'curve': '${curve}'}}}%%\nflowchart ${dir}\n`;
                        
                        const isLight = document.documentElement.getAttribute('data-theme') === 'light';
                        const fillBg = isLight ? '#ffffff' : '#1e293b';
                        const textCol = isLight ? '#0f172a' : '#fff';
                        const strokeDef = isLight ? '#008eb3' : '#00c8ff';

                        // Define Colors (Premium Look)
                        code += `    classDef default fill:${fillBg},stroke:${strokeDef},stroke-width:2px,color:${textCol},rx:8,ry:8;\n`;
                        code += `    classDef decision fill:${fillBg},stroke:#8b5cf6,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef square fill:${fillBg},stroke:#f59e0b,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef pill fill:#10b981,stroke:#059669,stroke-width:2px,color:#fff;\n`;
                        code += `    classDef io fill:${fillBg},stroke:#ec4899,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef database fill:${fillBg},stroke:#eab308,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef subroutine fill:${fillBg},stroke:#3b82f6,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef circle fill:${fillBg},stroke:#f43f5e,stroke-width:2px,color:${textCol};\n`;
                        code += `    classDef hexagon fill:${fillBg},stroke:#14b8a6,stroke-width:2px,color:${textCol};\n\n`; // Teal untuk Persiapan

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
                        
                        // Make SVG nodes interactive and draggable
                        this.makeNodesDraggable();
                        
                        // Tweak SVG overflow to prevent node clipping when dragged
                        const svgEl = container.querySelector('svg');
                        if(svgEl) {
                            svgEl.style.overflow = 'visible';
                            svgEl.style.maxWidth = 'none';
                            
                            // Bring edge labels layer to the top of SVG so text is never covered
                            const edgeLabelsEl = svgEl.querySelector('.edgeLabels');
                            if (edgeLabelsEl) {
                                svgEl.appendChild(edgeLabelsEl);
                            }
                        }
                    } catch (error) {
                        console.error('Mermaid Render Error:', error);
                        this.renderError = true;
                        this.renderErrorMsg = error.message || 'Terjadi kesalahan sintaks pada diagram. Periksa kembali ID atau Garis Anda.';
                    }
                },

                parseAndRenderFromCode() {
                    this.parseMermaidToData();
                    // For ERD, parseMermaidToData uses $nextTick internally — wait for it before rendering
                    if (this.type === 'erd') {
                        this.$nextTick(() => this.renderMermaid());
                    } else {
                        this.renderMermaid();
                    }
                },

                parseMermaidToData() {
                    if (!this.mermaidSyntax || !this.mermaidSyntax.trim()) return;
                    const text = this.mermaidSyntax;

                    if (this.type === 'flowchart' || this.type === 'dfd') {
                        // 1. Detect Direction
                        const dirMatch = text.match(/(?:flowchart|graph)\s+(TD|LR|RL|BT)/i);
                        if (dirMatch) {
                            this.diagramData.direction = dirMatch[1].toUpperCase();
                        }

                        // 2. Detect Curve
                        const curveMatch = text.match(/'curve':\s*'([^']+)'/i);
                        if (curveMatch) {
                            this.diagramData.curve = curveMatch[1];
                        }

                        const nodesMap = new Map();
                        const links = [];

                        function parseNodeString(str) {
                            if (!str) return null;
                            let s = str.trim();
                            
                            // Strip classDef association like :::pill
                            s = s.replace(/:::[A-Za-z0-9_]+$/, '').trim();

                            let m;
                            // Pill: ID(["Label"])
                            if ((m = s.match(/^([A-Za-z0-9_]+)\(\[\s*"?([\s\S]*?)"?\s*\]\)$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'pill' };
                            }
                            // Subroutine: ID([[ "Label" ]])
                            if ((m = s.match(/^([A-Za-z0-9_]+)\[\[\s*"?([\s\S]*?)"?\s*\]\]$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'subroutine' };
                            }
                            // Database: ID[( "Label" )]
                            if ((m = s.match(/^([A-Za-z0-9_]+)\[\(\s*"?([\s\S]*?)"?\s*\)\]$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'database' };
                            }
                            // Circle: ID(( "Label" ))
                            if ((m = s.match(/^([A-Za-z0-9_]+)\(\(\s*"?([\s\S]*?)"?\s*\)\)$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'circle' };
                            }
                            // Hexagon: ID{{ "Label" }}
                            if ((m = s.match(/^([A-Za-z0-9_]+)\{\{\s*"?([\s\S]*?)"?\s*\}\}$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'hexagon' };
                            }
                            // Diamond: ID{ "Label" }
                            if ((m = s.match(/^([A-Za-z0-9_]+)\{\s*"?([\s\S]*?)"?\s*\}$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'diamond' };
                            }
                            // Parallelogram: ID[/ "Label" /]
                            if ((m = s.match(/^([A-Za-z0-9_]+)\[\/\s*"?([\s\S]*?)"?\s*\/\]$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'parallelogram' };
                            }
                            // Square: ID[ "Label" ]
                            if ((m = s.match(/^([A-Za-z0-9_]+)\[\s*"?([\s\S]*?)"?\s*\]$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'square' };
                            }
                            // Round: ID( "Label" )
                            if ((m = s.match(/^([A-Za-z0-9_]+)\(\s*"?([\s\S]*?)"?\s*\)$/))) {
                                return { id: m[1], label: m[2].replace(/\\n/g, '\n'), shape: 'round' };
                            }
                            // Plain ID
                            if ((m = s.match(/^([A-Za-z0-9_]+)$/))) {
                                return { id: m[1], label: m[1], shape: 'round' };
                            }
                            return null;
                        }

                        const lines = text.split('\n');
                        lines.forEach(line => {
                            let l = line.trim();
                            if (!l || l.startsWith('%%') || l.startsWith('flowchart') || l.startsWith('graph') || l.startsWith('classDef') || l.startsWith('style')) {
                                return;
                            }

                            // Link line check: A --> B or A -->|"text"| B or A -- "text" --> B
                            const linkMatch = l.match(/(.+?)\s*(?:-->|---|==>)\s*(?:\|"?(.*?)"?\|)?\s*(.+)/);
                            if (linkMatch) {
                                const leftRaw = linkMatch[1].trim();
                                const linkText = linkMatch[2] ? linkMatch[2].replace(/^"|"$/g, '').trim() : '';
                                const rightRaw = linkMatch[3].trim();

                                const leftObj = parseNodeString(leftRaw);
                                const rightObj = parseNodeString(rightRaw);

                                if (leftObj) {
                                    const existing = nodesMap.get(leftObj.id);
                                    if (!existing || (existing.label === existing.id && leftObj.label !== leftObj.id)) {
                                        nodesMap.set(leftObj.id, leftObj);
                                    }
                                }
                                if (rightObj) {
                                    const existing = nodesMap.get(rightObj.id);
                                    if (!existing || (existing.label === existing.id && rightObj.label !== rightObj.id)) {
                                        nodesMap.set(rightObj.id, rightObj);
                                    }
                                }

                                const fromId = leftObj ? leftObj.id : leftRaw;
                                const toId = rightObj ? rightObj.id : rightRaw;

                                if (fromId && toId) {
                                    links.push({ from: fromId, to: toId, text: linkText });
                                }
                            } else {
                                // Standalone node definition
                                const nodeObj = parseNodeString(l);
                                if (nodeObj) {
                                    const existing = nodesMap.get(nodeObj.id);
                                    if (!existing || (existing.label === existing.id && nodeObj.label !== nodeObj.id)) {
                                        nodesMap.set(nodeObj.id, nodeObj);
                                    }
                                }
                            }
                        });

                        if (nodesMap.size > 0) {
                            this.diagramData.nodes = Array.from(nodesMap.values());
                            this.diagramData.links = links;
                        }
                    } else if (this.type === 'erd') {
                        // ─── Parse ERD Mermaid Syntax ───
                        const entities = [];
                        const relationships = [];

                        // Strip first line "erDiagram" and any comment/blank lines
                        const lines = text.split('\n').map(l => l.trim()).filter(l => l && !l.startsWith('%%') && l.toLowerCase() !== 'erdiagram');

                        let currentEntity = null;

                        lines.forEach(line => {
                            // Entity open: TABLE_NAME { 
                            const entityOpenMatch = line.match(/^([A-Za-z0-9_]+)\s*\{/);
                            if (entityOpenMatch && !line.includes('||') && !line.includes('|o') && !line.includes('}o')) {
                                currentEntity = { name: entityOpenMatch[1], attributes: [] };
                                return;
                            }

                            // Entity close: }
                            if (line === '}') {
                                if (currentEntity) {
                                    entities.push(currentEntity);
                                    currentEntity = null;
                                }
                                return;
                            }

                            // Attribute line: type name [PK] [FK] ["comment"]
                            if (currentEntity) {
                                // Match: type name [optional keys/comment]
                                const attrMatch = line.match(/^([A-Za-z0-9_]+)\s+([A-Za-z0-9_]+)(.*)?$/i);
                                if (attrMatch) {
                                    const keysRaw = attrMatch[3] || '';
                                    currentEntity.attributes.push({
                                        type: attrMatch[1],
                                        name: attrMatch[2],
                                        pk: /\bPK\b/i.test(keysRaw),
                                        fk: /\bFK\b/i.test(keysRaw)
                                    });
                                    return;
                                }
                            }

                            // Relationship line: TABLE1 ||--o{ TABLE2 : "label"
                            const relMatch = line.match(/^([A-Za-z0-9_]+)\s+(\|\|--o\{|\|\|--\|\||\}o--o\{|\|o--o\{|\|\|--\|\{|\{o--o\{|[|o}]{2}--[|o}]{2})\s+([A-Za-z0-9_]+)\s*:\s*"?([^"]*)"?/);
                            if (relMatch) {
                                relationships.push({
                                    from: relMatch[1],
                                    to: relMatch[3],
                                    type: relMatch[2],
                                    label: relMatch[4] ? relMatch[4].trim() : ''
                                });
                                return;
                            }
                        });

                        if (entities.length > 0) {
                            // Force Alpine.js reactivity by clearing first then setting on next tick
                            this.diagramData.entities = [];
                            this.$nextTick(() => {
                                this.diagramData.entities = entities;
                                if (relationships.length > 0) {
                                    this.diagramData.relationships = relationships;
                                }
                            });
                        } else if (relationships.length > 0) {
                            this.diagramData.relationships = relationships;
                        }
                    }
                },

                updateDirection(dir) {
                    this.diagramData.direction = dir;
                    if (this.mermaidSyntax && this.mermaidSyntax.trim()) {
                        const dirRegex = /(?:flowchart|graph)\s+(TD|LR|RL|BT)/i;
                        if (dirRegex.test(this.mermaidSyntax)) {
                            this.mermaidSyntax = this.mermaidSyntax.replace(dirRegex, `flowchart ${dir}`);
                        } else {
                            this.mermaidSyntax = `flowchart ${dir}\n` + this.mermaidSyntax;
                        }
                        this.parseMermaidToData();
                        this.renderMermaid();
                    } else {
                        this.generateMermaid();
                    }
                },

                updateCurve(curve) {
                    this.diagramData.curve = curve;
                    if (this.mermaidSyntax && this.mermaidSyntax.trim()) {
                        const initRegex = /%%\{init:\s*\{'flowchart':\s*\{'curve':\s*'[^']+'\}\}\}%%/i;
                        if (initRegex.test(this.mermaidSyntax)) {
                            this.mermaidSyntax = this.mermaidSyntax.replace(initRegex, `%%{init: {'flowchart': {'curve': '${curve}'}}}%%`);
                        } else {
                            this.mermaidSyntax = `%%{init: {'flowchart': {'curve': '${curve}'}}}%%\n` + this.mermaidSyntax;
                        }
                        this.parseMermaidToData();
                        this.renderMermaid();
                    } else {
                        this.generateMermaid();
                    }
                },

                // Zoom & Focus controls
                async centerDiagram() {
                    this.panX = 0;
                    this.panY = 0;
                    this.zoom = 1;
                    
                    let container = document.getElementById('canvasContainer');
                    let svgEl = document.querySelector('#mermaidContainer svg');

                    if (!svgEl || !container) {
                        if (!this.mermaidSyntax || !this.mermaidSyntax.trim()) {
                            this.generateMermaid();
                        }
                        await this.renderMermaid();
                    }
                    
                    if (typeof showToast === 'function') {
                        showToast('success', 'Diagram dipusatkan ke 100%.');
                    }
                },

                makeNodesDraggable() {
                    setTimeout(() => {
                        const container = document.getElementById('mermaidContainer');
                        if (!container) return;
                        
                        const nodes = Array.from(container.querySelectorAll('g.node, g.cluster, .node'));
                        const edgePaths = Array.from(container.querySelectorAll('g.edgePaths path, g.edgePath path, path.path'));
                        const edgeLabels = Array.from(container.querySelectorAll('g.edgeLabel, g.edgeLabels g'));
                        
                        if (nodes.length === 0 || edgePaths.length === 0) return;

                        // Initialize delta and center position for each node
                        nodes.forEach(node => {
                            node._delta = { x: 0, y: 0 };
                            let cx = 0, cy = 0;
                            let transform = node.getAttribute('transform') || '';
                            let match = transform.match(/translate\(([-0-9.]+)[,\s]+([-0-9.]+)\)/);
                            if (match) {
                                cx = parseFloat(match[1]);
                                cy = parseFloat(match[2]);
                            } else {
                                try {
                                    const bbox = node.getBBox();
                                    cx = bbox.x + bbox.width / 2;
                                    cy = bbox.y + bbox.height / 2;
                                } catch (e) {
                                    cx = 0; cy = 0;
                                }
                            }
                            node._initialCenter = { x: cx, y: cy };
                        });

                        // Map all edge labels with their initial center positions
                        const labelDataList = edgeLabels.map(lGroup => {
                            let transform = lGroup.getAttribute('transform') || '';
                            let match = transform.match(/translate\(([-0-9.]+)[,\s]+([-0-9.]+)\)/);
                            let lx = 0, ly = 0;
                            if (match) {
                                lx = parseFloat(match[1]);
                                ly = parseFloat(match[2]);
                            } else {
                                try {
                                    const bbox = lGroup.getBBox();
                                    lx = bbox.x + bbox.width / 2;
                                    ly = bbox.y + bbox.height / 2;
                                } catch (e) {}
                            }
                            return {
                                lGroup,
                                origTransform: transform,
                                center: { x: lx, y: ly },
                                matched: false
                            };
                        });

                        // Match each edge path to closest sourceNode, targetNode, and edgeLabel geometrically
                        const edgesData = [];
                        edgePaths.forEach(pathEl => {
                            const origD = pathEl.getAttribute('d');
                            if (!origD) return;
                            
                            const numberMatches = Array.from(origD.matchAll(/([-+]?[0-9]*\.?[0-9]+)/g));
                            if (numberMatches.length < 4) return;
                            
                            const startX = parseFloat(numberMatches[0][0]);
                            const startY = parseFloat(numberMatches[1][0]);
                            const endX = parseFloat(numberMatches[numberMatches.length - 2][0]);
                            const endY = parseFloat(numberMatches[numberMatches.length - 1][0]);
                            
                            const midX = (startX + endX) / 2;
                            const midY = (startY + endY) / 2;
                            
                            let sourceNode = null, minSourceDist = Infinity;
                            let targetNode = null, minTargetDist = Infinity;
                            
                            nodes.forEach(n => {
                                const distStart = Math.hypot(n._initialCenter.x - startX, n._initialCenter.y - startY);
                                if (distStart < minSourceDist) {
                                    minSourceDist = distStart;
                                    sourceNode = n;
                                }
                                const distEnd = Math.hypot(n._initialCenter.x - endX, n._initialCenter.y - endY);
                                if (distEnd < minTargetDist) {
                                    minTargetDist = distEnd;
                                    targetNode = n;
                                }
                            });
                            
                            // Find closest text label near line midpoint
                            let matchedLabel = null;
                            let minLabelDist = 150; // 150px proximity threshold
                            labelDataList.forEach(ld => {
                                if (ld.matched) return;
                                const dist = Math.hypot(ld.center.x - midX, ld.center.y - midY);
                                if (dist < minLabelDist) {
                                    minLabelDist = dist;
                                    matchedLabel = ld;
                                }
                            });
                            
                            if (matchedLabel) {
                                matchedLabel.matched = true;
                            }
                            
                            const edgeGroup = pathEl.closest('g.edgePath') || pathEl.parentElement;
                            const arrowEl = edgeGroup ? edgeGroup.querySelector('.arrowheadPath, path:not(.path)') : null;
                            const origArrowTransform = arrowEl ? (arrowEl.getAttribute('transform') || '') : '';
                            
                            edgesData.push({
                                pathEl,
                                origD,
                                sourceNode,
                                targetNode,
                                arrowEl,
                                origArrowTransform,
                                labelGroup: matchedLabel ? matchedLabel.lGroup : null,
                                origLabelTransform: matchedLabel ? matchedLabel.origTransform : '',
                                origLabelCenter: matchedLabel ? matchedLabel.center : { x: 0, y: 0 }
                            });
                        });

                        function updatePathFlexible(pathEl, originalD, sourceDelta, targetDelta) {
                            if (!originalD) return null;
                            const numberMatches = Array.from(originalD.matchAll(/([-+]?[0-9]*\.?[0-9]+)/g));
                            if (numberMatches.length < 4) return null;

                            // Original start and end points
                            const origStartX = parseFloat(numberMatches[0][0]);
                            const origStartY = parseFloat(numberMatches[1][0]);
                            const origEndX = parseFloat(numberMatches[numberMatches.length - 2][0]);
                            const origEndY = parseFloat(numberMatches[numberMatches.length - 1][0]);

                            // Current start and end points
                            const currStartX = origStartX + sourceDelta.x;
                            const currStartY = origStartY + sourceDelta.y;
                            const currEndX = origEndX + targetDelta.x;
                            const currEndY = origEndY + targetDelta.y;

                            // If nodes haven't moved, keep initial layout curve
                            if (Math.abs(sourceDelta.x) < 0.1 && Math.abs(sourceDelta.y) < 0.1 &&
                                Math.abs(targetDelta.x) < 0.1 && Math.abs(targetDelta.y) < 0.1) {
                                pathEl.setAttribute('d', originalD);
                                return { origStartX, origStartY, origEndX, origEndY, currStartX, currStartY, currEndX, currEndY };
                            }

                            // Calculate direct clean smooth curve when dragged by user
                            const dx = currEndX - currStartX;
                            const dy = currEndY - currStartY;
                            
                            const cp1x = currStartX + dx * 0.35;
                            const cp1y = currStartY + dy * 0.15;
                            const cp2x = currStartX + dx * 0.65;
                            const cp2y = currStartY + dy * 0.85;

                            const newD = `M ${currStartX.toFixed(2)} ${currStartY.toFixed(2)} C ${cp1x.toFixed(2)} ${cp1y.toFixed(2)}, ${cp2x.toFixed(2)} ${cp2y.toFixed(2)}, ${currEndX.toFixed(2)} ${currEndY.toFixed(2)}`;
                            
                            pathEl.setAttribute('d', newD);

                            return {
                                origStartX, origStartY, origEndX, origEndY,
                                currStartX, currStartY, currEndX, currEndY
                            };
                        }

                        function updateAllEdges() {
                            edgesData.forEach(edge => {
                                let sourceDelta = edge.sourceNode ? edge.sourceNode._delta : { x: 0, y: 0 };
                                let targetDelta = edge.targetNode ? edge.targetNode._delta : { x: 0, y: 0 };

                                const endpoints = updatePathFlexible(edge.pathEl, edge.origD, sourceDelta, targetDelta);

                                if (edge.arrowEl && endpoints) {
                                    const shiftArrowX = endpoints.currEndX - endpoints.origEndX;
                                    const shiftArrowY = endpoints.currEndY - endpoints.origEndY;
                                    edge.arrowEl.setAttribute('transform', `${edge.origArrowTransform} translate(${shiftArrowX.toFixed(2)}, ${shiftArrowY.toFixed(2)})`.trim());
                                }

                                if (edge.labelGroup) {
                                    try {
                                        const totalLen = edge.pathEl.getTotalLength();
                                        if (totalLen > 0) {
                                            const midPt = edge.pathEl.getPointAtLength(totalLen * 0.5);
                                            const shiftX = midPt.x - edge.origLabelCenter.x;
                                            const shiftY = midPt.y - edge.origLabelCenter.y;
                                            edge.labelGroup.setAttribute('transform', `${edge.origLabelTransform} translate(${shiftX.toFixed(2)}, ${shiftY.toFixed(2)})`.trim());
                                        }
                                    } catch (e) {
                                        let midX = (sourceDelta.x + targetDelta.x) / 2;
                                        let midY = (sourceDelta.y + targetDelta.y) / 2;
                                        edge.labelGroup.setAttribute('transform', `${edge.origLabelTransform} translate(${midX.toFixed(2)}, ${midY.toFixed(2)})`.trim());
                                    }
                                }
                            });
                        }

                        nodes.forEach(node => {
                            node.style.cursor = 'move';

                            const startDrag = (e) => {
                                e.stopPropagation();
                                
                                const isTouch = e.type === 'touchstart';
                                const clientX = isTouch ? e.touches[0].clientX : e.clientX;
                                const clientY = isTouch ? e.touches[0].clientY : e.clientY;
                                
                                node.style.cursor = 'grabbing';
                                
                                let transform = node.getAttribute('transform') || 'translate(0, 0)';
                                let match = transform.match(/translate\(([-0-9.]+)[,\s]+([-0-9.]+)\)/);
                                let currentX = match ? parseFloat(match[1]) : 0;
                                let currentY = match ? parseFloat(match[2]) : 0;
                                
                                let startMouseX = clientX;
                                let startMouseY = clientY;
                                let zoom = this.zoom || 1;
                                
                                const onMove = (moveEvent) => {
                                    const moveX = isTouch ? moveEvent.touches[0].clientX : moveEvent.clientX;
                                    const moveY = isTouch ? moveEvent.touches[0].clientY : moveEvent.clientY;
                                    
                                    let dx = (moveX - startMouseX) / zoom;
                                    let dy = (moveY - startMouseY) / zoom;
                                    
                                    let newX = currentX + dx;
                                    let newY = currentY + dy;
                                    
                                    node.setAttribute('transform', `translate(${newX}, ${newY})`);
                                    
                                    node._delta.x += dx;
                                    node._delta.y += dy;
                                    
                                    startMouseX = moveX;
                                    startMouseY = moveY;
                                    currentX = newX;
                                    currentY = newY;
                                    
                                    updateAllEdges();
                                };
                                
                                const onEnd = () => {
                                    node.style.cursor = 'move';
                                    if (isTouch) {
                                        document.removeEventListener('touchmove', onMove);
                                        document.removeEventListener('touchend', onEnd);
                                    } else {
                                        document.removeEventListener('mousemove', onMove);
                                        document.removeEventListener('mouseup', onEnd);
                                    }
                                };
                                
                                if (isTouch) {
                                    document.addEventListener('touchmove', onMove, { passive: false });
                                    document.addEventListener('touchend', onEnd);
                                } else {
                                    document.addEventListener('mousemove', onMove);
                                    document.addEventListener('mouseup', onEnd);
                                }
                            };
                            
                            node.addEventListener('mousedown', startDrag);
                            node.addEventListener('touchstart', startDrag, { passive: false });
                        });
                    }, 50);
                },

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

        // Auto-collapse main application sidebar on Diagram Builder page to maximize canvas space
        (function autoCollapseSidebar() {
            function collapse() {
                const sb = document.getElementById("sidebar");
                const mw = document.getElementById("mainWrap");
                const ico = document.getElementById("toggleIcon");
                if (sb && mw) {
                    sb.classList.add("collapsed");
                    mw.classList.add("expanded");
                    if (ico) ico.className = "bi bi-layout-sidebar";
                }
            }
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", collapse);
            } else {
                collapse();
            }
        })();
    </script>
    @endpush

    <!-- Modal Pintasan Keyboard & Navigasi -->
    <div class="modal fade" id="shortcutsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h6 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-keyboard text-info"></i> Pintasan & Panduan Navigasi Canvas
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-arrows-move me-2 text-info" style="font-size: 16px;"></i> Geser/Pindahkan Node Kotak
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Cursor Move / Panah 4 Arah</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-hand-index-thumb me-2 text-info" style="font-size: 16px;"></i> Pan / Pergeseran Layar Canvas
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Cursor Grab / Tangan</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-zoom-in me-2 text-info" style="font-size: 16px;"></i> Zoom In / Zoom Out
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Mouse Scroll / Ctrl + Scroll</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-crosshair me-2 text-info" style="font-size: 16px;"></i> Reset Posisi Ke Tengah (Focus)
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Tombol Crosshair Target</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 py-2">
                            <span class="d-flex align-items-center gap-2" style="font-size: 13px;">
                                <i class="bi bi-code-square me-2 text-info" style="font-size: 16px;"></i> Editor Kode 2-Arah
                            </span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Tab Editor Kode</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-primary w-100 rounded-pill" data-bs-dismiss="modal">Mengerti</button>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
