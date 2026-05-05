@php
    $isEdit = !empty($data->id);
    $isCreate = !$isEdit;
@endphp

<x-form.modal title="User" :action="$action ?? null" :is-edit="$isEdit" :type="$type ?? null">

    @if ($action ?? null)
        @include('pages.role-management.users.partials.form-view')
    @endif

</x-form.modal>
