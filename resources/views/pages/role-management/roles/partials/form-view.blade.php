<div class="m-section" style="border-top:none;padding-top:0;margin-top:0">Identitas Role</div>
<div class="row g-3 mt-0">
    <div class="col-12">
        <label class="fm-lbl">Nama Role <span class="req">*</span></label>
        <input type="text" class="fmi" id="role_name" name="name" value="{{ $data->name }}"
            placeholder="Contoh: Manager, Admin, dsb." required />
    </div>

    <div class="col-12 col-sm-4">
        <label class="fm-lbl">Slug <span class="req">*</span></label>
        <input type="text" class="fmi" id="role_slug" name="slug" value="{{ $data->slug }}"
            placeholder="otomatis-dari-nama" readonly required />
        <div class="form-note">Slug otomatis.</div>
    </div>

    <div class="col-12 col-sm-4">
        <label class="fm-lbl">Tipe Role <span class="req">*</span></label>
        <select class="fmsel" name="type_role" required>
            <option value="custom" {{ $data->type_role === 'custom' || !$data->id ? 'selected' : '' }}>custom</option>
            <option value="system" {{ $data->type_role === 'system' ? 'selected' : '' }}>system</option>
        </select>
    </div>

    <div class="col-12 col-sm-4">
        <label class="fm-lbl">Guard <span class="req">*</span></label>
        <select class="fmsel" name="guard_name" required>
            <option value="web" {{ $data->guard_name === 'web' || !$data->id ? 'selected' : '' }}>web</option>
            <option value="api" {{ $data->guard_name === 'api' ? 'selected' : '' }}>api</option>
        </select>
    </div>

    <div class="col-12">
        <label class="fm-lbl">Deskripsi</label>
        <textarea class="fmta" name="description" placeholder="Jelaskan fungsi dari role ini..." rows="2">{{ $data->description }}</textarea>
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Status</label>
        <select class="fmsel" name="is_active">
            <option value="1" {{ $data->is_active == '1' || !$data->id ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $data->is_active == '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>

    <div class="col-12 col-sm-6">
        <label class="fm-lbl">Level Prioritas</label>
        <select class="fmsel" name="priority">
            <option value="1" {{ $data->priority == 1 ? 'selected' : '' }}>1 — Tertinggi</option>
            <option value="5" {{ $data->priority == 5 || !$data->id ? 'selected' : '' }}>5 — Menengah</option>
            <option value="10" {{ $data->priority == 10 ? 'selected' : '' }}>10 — Terendah</option>
        </select>
    </div>
</div>

<div class="m-section">Warna Role</div>
<div class="form-note" style="margin-bottom:8px">Pilih warna identitas role untuk tampilan badge.</div>

{{-- Hidden input untuk menyimpan warna yang dipilih --}}
<input type="hidden" name="color" id="role_color" value="{{ $data->color ?? '#ff4d6d' }}">

<div class="color-row">
    @php
        $colors = [
            '#ff4d6d' => 'Merah',
            '#f59e0b' => 'Amber',
            '#00c8ff' => 'Cyan',
            '#00e5a0' => 'Hijau',
            '#a78bfa' => 'Ungu',
            '#fb923c' => 'Oranye',
            '#38bdf8' => 'Biru Muda',
            '#e879f9' => 'Pink',
        ];
        $selectedColor = $data->color ?? '#ff4d6d';
    @endphp

    @foreach ($colors as $hex => $label)
        <div class="csw {{ $selectedColor === $hex ? 'sel' : '' }}" style="background:{{ $hex }} !important; display: inline-block; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; margin-right: 8px; border: 2px solid transparent;"
            title="{{ $label }}" data-color="{{ $hex }}"></div>
    @endforeach
</div>

<script>
    $(document).ready(function() {
        // Klik swatch warna
        $('.color-row .csw').on('click', function() {
            $('.color-row .csw').removeClass('sel').css('border-color', 'transparent');
            $(this).addClass('sel').css('border-color', '#fff');
            $('#role_color').val($(this).data('color'));
        });

        // Auto-slug logic
        $('#role_name').on('input', function() {
            let slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            $('#role_slug').val(slug);
        });
    });
</script>
