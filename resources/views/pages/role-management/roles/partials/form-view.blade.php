<div class="mt-0 row g-3">
    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Nama Permission <span class="req">*</span></label>
        <input type="text" id="name" class="fmi" name="name" value="{{ $data->name }}"
            placeholder="Contoh : read menu" />
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Guard <span class="req">*</span></label>
        <select class="fmsel" name="guard_name">
            <option value="">-- Pilih --</option>
            <option value="web" {{ $data->guard_name === 'web' ? 'selected' : '' }}>web</option>
            <option value="api" {{ $data->guard_name === 'api' ? 'selected' : '' }}>api</option>
        </select>
    </div>

</div>
