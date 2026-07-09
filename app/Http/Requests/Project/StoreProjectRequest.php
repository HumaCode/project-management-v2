<?php

namespace App\Http\Requests\Project;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:to_do,in_progress,done'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'start_date' => ['required', 'date_format:d-m-Y'],
            'deadline' => ['required', 'date_format:d-m-Y', 'after_or_equal:start_date'],
            'progress' => ['integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:300'],
            'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'team_id' => ['required', 'exists:teams,id'],
            'pics' => ['nullable', 'array'],
            'pics.*' => ['exists:users,id'],
            'app_type' => ['nullable', 'in:website,android,website_android'],
        ];
    }
}
