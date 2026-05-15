/**
 * Cover Builder Module - V2 (Cache Buster)
 */
const CoverBuilder = {
    canvas: null,
    modal: null,
    coverImage: null,

    init() {
        if (!document.getElementById('coverCanvas')) return;
        console.log('Cover Builder V2 Initialized');
        
        this.modal = new bootstrap.Modal(document.getElementById('modalCover'), {
            focus: false // MATIKAN FOCUS TRAP AGAR KEYBOARD BISA MASUK KE KANVAS
        });
        this.initCanvas();
        this.initGlobalEvents();
    },

    initCanvas() {
        CoverBuilder.canvas = new fabric.Canvas('coverCanvas', {
            width: 595,
            height: 842,
            backgroundColor: '#ffffff',
            preserveObjectStacking: true
        });

        CoverBuilder.canvas.on('selection:created', (e) => CoverBuilder.updateControls(e));
        CoverBuilder.canvas.on('selection:updated', (e) => CoverBuilder.updateControls(e));
        CoverBuilder.canvas.on('selection:cleared', () => {
            $('#objectControls, #textSpecificControls').hide();
        });

        CoverBuilder.addText('JUDUL LAPORAN PROYEK', true);
    },

    updateControls(e) {
        $('#objectControls').show();
        const active = e.selected[0];
        if (active && active.type === 'textbox') {
            $('#textSpecificControls').show();
        } else {
            $('#textSpecificControls').hide();
        }
    },

    initGlobalEvents() {
        $(window).on('keydown', (e) => {
            if (!$('#modalCover').hasClass('show')) return;

            const activeObject = CoverBuilder.canvas.getActiveObject();
            if (e.keyCode === 8) {
                if (!activeObject || !activeObject.isEditing) {
                    e.preventDefault();
                    if (activeObject) CoverBuilder.deleteSelected();
                }
            }
            if (e.keyCode === 46 && activeObject && !activeObject.isEditing) {
                CoverBuilder.deleteSelected();
            }
        });
    },

    open() {
        this.modal.show();
        setTimeout(() => {
            CoverBuilder.canvas.calcOffset();
            CoverBuilder.canvas.renderAll();
            
            // PAKSA FOKUS KE ELEMEN KANVAS
            const el = document.querySelector('.upper-canvas');
            if (el) {
                el.focus();
                el.click(); // Trigger klik simulasi
            }
        }, 600);
    },

    editText() {
        const active = CoverBuilder.canvas.getActiveObject();
        if (active && active.type === 'textbox') {
            active.enterEditing();
            active.selectAll();
            CoverBuilder.canvas.renderAll();
        }
    },

    toggleStyle(style) {
        const active = CoverBuilder.canvas.getActiveObject();
        if (!active || active.type !== 'textbox') return;

        switch (style) {
            case 'bold':
                active.set('fontWeight', active.fontWeight === 'bold' ? 'normal' : 'bold');
                break;
            case 'italic':
                active.set('fontStyle', active.fontStyle === 'italic' ? 'normal' : 'italic');
                break;
            case 'underline':
                active.set('underline', !active.underline);
                break;
        }
        CoverBuilder.canvas.renderAll();
    },

    addShape(type) {
        let shape;
        const config = {
            left: 100,
            top: 100,
            fill: '#00c8ff',
            stroke: '#0088cc',
            strokeWidth: 0
        };

        if (type === 'rect') {
            shape = new fabric.Rect({ ...config, width: 100, height: 100 });
        } else if (type === 'circle') {
            shape = new fabric.Circle({ ...config, radius: 50 });
        } else if (type === 'line') {
            shape = new fabric.Rect({ ...config, width: 300, height: 4 });
        }

        CoverBuilder.canvas.add(shape);
        CoverBuilder.canvas.centerObject(shape);
        CoverBuilder.canvas.setActiveObject(shape);
        CoverBuilder.canvas.renderAll();
    },

    applyTemplate(name) {
        SCA.dialog({
            type: "warning",
            title: "Ganti Template?",
            message: "Desain Anda saat ini akan dihapus dan diganti dengan template baru.",
            confirmText: "Ya, Ganti",
            cancelText: "Batal",
            showCancel: true,
        }).then((confirmed) => {
            if (confirmed) {
                this.executeTemplate(name);
            }
        });
    },

    executeTemplate(name) {
        CoverBuilder.canvas.clear();
        CoverBuilder.canvas.setBackgroundColor('#ffffff', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));

        switch (name) {
            case 'modern':
                CoverBuilder.canvas.setBackgroundColor('#071528', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                // Decorative circles
                const c1 = new fabric.Circle({ radius: 100, fill: 'rgba(0, 200, 255, 0.05)', left: -50, top: -50 });
                const c2 = new fabric.Circle({ radius: 150, fill: 'rgba(0, 200, 255, 0.03)', left: 400, top: 600 });
                CoverBuilder.canvas.add(c1, c2);

                const lineMod = new fabric.Rect({ left: 100, top: 210, width: 400, height: 4, fill: '#00c8ff' });
                CoverBuilder.canvas.add(lineMod);
                CoverBuilder.canvas.centerObjectH(lineMod);
                
                const titleMod = new fabric.Textbox('LAPORAN PROYEK', { left: 50, top: 130, width: 500, fontSize: 38, fontWeight: 'bold', fill: '#ffffff', textAlign: 'center', charSpacing: 100 });
                CoverBuilder.canvas.add(titleMod);
                CoverBuilder.canvas.centerObjectH(titleMod);

                const subMod = new fabric.Textbox('Sistem Manajemen Proyek Terintegrasi\nDevelopment Phase 2026', { left: 50, top: 240, width: 500, fontSize: 18, fill: '#aaaaaa', textAlign: 'center', lineHeight: 1.4 });
                CoverBuilder.canvas.add(subMod);
                CoverBuilder.canvas.centerObjectH(subMod);
                break;

            case 'minimalist':
                const titleMin = new fabric.Textbox('PROJECT\nREPORT', { left: 60, top: 100, width: 500, fontSize: 64, fontWeight: '900', fill: '#1a1a1a', textAlign: 'left', lineHeight: 0.9 });
                CoverBuilder.canvas.add(titleMin);
                const lineMin = new fabric.Rect({ left: 60, top: 230, width: 80, height: 8, fill: '#1a1a1a' });
                CoverBuilder.canvas.add(lineMin);
                
                const dateMin = new fabric.Textbox('MAY 2026 / VOL 01', { left: 60, top: 750, width: 400, fontSize: 14, fontWeight: 'bold', fill: '#999', charSpacing: 200 });
                CoverBuilder.canvas.add(dateMin);
                break;

            case 'elegant':
                CoverBuilder.canvas.setBackgroundColor('#1a1a1a', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const topBar = new fabric.Rect({ left: 0, top: 0, width: 595, height: 320, fill: '#252525' });
                CoverBuilder.canvas.add(topBar);
                
                const titleEle = new fabric.Textbox('ANNUAL REPORT', { left: 50, top: 420, width: 500, fontSize: 44, fontWeight: '300', fill: '#ffffff', textAlign: 'center', charSpacing: 300 });
                CoverBuilder.canvas.add(titleEle);
                CoverBuilder.canvas.centerObjectH(titleEle);

                const dividerEle = new fabric.Rect({ left: 0, top: 480, width: 150, height: 1, fill: '#555' });
                CoverBuilder.canvas.add(dividerEle);
                CoverBuilder.canvas.centerObjectH(dividerEle);
                break;

            case 'corporate':
                const sideBar = new fabric.Rect({ left: 0, top: 0, width: 70, height: 842, fill: '#0088cc' });
                const accentBar = new fabric.Rect({ left: 70, top: 0, width: 10, height: 842, fill: '#e1f5fe' });
                CoverBuilder.canvas.add(sideBar, accentBar);
                
                const titleCor = new fabric.Textbox('DOKUMEN TEKNIS', { left: 120, top: 120, width: 450, fontSize: 34, fontWeight: 'bold', fill: '#071528' });
                CoverBuilder.canvas.add(titleCor);
                
                const descCor = new fabric.Textbox('Dokumentasi lengkap pengembangan sistem, arsitektur database, dan panduan integrasi API.', { left: 120, top: 180, width: 400, fontSize: 16, fill: '#666', lineHeight: 1.5 });
                CoverBuilder.canvas.add(descCor);
                break;

            case 'creative':
                CoverBuilder.canvas.setBackgroundColor('#fdfdfd', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                // Diagonal accent
                const diag = new fabric.Polygon([
                    { x: 0, y: 0 }, { x: 595, y: 0 }, { x: 595, y: 400 }, { x: 0, y: 600 }
                ], { fill: '#ff5f00', strokeWidth: 0, opacity: 0.9 });
                CoverBuilder.canvas.add(diag);

                const titleCre = new fabric.Textbox('CREATIVE\nPROPOSAL', { left: 50, top: 100, width: 500, fontSize: 58, fontWeight: 'bold', fill: '#ffffff', textAlign: 'left', lineHeight: 0.9 });
                CoverBuilder.canvas.add(titleCre);
                break;

            case 'luxury':
                CoverBuilder.canvas.setBackgroundColor('#000000', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const borderLux = new fabric.Rect({ left: 20, top: 20, width: 555, height: 802, fill: 'transparent', stroke: '#d4af37', strokeWidth: 2 });
                CoverBuilder.canvas.add(borderLux);
                const titleLux = new fabric.Textbox('OFFICIAL REPORT', { left: 50, top: 380, width: 500, fontSize: 32, fontWeight: 'bold', fill: '#d4af37', textAlign: 'center', charSpacing: 400 });
                CoverBuilder.canvas.add(titleLux);
                CoverBuilder.canvas.centerObjectH(titleLux);
                break;

            case 'futuristic':
                CoverBuilder.canvas.setBackgroundColor('#000000', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const neonB = new fabric.Rect({ left: 30, top: 30, width: 535, height: 782, fill: 'transparent', stroke: '#00ff00', strokeWidth: 1, shadow: '0 0 10px #00ff00' });
                CoverBuilder.canvas.add(neonB);
                const titleFut = new fabric.Textbox('SYSTEM_CORE_v2.0', { left: 50, top: 100, width: 500, fontSize: 40, fontWeight: 'bold', fill: '#00ff00', textAlign: 'center', shadow: '0 0 5px #00ff00' });
                CoverBuilder.canvas.add(titleFut);
                break;

            case 'retro':
                CoverBuilder.canvas.setBackgroundColor('#f4a261', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const circleR = new fabric.Circle({ radius: 200, fill: '#e76f51', left: -100, top: -100 });
                CoverBuilder.canvas.add(circleR);
                const titleRet = new fabric.Textbox('THE ARCHIVE', { left: 50, top: 400, width: 500, fontSize: 60, fontWeight: 'bold', fill: '#264653', textAlign: 'center' });
                CoverBuilder.canvas.add(titleRet);
                break;

            case 'geometric':
                const g1 = new fabric.Polygon([{x:0,y:0},{x:300,y:0},{x:0,y:300}], { fill: '#333', left: 0, top: 0 });
                const g2 = new fabric.Rect({ width: 200, height: 200, fill: '#666', left: 400, top: 600, angle: 45 });
                CoverBuilder.canvas.add(g1, g2);
                const titleGeo = new fabric.Textbox('GEOMETRIC\nPROPOSAL', { left: 50, top: 350, width: 500, fontSize: 48, fontWeight: 'bold', fill: '#222' });
                CoverBuilder.canvas.add(titleGeo);
                break;

            case 'blueprint':
                CoverBuilder.canvas.setBackgroundColor('#004a99', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                // Grid lines
                for(let i=0; i<600; i+=50) {
                    CoverBuilder.canvas.add(new fabric.Rect({ left: i, top: 0, width: 1, height: 842, fill: 'rgba(255,255,255,0.1)' }));
                    CoverBuilder.canvas.add(new fabric.Rect({ left: 0, top: i, width: 595, height: 1, fill: 'rgba(255,255,255,0.1)' }));
                }
                const titleBlu = new fabric.Textbox('BLUEPRINT SPEC', { left: 50, top: 100, width: 500, fontSize: 32, fill: '#ffffff', backgroundColor: '#003d80' });
                CoverBuilder.canvas.add(titleBlu);
                break;

            case 'soft':
                CoverBuilder.canvas.setBackgroundColor('#f8edeb', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const s1 = new fabric.Circle({ radius: 150, fill: '#ffd7ba', left: 300, top: -50 });
                CoverBuilder.canvas.add(s1);
                const titleSof = new fabric.Textbox('Monthly Journal', { left: 50, top: 400, width: 500, fontSize: 38, fill: '#5e503f', textAlign: 'center' });
                CoverBuilder.canvas.add(titleSof);
                break;

            case 'midnight':
                CoverBuilder.canvas.setBackgroundColor('#121212', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const barMid = new fabric.Rect({ left: 540, top: 0, width: 55, height: 842, fill: '#3d3d3d' });
                CoverBuilder.canvas.add(barMid);
                const titleMid = new fabric.Textbox('DARK\nMATTER', { left: 50, top: 100, width: 400, fontSize: 72, fontWeight: 'bold', fill: '#ffffff', lineHeight: 0.8 });
                CoverBuilder.canvas.add(titleMid);
                break;

            case 'impact':
                CoverBuilder.canvas.setBackgroundColor('#ffea00', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const titleImp = new fabric.Textbox('BOOM!', { left: 20, top: 300, width: 555, fontSize: 120, fontWeight: '900', fill: '#000', textAlign: 'center', skewX: -10 });
                CoverBuilder.canvas.add(titleImp);
                break;

            case 'scientific':
                const lineS1 = new fabric.Rect({ left: 50, top: 50, width: 495, height: 1, fill: '#000' });
                const lineS2 = new fabric.Rect({ left: 50, top: 792, width: 495, height: 1, fill: '#000' });
                CoverBuilder.canvas.add(lineS1, lineS2);
                const titleSci = new fabric.Textbox('DATA_ANALYSIS_V.01', { left: 50, top: 100, width: 500, fontSize: 24, fontWeight: 'bold', fill: '#000', charSpacing: 100 });
                CoverBuilder.canvas.add(titleSci);
                break;

            case 'abstract':
                CoverBuilder.canvas.setBackgroundColor('#6d597a', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const tri = new fabric.Polygon([{x:0,y:0},{x:200,y:0},{x:100,y:200}], { fill: '#b56576', left: 200, top: 300, opacity: 0.6 });
                CoverBuilder.canvas.add(tri);
                const titleAbs = new fabric.Textbox('ABSTRACT\nTHOUGHTS', { left: 50, top: 100, width: 500, fontSize: 44, fill: '#ffffff', textAlign: 'right' });
                CoverBuilder.canvas.add(titleAbs);
                break;

            case 'ocean':
                CoverBuilder.canvas.setBackgroundColor('#0077b6', CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
                const wave = new fabric.Ellipse({ rx: 400, ry: 200, fill: '#00b4d8', left: -100, top: 600 });
                CoverBuilder.canvas.add(wave);
                const titleOce = new fabric.Textbox('DEEP WATER', { left: 50, top: 150, width: 500, fontSize: 48, fontWeight: 'bold', fill: '#caf0f8', textAlign: 'center' });
                CoverBuilder.canvas.add(titleOce);
                break;
        }
        CoverBuilder.canvas.renderAll();
        SCA.toast({ type: 'success', title: 'Template Berhasil!', message: 'Silakan sesuaikan teks sesuai kebutuhan.' });
    },

    addText(text, isHeader = false) {
        const textObj = new fabric.Textbox(text, {
            left: 50,
            top: isHeader ? 100 : 250,
            width: 500,
            fontFamily: 'Helvetica, Arial, sans-serif',
            fontSize: isHeader ? 36 : 20,
            fontWeight: isHeader ? 'bold' : 'normal',
            fill: '#333333',
            textAlign: 'center',
            editable: true,
            selectable: true
        });
        
        if(isHeader) CoverBuilder.canvas.centerObjectH(textObj);
        
        CoverBuilder.canvas.add(textObj);
        CoverBuilder.canvas.setActiveObject(textObj);
        CoverBuilder.canvas.renderAll();
    },

    handleImageUpload(input) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (f) => {
            fabric.Image.fromURL(f.target.result, (img) => {
                img.scaleToWidth(200);
                CoverBuilder.canvas.add(img);
                CoverBuilder.canvas.centerObject(img);
                CoverBuilder.canvas.setActiveObject(img);
                CoverBuilder.canvas.renderAll();
            });
        };
        reader.readAsDataURL(file);
    },

    setBgColor(color) {
        CoverBuilder.canvas.setBackgroundColor(color, CoverBuilder.canvas.renderAll.bind(CoverBuilder.canvas));
    },

    setObjectColor(color) {
        const active = CoverBuilder.canvas.getActiveObject();
        if (active) {
            active.set('fill', color);
            CoverBuilder.canvas.renderAll();
        }
    },

    deleteSelected() {
        const active = CoverBuilder.canvas.getActiveObject();
        if (active) {
            CoverBuilder.canvas.remove(active);
            CoverBuilder.canvas.discardActiveSelection();
            CoverBuilder.canvas.renderAll();
        }
    },

    save() {
        CoverBuilder.canvas.discardActiveObject().renderAll();
        CoverBuilder.coverImage = CoverBuilder.canvas.toDataURL({
            format: 'png',
            multiplier: 2 
        });
        CoverBuilder.modal.hide();
        SCA.toast({ type: 'success', title: 'Cover Siap!', message: 'Laporan akan menggunakan cover kustom ini.' });
        $('.btn-outline-info').addClass('btn-info text-white').removeClass('btn-outline-info');
    }
};

$(document).ready(() => CoverBuilder.init());
