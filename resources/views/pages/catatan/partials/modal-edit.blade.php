<!-- MODAL: EDIT -->
<div class="modal fade m-dark m-cyan" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="form_edit" action="" method="POST">
                @method('PUT')
                <div class="m-hd">
                    <h5 class="m-hd-title"><i class="bi bi-pencil-square"></i> Edit Catatan</h5>
                    <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="m-bd">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="fm-lbl">Judul Catatan<span class="req">*</span></label>
                            <input type="text" name="title" id="edit_title" class="fmi" placeholder="Masukkan judul catatan..." required />
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="fm-lbl">Kategori<span class="req">*</span></label>
                            <select name="category" id="edit_category" class="fmsel" required>
                                <option value="">-- Pilih --</option>
                                <option value="Personal">Personal</option>
                                <option value="Project">Project</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Technical">Technical</option>
                                <option value="Task">Task</option>
                                <option value="Penting">Penting</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="fm-lbl">Prioritas<span class="req">*</span></label>
                            <select name="priority" id="edit_priority" class="fmsel" required>
                                <option value="">-- Pilih --</option>
                                <option value="tinggi">Tinggi</option>
                                <option value="sedang">Sedang</option>
                                <option value="rendah">Rendah</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="fm-lbl">Project Terkait</label>
                            <select name="project_id" id="edit_project_id" class="fmsel select2">
                                <option value="">-- Tidak ada --</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <label class="fm-lbl">Isi Catatan<span class="req">*</span></label>
                    <div class="ck-wrap">
                        <textarea name="content" id="ckEdit"></textarea>
                    </div>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batal</button>
                    <button type="submit" class="btn-msave"><span><i class="bi bi-floppy-fill"></i> Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
