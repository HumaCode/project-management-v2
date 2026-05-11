<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class DokumenStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type'           => 'required|in:file,article,code',
            'nama'           => 'required|string|max:255',
            'versi'          => 'nullable|string|max:50',
            'kategori'       => 'required|string|max:10',
            'project_id'     => 'required|exists:projects,id',
            'user_id'        => 'required|exists:users,id',
            'tanggal_upload' => 'nullable|date',
            'keterangan'     => 'nullable|string',
            'file'           => 'required_if:type,file|file|max:51200', // Max 50MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.required_if' => 'File dokumen wajib diunggah jika tipe adalah File Tunggal.',
            'nama.required'    => 'Nama dokumen tidak boleh kosong.',
            'project_id.required' => 'Project harus dipilih.',
            'user_id.required'    => 'User pengunggah harus dipilih.',
        ];
    }
}
