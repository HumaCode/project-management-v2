<?php

namespace App\Http\Requests\RoleManagement\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update users');
    }

    public function rules(): array
    {
        $userId = $this->route('user') instanceof \App\Models\User 
            ? $this->route('user')->id 
            : $this->route('user');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $userId],
            'email'    => ['required', 'email', 'unique:users,email,' . $userId],
            'phone'    => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['required', 'in:0,1'],
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
            'is_active' => 'Status Akun',
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
