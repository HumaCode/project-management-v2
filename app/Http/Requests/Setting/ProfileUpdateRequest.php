<?php

namespace App\Http\Requests\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ubah jadi true karena user yang login berhak mengedit profilnya sendiri
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Ambil ID user yang sedang login
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'alpha_dash', // Hanya boleh huruf, angka, strip, dan underscore
                'max:255',
                Rule::unique('users', 'username')->ignore($userId), // Abaikan validasi unik jika itu miliknya sendiri
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'bio' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif', // Format yang diizinkan
                'max:2048', // Maksimal 2MB (dalam Kilobytes)
            ],
        ];
    }

    /**
     * Kustomisasi pesan error agar lebih ramah untuk user (Opsional)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan underscore.',
            'username.unique' => 'Username ini sudah digunakan oleh pengguna lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'avatar.image' => 'File yang diupload harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau GIF.',
            'avatar.max' => 'Ukuran foto maksimal adalah 2MB.',
        ];
    }
}
