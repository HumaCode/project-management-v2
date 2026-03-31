<?php

namespace App\Http\Requests\RoleManagement\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    /**
     * Izinkan user untuk melakukan request ini.
     * Ubah ke true jika Anda tidak menggunakan policy khusus di sini.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create roles');
    }

    /**
     * Aturan validasi yang diterapkan pada request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles,slug'],
            'description' => ['nullable', 'string'],
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
            'description' => 'Deskripsi',
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
            'unique' => ':attribute sudah digunakan, silakan pilih nama lain.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }
}
