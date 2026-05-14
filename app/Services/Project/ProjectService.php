<?php

namespace App\Services\Project;

use App\Interface\Project\ProjectServiceInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    public function getIndexData(): array
    {
        $counts = Project::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count,
            SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done_count,
            SUM(CASE WHEN status = 'to_do' THEN 1 ELSE 0 END) as to_do_count
        ")->first();

        return [
            'total_projects' => (int) ($counts->total ?? 0),
            'in_progress' => (int) ($counts->in_progress_count ?? 0),
            'done' => (int) ($counts->done_count ?? 0),
            'to_do' => (int) ($counts->to_do_count ?? 0),
        ];
    }

    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage, ?string $sortCol = 'created_at', ?string $sortDir = 'desc')
    {
        // Eager load media also to avoid N+1 when processing thumbnail and avatars in Resource
        $query = Project::with(['creator.media', 'pics.media', 'media']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Sorting
        $allowedSort = ['name', 'start_date', 'deadline', 'created_at', 'created_by'];
        $sortCol = in_array($sortCol, $allowedSort) ? $sortCol : 'created_at';
        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortCol, $sortDir)->paginate($rowPerPage);
    }

    public function storeProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            // Transform dates
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            
            $data['created_by'] = auth()->id();

            $project = Project::create($data);

            // Sync PICs
            if (isset($data['pics'])) {
                $project->pics()->sync($data['pics']);
            }

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }

            return $project;
        });
    }

    public function getProjectByUlid(string $ulid): Project
    {
        return Project::with(['pics', 'media'])->findOrFail($ulid);
    }

    public function updateProject(string $ulid, array $data): Project
    {
        return DB::transaction(function () use ($ulid, $data) {
            $project = Project::findOrFail($ulid);

            // Transform dates
            if (isset($data['start_date'])) {
                $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            }
            if (isset($data['deadline'])) {
                $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            }

            $project->update($data);

            // Sync PICs
            if (isset($data['pics'])) {
                $project->pics()->sync($data['pics']);
            }

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->clearMediaCollection('thumbnail');
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }

            return $project;
        });
    }

    public function deleteProject(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $project = Project::findOrFail($ulid);

            // Bersihkan relasi many-to-many dengan PIC (agar bersih di pivot table)
            $project->pics()->detach();

            // Hapus project (karena trait InteractsWithMedia, file fisik di public/storage 
            // umumnya otomatis terhapus saat model di-delete, namun jika diperlukan bisa dipanggil clearMediaCollection)
            $project->clearMediaCollection('thumbnail');

            return $project->delete();
        });
    }

    public function getProjectDetailData(string $ulid): array
    {
        $project = Project::with(['pics.media', 'creator.media'])->findOrFail($ulid);
        
        // Stats calculation
        $stats = [
            'docs_count' => $project->dokumens()->count(),
            'notes_count' => $project->diskusis()->count(),
            'members_count' => $project->pics()->count(),
            'days_remaining' => now()->diffInDays($project->deadline, false) >= 0 
                ? now()->diffInDays($project->deadline, false) 
                : 0,
        ];
        
        // Status class logic for days remaining
        $days = $stats['days_remaining'];
        $stats['days_remaining_class'] = $days <= 0 ? 'err' : ($days < 7 ? 'warn' : 'ok');

        // Map data to prevent circular reference and optimize JSON
        return [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'icon' => $project->icon,
                'status' => $project->status,
                'progress' => $project->progress,
                'description' => $project->description,
                'start_date' => $project->start_date->format('Y-m-d'),
                'deadline' => $project->deadline->format('Y-m-d'),
                'creator' => [
                    'name' => $project->creator?->name ?? 'System',
                ],
                'pics' => $project->pics->map(fn($u) => [
                    'name' => $u->name,
                    'initials' => $u->initials,
                    'display_avatar' => $u->display_avatar,
                    'role_name' => $u->role_name,
                ]),
            ],
            'stats' => $stats,
            'dokumens' => $project->dokumens()->with('uploader')->latest()->limit(10)->get()->map(fn($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
                'versi' => $d->versi,
                'kategori' => $d->kategori,
                'type' => $d->type,
                'kategori_label' => $d->kategori_label,
                'created_at' => $d->created_at->format('Y-m-d'),
                'uploader' => [
                    'name' => $d->uploader?->name ?? 'Unknown',
                ]
            ]),
            'catatans' => $project->diskusis()->with(['user', 'media', 'parent.user', 'parent.media'])->latest()->limit(20)->get()->map(fn($c) => [
                'id' => $c->id,
                'content' => $c->content,
                'is_me' => $c->user_id === auth()->id(),
                'is_edited' => $c->updated_at->gt($c->created_at),
                'can_edit' => $c->user_id === auth()->id() && $c->created_at->diffInMinutes(now()) < 5,
                'can_delete' => $c->user_id === auth()->id(),
                'created_at_human' => $c->created_at_human,
                'attachments' => $c->getMedia('diskusi_attachments')->map(fn($m) => [
                    'id' => $m->id,
                    'url' => route('projects.diskusi.media', ['mediaId' => $m->id]),
                    'name' => $m->file_name,
                    'type' => str_starts_with($m->mime_type, 'image/') ? 'image' : 'file',
                ]),
                'parent' => $c->parent ? [
                    'id' => $c->parent->id,
                    'user_name' => $c->parent->user?->name ?? 'Unknown',
                    'content' => $c->parent->content,
                    'thumbnail' => $c->parent->getMedia('diskusi_attachments')->where('mime_type', 'like', 'image/%')->first()?->getUrl(),
                ] : null,
                'user' => [
                    'id' => $c->user?->id,
                    'name' => $c->user?->name ?? 'Unknown',
                    'initials' => $c->user?->initials ?? '??',
                    'display_avatar' => $c->user?->display_avatar,
                ]
            ]),
            'activities' => \Spatie\Activitylog\Models\Activity::where('subject_id', $project->id)
                ->where('subject_type', get_class($project))
                ->with('causer')
                ->latest()
                ->limit(10)
                ->get()
                ->map(fn($a) => [
                    'description' => $a->description,
                    'created_at' => $a->created_at->diffForHumans(),
                    'causer' => [
                        'name' => $a->causer?->name ?? 'System',
                        'initials' => $a->causer?->initials ?? 'S',
                    ],
                    'properties' => $a->properties,
                ])
        ];
    }

    public function storeDiskusi(string $projectId, array $data): \App\Models\Diskusi
    {
        $diskusi = \App\Models\Diskusi::create([
            'project_id' => $projectId,
            'user_id' => auth()->id(),
            'parent_id' => $data['parent_id'] ?? null,
            'content' => $data['content'] ?? '',
        ]);

        if (isset($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $file) {
                $diskusi->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection('diskusi_attachments');
            }
        }

        return $diskusi;
    }

    public function updateDiskusi(string $id, array $data): \App\Models\Diskusi
    {
        $diskusi = \App\Models\Diskusi::findOrFail($id);

        if ($diskusi->user_id !== auth()->id()) {
            throw new \Exception('Anda tidak diizinkan mengubah komentar ini.');
        }

        if ($diskusi->created_at->diffInMinutes(now()) >= 5) {
            throw new \Exception('Batas waktu pengeditan (5 menit) telah berakhir.');
        }

        $diskusi->update([
            'content' => $data['content'] ?? '',
        ]);

        return $diskusi;
    }

    public function deleteDiskusi(string $id): void
    {
        $diskusi = \App\Models\Diskusi::findOrFail($id);

        if ($diskusi->user_id !== auth()->id()) {
            throw new \Exception('Anda tidak diizinkan menghapus komentar ini.');
        }

        $diskusi->delete();
    }
}
