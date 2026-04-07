<?php

namespace App\Http\Requests\RoleManagement\Permission;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
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
            'unique' => ':attribute sudah digunakan, silakan pilih nama lain.',
        ];
    }
}
