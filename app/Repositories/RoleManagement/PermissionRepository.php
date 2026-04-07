<?php

namespace App\Repositories\RoleManagement;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Models\Shield\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        // Inject model ke BaseRepository
        parent::__construct($model);
    }

    public function getAll(?string $search, ?string $limit, bool $execute)
    {
        $query = $this->model->query(); // Gunakan $this->model dari BaseRepository

        if ($search) {
            $query->search($search);
        }

        if ($limit) {
            $query->take((int) $limit);
        }

        $query->orderBy('id', 'desc');

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowsPerPage)
    {
        return $this->getAll($search, null, false)->paginate($rowsPerPage);
    }
}
