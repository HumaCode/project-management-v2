<?php

namespace App\Interface\RoleManagement;

interface RoleServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedRoles(?string $search, ?string $status, ?int $perPage);
    public function createRole(array $data);
    public function getRoleById(string $id);
    public function updateRole(string $id, array $data);
    public function deleteRole(string $id);
    public function syncPermissions(string $id, array $permissions);
    public function getPermissionsData(): array;
}
