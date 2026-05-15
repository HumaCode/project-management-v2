<?php

namespace App\Services\Project;

use App\Interface\Project\ProjectServiceInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    public function getAllProjects()
    {
        $user = auth()->user();
        $query = Project::query();

        if (!$user->hasRole(['dev', 'admin'])) {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('team.members', function ($sq) use ($user) {
                        $sq->where('users.id', $user->id);
                    });
            });
        }

        return $query->latest()->get();
    }

    public function getIndexData(): array
    {
        $user = auth()->user();
        $version = $this->getCacheVersion();
        $cacheKey = "project_stats_v{$version}_user_{$user->id}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
            $query = Project::query();

            // Filter akses jika bukan admin/dev
            if (!$user->hasRole(['dev', 'admin'])) {
                $query->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhereHas('team.members', function($sq) use ($user) {
                          $sq->where('users.id', $user->id);
                      });
                });
            }

            $counts = $query->selectRaw("
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
        });
    }

    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage, ?string $sortCol = 'created_at', ?string $sortDir = 'desc')
    {
        $user = auth()->user();
        $page = request()->get('page', 1);
        $version = $this->getCacheVersion();
        $cacheKey = "projects_list_v{$version}_user_{$user->id}_p{$page}_r{$rowPerPage}_s" . md5($search . $status . $sortCol . $sortDir);

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $status, $rowPerPage, $sortCol, $sortDir, $user) {
            // Eager load media also to avoid N+1 when processing thumbnail and avatars in Resource
            $query = Project::with(['creator.media', 'team.members.media', 'media']);

            // Filter: Hanya tampilkan project di mana user adalah anggota tim atau pembuatnya
            if (!$user->hasRole(['dev', 'admin'])) {
                $query->where(function($q) use ($user) {
                    $q->where('created_by', $user->id)
                      ->orWhereHas('team.members', function($sq) use ($user) {
                          $sq->where('users.id', $user->id);
                      });
                });
            }

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($status) {
                $query->where('status', $status);
            }

            // Sorting - Paksa terbaru di atas jika tidak ada sortir spesifik
            $allowedSort = ['name', 'start_date', 'deadline', 'created_at'];
            $finalSortCol = in_array($sortCol, $allowedSort) ? $sortCol : 'created_at';
            
            // Jika kolomnya created_at, kita paksa DESC (terbaru) kecuali user minta ASC secara sadar
            $finalSortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

            return $query->orderBy($finalSortCol, $finalSortDir)
                         ->orderBy('id', 'desc') // Tambahan fallback ID agar urutan input benar-benar akurat
                         ->paginate($rowPerPage);
        });
    }

    public function storeProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            // Transform dates
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            
            $data['created_by'] = auth()->id();

            $project = Project::create($data);

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }
            
            $this->clearProjectCache();

            return $project;
        });
    }

    public function getProjectByUlid(string $id): Project
    {
        return Project::with(['team.members.media', 'media'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();
    }

    public function updateProject(string $id, array $data): Project
    {
        return DB::transaction(function () use ($id, $data) {
            $project = Project::where('id', $id)->orWhere('slug', $id)->firstOrFail();

            // Transform dates
            if (isset($data['start_date'])) {
                $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            }
            if (isset($data['deadline'])) {
                $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            }

            $project->update($data);

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->clearMediaCollection('thumbnail');
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }
            
            $this->clearProjectCache($project->id);

            return $project;
        });
    }

    public function deleteProject(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $project = Project::where('id', $id)->orWhere('slug', $id)->firstOrFail();

            // Bersihkan relasi many-to-many dengan PIC (agar bersih di pivot table)
            $project->pics()->detach();

            // Hapus project (karena trait InteractsWithMedia, file fisik di public/storage 
            // umumnya otomatis terhapus saat model di-delete, namun jika diperlukan bisa dipanggil clearMediaCollection)
            $project->clearMediaCollection('thumbnail');
            
            $result = $project->delete();

            $this->clearProjectCache();

            return $result;
        });
    }

    private function getCacheVersion(): int
    {
        return \Illuminate\Support\Facades\Cache::get('project_cache_version', 1);
    }

    private function clearProjectCache(?string $projectId = null): void
    {
        // Increment versi cache - INI AKAN LANGSUNG MEMBERSIHKAN SEMUA LIST UNTUK SEMUA USER
        \Illuminate\Support\Facades\Cache::forever('project_cache_version', $this->getCacheVersion() + 1);

        // Bersihkan dashboard data user aktif (atau bisa gunakan versi juga di DashboardService)
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_' . auth()->id());
        
        // Jika ada project spesifik, hapus detailnya
        if ($projectId) {
            \Illuminate\Support\Facades\Cache::forget("project_detail_{$projectId}_user_" . auth()->id());
        }
    }

    public function getProjectDetailData(string $id): array
    {
        $userId = auth()->id();
        $project = Project::with(['team.members.media', 'creator.media'])
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();
        
        // Stats calculation
        $stats = [
            'docs_count' => $project->dokumens()->count(),
            'notes_count' => $project->diskusis()->count(),
            'members_count' => $project->team?->members()->count() ?? 0,
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
                'team' => [
                    'name' => $project->team?->name ?? 'No Team',
                ],
                'pics' => ($project->team?->members ?? collect())->map(fn($u) => [
                    'name' => $u->name,
                    'initials' => $u->initials,
                    'display_avatar' => $u->display_avatar,
                    'role_name' => $u->pivot->role ?? $u->role_name,
                ]),
            ],
            'stats' => $stats,
            'dokumens' => $project->dokumens()->with(['uploader', 'media'])->latest()->paginate(5, ['*'], 'doc_page')->through(fn($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
                'versi' => $d->versi,
                'kategori' => $d->kategori,
                'type' => $d->type,
                'kategori_label' => $d->kategori_label,
                'created_at' => $d->created_at->format('Y-m-d'),
                'uploader' => [
                    'name' => $d->uploader?->name ?? 'Unknown',
                ],
                'file_size' => $d->file_size_label
            ]),
            'catatans' => $project->diskusis()->with(['user', 'media', 'parent.user', 'parent.media'])->latest()->limit(20)->get()->map(fn($c) => [
                'id' => $c->id,
                'content' => $c->content,
                'is_me' => $c->user_id === $userId,
                'is_edited' => $c->updated_at->gt($c->created_at),
                'can_edit' => $c->user_id === $userId && $c->created_at->diffInMinutes(now()) < 5,
                'can_delete' => $c->user_id === $userId,
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
            'activities' => $this->getPaginatedActivities($project->id, 5),
            'activities_meta' => [
                'total' => \Spatie\Activitylog\Models\Activity::where(function($q) use ($project) {
                    $q->where(function($sq) use ($project) {
                        $sq->where('subject_type', \App\Models\Project::class)
                           ->where('subject_id', $project->id);
                    })->orWhere(function($sq) use ($project) {
                        $sq->where('subject_type', \App\Models\Dokumen::class)
                           ->whereIn('subject_id', $project->dokumens()->pluck('id'));
                    })->orWhere(function($sq) use ($project) {
                        $sq->where('subject_type', \App\Models\Diskusi::class)
                           ->whereIn('subject_id', $project->diskusis()->pluck('id'));
                    })->orWhere(function($sq) use ($project) {
                        $sq->where('subject_type', \App\Models\Catatan::class)
                           ->whereIn('subject_id', $project->catatans()->pluck('id'));
                    });
                })->count(),
            ]
        ];
    }

    public function getPaginatedActivities(string $id, int $perPage = 10)
    {
        $project = Project::where('id', $id)->orWhere('slug', $id)->firstOrFail();

        $activities = \Spatie\Activitylog\Models\Activity::where(function($q) use ($project) {
                $q->where(function($sq) use ($project) {
                    $sq->where('subject_type', \App\Models\Project::class)
                       ->where('subject_id', $project->id);
                })->orWhere(function($sq) use ($project) {
                    $sq->where('subject_type', \App\Models\Dokumen::class)
                       ->whereIn('subject_id', $project->dokumens()->pluck('id'));
                })->orWhere(function($sq) use ($project) {
                    $sq->where('subject_type', \App\Models\Diskusi::class)
                       ->whereIn('subject_id', $project->diskusis()->pluck('id'));
                })->orWhere(function($sq) use ($project) {
                    $sq->where('subject_type', \App\Models\Catatan::class)
                       ->whereIn('subject_id', $project->catatans()->pluck('id'));
                });
            })
            ->with(['causer', 'subject'])
            ->latest()
            ->paginate($perPage);

        $activities->getCollection()->transform(function($a) {
            $event = strtolower($a->description);
            $subjectType = strtolower(class_basename($a->subject_type));
            $subjectName = "";
            
            if ($a->subject) {
                $subjectName = $a->subject->nama ?? $a->subject->name ?? $a->subject->title ?? "";
            }

            $friendlyDesc = "Melakukan aktivitas";
            $icon = "bi-activity";
            $color = "primary";

            if ($event === 'created') {
                $friendlyDesc = "Mengirim " . ($subjectType === 'project' ? 'proyek' : ($subjectType === 'dokumen' ? 'dokumen' : ($subjectType === 'catatan' ? 'catatan' : 'pesan diskusi'))) . " baru";
                if ($subjectName) $friendlyDesc .= ": <b>$subjectName</b>";
                $icon = "bi-plus-circle-fill";
                $color = "success";
            } elseif ($event === 'updated') {
                $friendlyDesc = "Memperbarui detail " . ($subjectType === 'project' ? 'proyek' : ($subjectType === 'dokumen' ? 'dokumen' : ($subjectType === 'catatan' ? 'catatan' : 'pesan diskusi')));
                if ($subjectName) $friendlyDesc .= ": <b>$subjectName</b>";
                $icon = "bi-pencil-square";
                $color = "info";
            } elseif ($event === 'deleted') {
                $friendlyDesc = "Menghapus " . ($subjectType === 'project' ? 'proyek' : ($subjectType === 'dokumen' ? 'dokumen' : ($subjectType === 'catatan' ? 'catatan' : 'pesan diskusi')));
                $icon = "bi-trash-fill";
                $color = "danger";
            }

            return [
                'description' => $friendlyDesc,
                'event' => $event,
                'type' => $subjectType,
                'icon' => $icon,
                'color' => $color,
                'created_at' => $a->created_at->diffForHumans(),
                'causer' => [
                    'name' => $a->causer?->name ?? 'System',
                    'initials' => $a->causer?->initials ?? 'S',
                    'display_avatar' => $a->causer?->display_avatar,
                ],
                'properties' => $a->properties,
            ];
        });

        return $activities;
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
        
        $this->clearProjectCache($projectId);

        broadcast(new \App\Events\Project\DiskusiSent($diskusi->load(['user', 'parent.user', 'media', 'parent.media'])))->toOthers();

        return $diskusi->load(['user', 'parent.user', 'media', 'parent.media']);
    }

    public function updateDiskusi(string $id, array $data): \App\Models\Diskusi
    {
        $diskusi = \App\Models\Diskusi::findOrFail($id);

        $diskusi->update([
            'content' => $data['content'] ?? '',
        ]);
        
        $this->clearProjectCache($diskusi->project_id);

        return $diskusi;
    }

    public function deleteDiskusi(string $id): void
    {
        $diskusi = \App\Models\Diskusi::findOrFail($id);
        $projectId = $diskusi->project_id;
        $diskusi->delete();
        $this->clearProjectCache($projectId);
    }
}
