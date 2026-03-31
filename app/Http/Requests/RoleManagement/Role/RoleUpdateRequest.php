<?php

namespace App\Http\Requests\RoleManagement\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Pastikan unik, tapi abaikan ID milik role yang sedang di-update
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'slug' => [
                'required',
                'string',
                'max:255',
                // Pastikan unik, tapi abaikan ID milik role yang sedang di-update
                Rule::unique('roles', 'slug')->ignore($roleId),
            ],
            'type_role' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'in:0,1'],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Custom attributes untuk pesan error yang lebih user-friendly.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Role',
            'slug' => 'Slug',
            'type_role' => 'Tipe Role',
            'description' => 'Deskripsi',
            'is_active' => 'Status Aktif',
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
            'name.unique' => 'Nama role sudah digunakan oleh role lain.',
            'name.required' => 'Nama role tidak boleh kosong.',
            'slug.unique' => 'Slug role sudah digunakan oleh role lain.',
            'slug.required' => 'Slug role tidak boleh kosong.',
            'unique' => ':attribute sudah digunakan, silakan pilih nama lain.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }
}
