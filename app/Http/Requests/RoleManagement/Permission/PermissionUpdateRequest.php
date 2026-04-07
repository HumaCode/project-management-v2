<?php

namespace App\Http\Requests\RoleManagement\Permission;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan unik, tapi abaikan ID milik role yang sedang di-update
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom attributes untuk pesan error yang lebih user-friendly.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Permission',
            'guard_name' => 'Guard Name',
        ];
    }

    /**
     * Custom messages (Opsional) jika ingin pesan spesifik dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'name.unique' => 'Nama permission sudah digunakan oleh permission lain.',
            'name.required' => 'Nama permission tidak boleh kosong.',
            'guard_name.required' => 'Guard name tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan, silakan pilih nama lain.',
        ];
    }
}
