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
        return [
            'total_catatan' => $this->model->count(),
            'total_high_priority' => $this->model->where('priority', 'tinggi')->count(),
            'total_categories' => $this->model->distinct('category')->count('category'),
            'total_projects_related' => $this->model->whereNotNull('project_id')->distinct('project_id')->count('project_id'),
        ];
    }
}
