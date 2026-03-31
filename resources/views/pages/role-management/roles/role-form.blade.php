@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;

@endphp

<x-form.modal title="Role" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    @if ($action ?? null)
        @include('pages.role-management.roles.partials.form-view')
    @elseif($action !== null && $type === 'show')
        @include('pages.role-management.roles.partials.form-akses-view')
    @endif

</x-form.modal>
