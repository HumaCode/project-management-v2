<?php

namespace App\Http\Requests\Dokumen;

use Illuminate\Foundation\Http\FormRequest;

class DokumenPaginationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'       => 'nullable|string',
            'kategori'     => 'nullable|string',
            'project_id'   => 'nullable|string',
            'row_per_page' => 'required|integer|min:1|max:100',
        ];
    }
}
