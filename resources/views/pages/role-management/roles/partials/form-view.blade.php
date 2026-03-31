<div class="mt-0 row g-3">
    <div class="col-12">
        <label class="fm-lbl">Nama Role <span class="req">*</span></label>
        <input type="text" id="role_name" class="fmi" name="name" value="{{ $data->name }}"
            placeholder="Contoh: Supervisor, QA, Reviewer..." />
    </div>
    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Slug <span class="req">*</span></label>
        <input type="text" id="role_slug" class="fmi" name="slug" value="{{ $data->slug }}"
            placeholder="otomatis setelah mengisi nama role" readonly />
    </div>
    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Guard <span class="req">*</span></label>
        <select class="fmsel" name="guard_name">
            <option value="">-- Pilih --</option>
            <option value="web" {{ $data->guard_name === 'web' ? 'selected' : '' }}>web</option>
            <option value="api" {{ $data->guard_name === 'api' ? 'selected' : '' }}>api</option>
        </select>
    </div>
    <div class="col-12">
        <label class="fm-lbl">Deskripsi</label>
        <textarea class="fmta" name="description" placeholder="Jelaskan fungsi dan tanggung jawab role ini...">{{ $data->description }}</textarea>
    </div>

    @if (request()->routeIs('roles.edit'))
        <div class="col-12 col-sm-6">
            <label class="fm-lbl">Status</label>
            <select class="fmsel" name="is_active">
                <option value="">-- Pilih Status --</option>
                <option value="1" {{ $data->is_active === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $data->is_active === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-12 col-sm-6">
            <label class="fm-lbl">Tipe Role</label>
            <select class="fmsel" name="type_role">
                <option value="">-- Pilih Tipe --</option>
                <option value="system" {{ $data->type_role === 'system' ? 'selected' : '' }}>System</option>
                <option value="custom" {{ $data->type_role === 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>
    @endif

</div>
