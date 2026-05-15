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
        $query = $this->model->newQuery()->with(['user', 'project']);

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

    public function getStatistics()
    {
        $user = auth()->user();
        $cacheKey = "catatan_stats_user_{$user->id}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
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

            return [
                'total_catatan' => (clone $query)->count(),
                'total_high_priority' => (clone $query)->where('priority', 'tinggi')->count(),
                'total_categories' => (clone $query)->distinct('category')->count('category'),
                'total_projects_related' => (clone $query)->whereNotNull('project_id')->distinct('project_id')->count('project_id'),
            ];
        });
    }
}
