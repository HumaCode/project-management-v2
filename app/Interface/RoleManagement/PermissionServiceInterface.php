<?php

namespace App\Interface\RoleManagement;

interface PermissionServiceInterface
{
    public function getPaginatedPermissions(?string $search, ?int $perPage);
    public function createPermission(array $data);
    public function getPermissionById(string $id);
    public function updatePermission(string $id, array $data);
    public function deletePermission(string $id);
}
