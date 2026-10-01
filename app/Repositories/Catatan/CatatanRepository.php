<?php

namespace App\Repositories\Catatan;

use App\Interface\Catatan\CatatanRepositoryInterface;
use App\Models\Catatan;
use App\Repositories\BaseRepository;

class CatatanRepository extends BaseRepository implements CatatanRepositoryInterface
{
    public function __construct(Catatan $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(?string $search, ?string $category, ?string $project_id, ?string $priority, int $perPage)
    {
        $query = $this->model->newQuery()->with(['user', 'project', 'media']);

        $user = auth()->user();
        if ($user && !$user->hasRole('dev') && !$user->hasRole('admin')) {
            $query->where(function($q) use ($user) {
                $q->whereHas('project.team.members', function($mq) use ($user) {
                    $mq->where('users.id', $user->id);
                })->orWhereHas('project', function($pq) use ($user) {
                    $pq->where('created_by', $user->id);
                })->orWhere('user_id', $user->id);
            });
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($project_id) {
            $query->where('project_id', $project_id);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(string $id, array $relations = [])
    {
        if (empty($relations)) {
            $relations = ['user', 'project', 'media'];
        } else if (!in_array('media', $relations)) {
            $relations[] = 'media';
        }
        return parent::findById($id, $relations);
    }

    public function create(array $data)
    {
        $attachments = $data['attachments'] ?? [];
        unset($data['attachments']);

        $catatan = parent::create($data);

        if (!empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $file = convertToWebp($file);
                    $catatan->addMedia($file)->toMediaCollection('catatan_attachments', 'local');
                }
            }
        }

        return $catatan->load(['user', 'project', 'media']);
    }

    public function update(string $id, array $data)
    {
        $attachments = $data['attachments'] ?? [];
        $deleteAttachments = $data['delete_attachments'] ?? [];
        unset($data['attachments'], $data['delete_attachments']);

        $catatan = parent::update($id, $data);

        if (!empty($deleteAttachments) && is_array($deleteAttachments)) {
            foreach ($deleteAttachments as $mediaId) {
                $media = $catatan->getMedia('catatan_attachments')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        if (!empty($attachments) && is_array($attachments)) {
            foreach ($attachments as $file) {
                if ($file instanceof \Illuminate\Http\UploadedFile) {
                    $file = convertToWebp($file);
                    $catatan->addMedia($file)->toMediaCollection('catatan_attachments', 'local');
                }
            }
        }

        return $catatan->load(['user', 'project', 'media']);
    }

    public function getStatistics()
    {
        $user = auth()->user();
        $query = $this->model->newQuery();

        if ($user && !$user->hasRole('dev') && !$user->hasRole('admin')) {
            $query->where(function($q) use ($user) {
                $q->whereHas('project.team.members', function($mq) use ($user) {
                    $mq->where('users.id', $user->id);
                })->orWhereHas('project', function($pq) use ($user) {
                    $pq->where('created_by', $user->id);
                })->orWhere('user_id', $user->id);
            });
        }

        $startOfWeek = now()->startOfWeek()->toDateTimeString();

        $stats = $query->selectRaw("
            COUNT(*) as total_catatan,
            COUNT(CASE WHEN priority = 'tinggi' THEN 1 END) as total_high_priority,
            COUNT(DISTINCT category) as total_categories,
            COUNT(DISTINCT project_id) as total_projects_related,
            COUNT(CASE WHEN created_at >= ? THEN 1 END) as catatan_minggu_ini,
            COUNT(CASE WHEN priority = 'tinggi' AND created_at >= ? THEN 1 END) as high_priority_minggu_ini
        ", [$startOfWeek, $startOfWeek])->first();

        return [
            'total_catatan' => (int) ($stats->total_catatan ?? 0),
            'total_high_priority' => (int) ($stats->total_high_priority ?? 0),
            'total_categories' => (int) ($stats->total_categories ?? 0),
            'total_projects_related' => (int) ($stats->total_projects_related ?? 0),
            'catatan_minggu_ini' => (int) ($stats->catatan_minggu_ini ?? 0),
            'high_priority_minggu_ini' => (int) ($stats->high_priority_minggu_ini ?? 0),
        ];
    }
}
