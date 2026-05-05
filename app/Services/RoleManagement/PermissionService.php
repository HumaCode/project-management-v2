<?php

namespace App\Services\RoleManagement;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Interface\RoleManagement\PermissionServiceInterface;

class PermissionService implements PermissionServiceInterface
{
    protected PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function getPaginatedPermissions(?string $search, ?int $perPage)
    {
        return $this->permissionRepository->getAllPaginated($search, $perPage);
    }

    public function createPermission(array $data)
    {
        return $this->permissionRepository->create($data);
    }

    public function getPermissionById(string $id)
    {
        return $this->permissionRepository->getById($id);
    }

    public function updatePermission(string $id, array $data)
    {
        return $this->permissionRepository->update($id, $data);
    }

    public function deletePermission(string $id)
    {
        return $this->permissionRepository->delete($id);
    }
}
