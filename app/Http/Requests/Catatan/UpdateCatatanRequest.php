<?php

namespace App\Http\Requests\Catatan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatatanRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:Personal,Project,Meeting,Technical,Task,Penting',
            'priority' => 'required|string|in:tinggi,sedang,rendah',
            'project_id' => 'nullable|exists:projects,id',
            'user_id' => 'nullable|exists:users,id',
            'content' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul catatan wajib diisi',
            'category.required' => 'Kategori wajib dipilih',
            'priority.required' => 'Prioritas wajib dipilih',
            'user_id.required' => 'Pembuat catatan wajib dipilih',
            'content.required' => 'Isi catatan tidak boleh kosong',
        ];
    }
}
