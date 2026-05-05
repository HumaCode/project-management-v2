<div class="m-section" style="border-top:none;padding-top:0;margin-top:0">Identitas Pengguna</div>
<div class="row g-3 mt-0">
    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Nama Lengkap <span class="req">*</span></label>
        <input type="text" class="fmi" name="name" value="{{ $data->name }}"
            placeholder="Contoh: Ahmad Fauzi" required />
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Username <span class="req">*</span></label>
        <input type="text" class="fmi" name="username" value="{{ $data->username }}"
            placeholder="fauzi_ahmad" required />
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Email <span class="req">*</span></label>
        <input type="email" class="fmi" name="email" value="{{ $data->email }}"
            placeholder="fauzi@example.com" required />
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Nomor Telepon</label>
        <input type="text" class="fmi" name="phone" value="{{ $data->phone }}"
            placeholder="0812XXXXXXXX" />
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Role <span class="req">*</span></label>
        <select class="fmsel" name="role" required>
            <option value="">-- Pilih Role --</option>
            @foreach ($rolesActive as $role)
                <option value="{{ $role->name }}" {{ $data->hasRole($role->name) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if (isset($isEdit) && $isEdit)
        <div class="col-12 col-sm-6">
            <label class="fm-lbl">Status Akun <span class="req">*</span></label>
            <select class="fmsel" name="is_active" required>
                <option value="1" {{ $data->is_active == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $data->is_active == '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    @endif

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Password {{ isset($isEdit) && $isEdit ? '(Kosongkan jika tidak diubah)' : '*' }}</label>
        <div class="pass-group">
            <input type="password" class="fmi" name="password" id="password" {{ isset($isEdit) && $isEdit ? '' : 'required' }} />
            <button type="button" class="pass-toggle" onclick="togglePassword('password')">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Konfirmasi Password {{ isset($isEdit) && $isEdit ? '(Kosongkan jika tidak diubah)' : '*' }}</label>
        <div class="pass-group">
            <input type="password" class="fmi" name="password_confirmation" id="password_confirmation" {{ isset($isEdit) && $isEdit ? '' : 'required' }} />
            <button type="button" class="pass-toggle" onclick="togglePassword('password_confirmation')">
                <i class="bi bi-eye-slash"></i>
            </button>
        </div>
    </div>
</div>
