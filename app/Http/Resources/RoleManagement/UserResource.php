<?php

namespace App\Http\Resources\RoleManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'role_name' => $this->role_name,
            'created_at_indo' => $this->created_at_indo,
            'updated_at_indo' => $this->updated_at_indo,
        ];
    }
}
