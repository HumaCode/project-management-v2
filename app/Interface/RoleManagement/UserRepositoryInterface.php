<?php

namespace App\Interface\RoleManagement;

use App\Interface\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getAll(?string $search, ?string $limit, ?string $status, ?string $type, bool $execute);

    public function getAllPaginated(?string $search, ?string $status, ?string $type, ?int $rowsPerPage);

    public function approve(string $id);

    public function reject(string $id);
}
