/**
 * Cover Builder Module - REVISED VERSION
 * Memastikan Fokus Keyboard & Pencegahan Navigasi Backspace
 */
const CoverBuilder = {
    canvas: null,
    modal: null,
    coverImage: null,

    init() {
        if (!document.getElementById('coverCanvas')) return;
        console.log('Cover Builder Initialized');
        
        this.modal = new bootstrap.Modal(document.getElementById('modalCover'));
        this.initCanvas();
        this.initGlobalEvents();
    },

    initCanvas() {
        this.canvas = new fabric.Canvas('coverCanvas', {
            width: 595,
            height: 842,
            backgroundColor: '#ffffff',
            preserveObjectStacking: true
        });

        // Event: Menampilkan control jika objek dipilih
        this.canvas.on('selection:created', (e) => this.updateControls(e));
        this.canvas.on('selection:updated', (e) => this.updateControls(e));
        this.canvas.on('selection:cleared', () => {
            $('#objectControls, #textSpecificControls').hide();
        });

        // Klik untuk edit (Better handling)
        this.canvas.on('mouse:down', (options) => {
            if (options.target && options.target.type === 'textbox') {
                if (this.canvas.getActiveObject() === options.target) {
                    options.target.enterEditing();
                    this.canvas.renderAll();
                }
            }
        });

        this.addText('JUDUL LAPORAN PROYEK', true);
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
        // PENCEGAHAN BACKSPACE AGAR TIDAK NAVIGASI BACK
        $(window).on('keydown', (e) => {
            if (!$('#modalCover').hasClass('show')) return;

            const activeObject = this.canvas.getActiveObject();
            
            // Jika tombol Backspace (8) ditekan
            if (e.keyCode === 8) {
                // Jika tidak sedang mengedit teks, hapus objek & cegah navigasi
                if (!activeObject || !activeObject.isEditing) {
                    e.preventDefault();
                    if (activeObject) this.deleteSelected();
                }
                // Jika sedang mengedit teks, biarkan Backspace bekerja di dalam teks
            }

            // Tombol Delete (46)
            if (e.keyCode === 46 && activeObject && !activeObject.isEditing) {
                this.deleteSelected();
            }
        });
    },

    open() {
        this.modal.show();
        setTimeout(() => {
            this.canvas.calcOffset();
            this.canvas.renderAll();
            // Paksa fokus ke canvas
            const el = document.querySelector('.upper-canvas');
            if (el) el.focus();
        }, 500);
    },

    editText() {
        const active = this.canvas.getActiveObject();
        if (active && active.type === 'textbox') {
            active.enterEditing();
            active.selectAll();
            this.canvas.renderAll();
        }
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
            selectable: true,
            hasControls: true
        });
        
        if(isHeader) this.canvas.centerObjectH(textObj);
        
        this.canvas.add(textObj);
        this.canvas.setActiveObject(textObj);
        this.canvas.renderAll();
    },

    handleImageUpload(input) {
        const file = input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (f) => {
            fabric.Image.fromURL(f.target.result, (img) => {
                img.scaleToWidth(200);
                this.canvas.add(img);
                this.canvas.centerObject(img);
                this.canvas.setActiveObject(img);
                this.canvas.renderAll();
            });
        };
        reader.readAsDataURL(file);
    },

    setBgColor(color) {
        this.canvas.setBackgroundColor(color, this.canvas.renderAll.bind(this.canvas));
    },

    setObjectColor(color) {
        const active = this.canvas.getActiveObject();
        if (active) {
            active.set('fill', color);
            this.canvas.renderAll();
        }
    },

    deleteSelected() {
        const active = this.canvas.getActiveObject();
        if (active) {
            this.canvas.remove(active);
            this.canvas.discardActiveSelection();
            this.canvas.renderAll();
        }
    },

    save() {
        this.canvas.discardActiveObject().renderAll();
        this.coverImage = this.canvas.toDataURL({
            format: 'png',
            multiplier: 2 
        });
        this.modal.hide();
        SCA.toast({ type: 'success', title: 'Cover Siap!', message: 'Laporan akan menggunakan cover kustom ini.' });
        $('.btn-outline-info').addClass('btn-info text-white').removeClass('btn-outline-info');
    }
};

$(document).ready(() => CoverBuilder.init());
