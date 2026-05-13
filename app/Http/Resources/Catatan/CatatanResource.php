<?php

namespace App\Http\Resources\Catatan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatatanResource extends JsonResource
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
            'title' => $this->title,
            'category' => $this->category,
            'priority' => $this->priority,
            'content' => $this->content,
            'content_preview' => \Illuminate\Support\Str::limit(strip_tags($this->content), 80),
            'project_name' => $this->project ? $this->project->name : null,
            'user' => [
                'name' => $this->user ? $this->user->name : 'Unknown',
                'initials' => $this->user ? $this->user->initials : '??',
            ],
            'project_id' => $this->project_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('d M Y'),
            'updated_at_diff' => $this->updated_at->diffForHumans(),
            'created_at_raw' => $this->created_at->toDateTimeString(),
        ];
    }
}
