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
            'created_at_indo' => $this->created_at_indo,
        ];
    }
}
