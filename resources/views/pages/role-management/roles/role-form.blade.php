@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;

@endphp

<x-form.modal title="Role" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    @if ($action ?? null)
        @include('pages.role-management.roles.partials.form-view')
    @else
        (detail)
    @endif

</x-form.modal>
