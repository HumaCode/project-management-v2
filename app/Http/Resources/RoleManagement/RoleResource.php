<?php

namespace App\Http\Resources\RoleManagement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'type_role'       => $this->type_role,
            'guard_name'      => $this->guard_name,
            'description'     => $this->description,
            'permissions'     => $this->whenLoaded('permissions', function () {
                return $this->permissions->pluck('name');
            }),

            'permission_count_label'          => $this->permission_count_label,
            'permissions_percentage'          => $this->permissions_percentage,
            'users_count_label'             => $this->users_count_label,
            'created_at_indo' => $this->created_at_indo,
            'updated_at_indo' => $this->updated_at_indo,
        ];
    }
}
