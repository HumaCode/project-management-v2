<?php

namespace App\Events\Project;

use App\Models\Diskusi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DiskusiSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Diskusi $diskusi;

    /**
     * Create a new event instance.
     */
    public function __construct(Diskusi $diskusi)
    {
        $this->diskusi = $diskusi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.' . $this->diskusi->project_id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->diskusi->id,
            'content' => $this->diskusi->content,
            'user_id' => $this->diskusi->user_id,
            'project_id' => $this->diskusi->project_id,
            'is_edited' => $this->diskusi->updated_at->gt($this->diskusi->created_at),
            'created_at_human' => $this->diskusi->created_at_human,
            'attachments' => $this->diskusi->getMedia('diskusi_attachments')->map(fn($m) => [
                'id' => $m->id,
                'url' => route('projects.diskusi.media', ['mediaId' => $m->id]),
                'name' => $m->file_name,
                'type' => str_starts_with($m->mime_type, 'image/') ? 'image' : 'file',
            ]),
            'parent' => $this->diskusi->parent ? [
                'id' => $this->diskusi->parent->id,
                'user_name' => $this->diskusi->parent->user?->name ?? 'Unknown',
                'content' => $this->diskusi->parent->content,
                'thumbnail' => $this->diskusi->parent->getMedia('diskusi_attachments')->where('mime_type', 'like', 'image/%')->first()?->getUrl(),
            ] : null,
            'user' => [
                'id' => $this->diskusi->user?->id,
                'name' => $this->diskusi->user?->name ?? 'Unknown',
                'initials' => $this->diskusi->user?->initials ?? '??',
                'display_avatar' => $this->diskusi->user?->display_avatar,
            ]
        ];
    }
}
