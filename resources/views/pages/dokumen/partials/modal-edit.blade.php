        <!-- Modal Edit -->
        <div class="modal fade m-dark m-cyan" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <form class="modal-content" id="formEditDokumen" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="m-hd">
                        <h5 class="m-hd-title"><i class="bi bi-pencil-square"></i> Edit Dokumen</h5>
                        <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                    </div>
                    <div class="m-bd">
                        <!-- Drop zone -->
                        <div class="drop-zone" id="dropZoneEdit">
                            <div id="previewContainerEdit" style="display:none; margin-bottom:12px; margin-top: 5px;">
                                <div style="position: relative; display: inline-block; max-width: 100%;">
                                    <img id="imagePreviewEdit" src="" style="max-height:150px; max-width: 100%; border-radius:12px; border: 2px solid var(--cyan, #0284c7); box-shadow: 0 8px 24px rgba(0,0,0,0.3); display: block;">
                                    <button type="button" id="btnRemovePreviewEdit" title="Hapus Gambar" style="position:absolute; top:8px; right:8px; background:#ef4444; color:#ffffff !important; border:2px solid #ffffff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:20; box-shadow: 0 4px 10px rgba(0,0,0,0.4); padding:0; outline:none;">
                                        <i class="bi bi-x-lg" style="color:#ffffff !important; font-size:14px; font-weight:bold; display:flex; align-items:center; justify-content:center; width:100%; height:100%; margin:0;"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="dropZoneContentEdit">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <div class="dt">Ganti file di sini</div>
                                <div class="ds">atau <span style="color:var(--cyan);cursor:pointer">klik untuk memilih file baru</span></div>
                                <div class="dk" id="fileNameEdit">Biarkan kosong jika tidak ingin mengubah file</div>
                            </div>
                        </div>
                        <input type="file" name="file" id="fileInputEdit" style="display:none" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">TIPE DOKUMEN<span class="req">*</span></label>
                                    <select class="select2" id="sel2TypeEdit" name="type" style="width:100%">
                                        <option value="file">File Tunggal (Upload PDF, DOCX, dll)</option>
                                        <option value="article">Koleksi / Manual Book (Documentation Builder)</option>
                                        <option value="code">Dokumentasi Koding (Snippet & Penjelasan)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">NAMA DOKUMEN<span class="req">*</span></label>
                                    <input type="text" name="nama" id="editNama" class="fmi" placeholder="Masukkan nama dokumen..." required/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">VERSI DOKUMEN</label>
                                    <input type="text" name="versi" id="editVersi" class="fmi" placeholder="Contoh: v1.0, v2.3..."/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">KATEGORI<span class="req">*</span></label>
                                    <select class="select2" id="sel2KatEdit" name="kategori" style="width:100%" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->slug }}" data-icon="{{ $cat->icon }}" data-color="{{ $cat->color }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">PROJECT TERKAIT<span class="req">*</span></label>
                                    <select class="select2" id="sel2ProjEdit" name="project_id" style="width:100%" required>
                                        <option value="">-- Pilih Project --</option>
                                        @foreach($projects as $pj)
                                            <option value="{{ $pj->id }}">{{ $pj->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">DIUNGGAH OLEH<span class="req">*</span></label>
                                    <select class="select2" id="sel2UserEdit" name="user_id" style="width:100%" required>
                                        <option value="">-- Pilih Pengguna --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">TANGGAL UPLOAD</label>
                                    <x-datepicker name="tanggal_upload" id="editTanggal" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="fm-row mb-0">
                                    <label class="fm-lbl">KETERANGAN</label>
                                    <textarea name="keterangan" id="editKeterangan" class="fmta" placeholder="Deskripsi singkat dokumen ini (opsional)..." style="height: 80px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-ft">
                        <button type="button" class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                        <button type="submit" class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Perubahan</span></button>
                    </div>
                </form>
            </div>
        </div>
