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
            'date' => $this->date ? $this->date->format('Y-m-d') : null,
            'date_formatted' => $this->date ? $this->date->format('d M Y') : null,
            'created_at' => $this->created_at->format('d M Y'),
            'updated_at_diff' => $this->updated_at->diffForHumans(),
            'created_at_raw' => $this->created_at->toDateTimeString(),
            'attachments' => $this->getMedia('catatan_attachments')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'file_size' => number_format($media->size / 1024, 1) . ' KB',
                    'mime_type' => $media->mime_type,
                    'url' => route('catatan.media', \Illuminate\Support\Facades\Crypt::encryptString($media->id)),
                    'is_image' => str_starts_with($media->mime_type, 'image/'),
                ];
            }),
        ];
    }
}
