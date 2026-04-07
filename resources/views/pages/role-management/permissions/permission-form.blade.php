@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;

@endphp

<x-form.modal title="Permission" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    {{-- Cek tipe akses TERLEBIH DAHULU --}}
    @if ($action ?? null)
        @include('pages.role-management.roles.partials.form-view')
    @endif

</x-form.modal>
