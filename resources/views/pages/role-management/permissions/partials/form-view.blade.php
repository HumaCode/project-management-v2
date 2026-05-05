<div class="m-section" style="border-top:none;padding-top:0;margin-top:0">Identitas Permission</div>
<div class="mt-0 row g-3">
    <div class="col-12 col-sm-8">
        <label class="fm-lbl">Nama Permission <span class="req">*</span></label>
        <input type="text" id="permission_name" class="fmi" name="name" value="{{ $data->name }}"
            placeholder="Contoh: create-users, edit-articles..." required />
        <div class="form-note">Gunakan format slug (kecil, tanda hubung).</div>
    </div>

    <div class="col-12 col-sm-4">
        <label class="fm-lbl">Guard <span class="req">*</span></label>
        <select class="fmsel" name="guard_name" required>
            <option value="web" {{ $data->guard_name === 'web' || !$data->id ? 'selected' : '' }}>web</option>
            <option value="api" {{ $data->guard_name === 'api' ? 'selected' : '' }}>api</option>
        </select>
    </div>
</div>
