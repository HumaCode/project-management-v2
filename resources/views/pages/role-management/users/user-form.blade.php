@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;

@endphp

<x-form.modal title="Role" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    {{-- Cek tipe akses TERLEBIH DAHULU --}}
    @if (($type ?? null) === 'akses')
        @include('pages.role-management.roles.partials.form-akses-view')

        {{-- Jika bukan akses, tapi memiliki action (Berarti Tambah/Edit role biasa) --}}
    @elseif ($action ?? null)
        @include('pages.role-management.roles.partials.form-view')

        {{-- (Opsional) Jika action null, biasanya untuk mode Show/Detail --}}
    @else
        {{-- @include('pages.role-management.roles.partials.show-view') --}}
    @endif

</x-form.modal>
