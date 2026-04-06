<?php

namespace App\Http\Requests\RoleManagement\Role;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AksesRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // UBAH JADI TRUE.
        // Authorization (Gate) sudah kamu tangani di dalam Controller method aksesEdit()
        // return true;
        return auth()->check() && auth()->user()->can('akses roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // permissions boleh kosong (jika admin menghapus semua centang), tapi jika ada, harus berupa array
            'permissions'   => ['nullable', 'array'],

            // Setiap isi dari array permissions harus berupa string dan ADA di tabel permissions kolom name
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'permissions'   => 'Hak Akses',
            'permissions.*' => 'Item Hak Akses'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'permissions.array' => 'Format :attribute tidak valid.',
            'permissions.*.exists' => ':attribute yang dipilih tidak ditemukan di sistem.',
        ];
    }
}
