<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'start_date' => $this->start_date ? $this->start_date : null,
            'deadline' => $this->deadline ? $this->deadline : null,
            'progress' => $this->progress,
            'created_by' => $this->creator ? $this->creator->name : 'Unknown',
            'pics' => $this->pics->map(fn($u) => [
                'name' => $u->name,
                'avatar' => $u->display_avatar,
                'initials' => $u->initials,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
