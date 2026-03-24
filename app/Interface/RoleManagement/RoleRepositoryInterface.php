<?php

namespace App\Interface\RoleManagement;

use App\Interface\BaseRepositoryInterface;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function getAll(?string $search, ?string $limit, ?string $status, bool $execute);
    public function getAllPaginated(?string $search, ?string $status, ?int $rowsPerPage);
}
