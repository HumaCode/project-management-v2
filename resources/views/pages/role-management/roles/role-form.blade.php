@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;

@endphp

<x-form.modal title="Role" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    @if ($action ?? null)
        <div class="row g-3 mt-0">
            <div class="col-12">
                <label class="fm-lbl">Nama Role <span class="req">*</span></label>
                <input type="text" id="role_name" class="fmi" name="name"
                    placeholder="Contoh: Supervisor, QA, Reviewer..." />
            </div>
            <div class="col-12 col-sm-6">
                <label class="fm-lbl">Slug <span class="req">*</span></label>
                <input type="text" id="role_slug" class="fmi" name="slug"
                    placeholder="otomatis setelah mengisi nama role" readonly />
            </div>
            <div class="col-12 col-sm-6">
                <label class="fm-lbl">Guard <span class="req">*</span></label>
                <select class="fmsel" name="guard_name">
                    <option value="">-- Pilih --</option>
                    <option value="web" selected>web</option>
                    <option value="api">api</option>
                </select>
            </div>
            <div class="col-12">
                <label class="fm-lbl">Deskripsi</label>
                <textarea class="fmta" name="description" placeholder="Jelaskan fungsi dan tanggung jawab role ini..."></textarea>
            </div>
            <div class="col-12 col-sm-6">
                <label class="fm-lbl">Status</label>
                <select class="fmsel" name="is_active">
                    <option value="">-- Pilih Status --</option>
                    <option value="1" selected>Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="col-12 col-sm-6">
                <label class="fm-lbl">Tipe Role</label>
                <select class="fmsel" name="type_role">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="system" selected>System</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
        </div>
    @else
        (detail)
    @endif

</x-form.modal>
