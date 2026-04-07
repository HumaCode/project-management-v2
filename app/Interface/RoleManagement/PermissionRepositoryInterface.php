<?php

namespace App\Interface\RoleManagement;

use App\Interface\BaseRepositoryInterface;

interface PermissionRepositoryInterface extends BaseRepositoryInterface
{
    public function getAll(?string $search, ?string $limit, bool $execute);

    public function getAllPaginated(?string $search, ?int $rowsPerPage);
}
