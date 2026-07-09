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
            'description' => $this->description ? \Illuminate\Support\Str::limit(strip_tags($this->description), 80) : null,
            'status' => $this->status,
            'start_date' => $this->start_date ? $this->start_date->format('Y-m-d') : null,
            'deadline' => $this->deadline ? $this->deadline->format('Y-m-d') : null,
            'progress' => $this->progress,
            'color' => $this->color ?? '#4f46e5',
            'icon' => $this->icon ?? 'bi-kanban',
            'thumbnail' => $this->getFirstMediaUrl('thumbnail'),
            'created_by' => $this->creator ? $this->creator->name : 'Unknown',
            'team_name' => $this->team ? $this->team->name : '-',
            'pics' => ($this->team?->members ?? collect())->map(fn($u) => [
                'name' => $u->name,
                'avatar' => $u->display_avatar,
                'initials' => $u->initials,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'app_type' => $this->app_type,
        ];
    }
}
