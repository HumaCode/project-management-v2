<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'nullable|array',
            'members.*.id' => 'required|exists:users,id',
            'members.*.role' => 'nullable|string|max:100',
        ];
    }
}
