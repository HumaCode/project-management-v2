<?php

namespace App\Http\Requests\RoleManagement\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create users');
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Nama Lengkap',
            'username' => 'Username',
            'email'    => 'Email',
            'phone'    => 'Nomor Telepon',
            'password' => 'Password',
            'role'     => 'Role',
        ];
    }

    public function messages(): array
    {
        return [
            'required'  => ':attribute wajib diisi.',
            'unique'    => ':attribute sudah digunakan.',
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'min'       => ':attribute minimal :min karakter.',
        ];
    }
}
