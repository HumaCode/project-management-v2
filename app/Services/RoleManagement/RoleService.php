<?php

namespace App\Services\RoleManagement;

use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Interface\RoleManagement\RoleServiceInterface;

class RoleService implements RoleServiceInterface
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getIndexData(): array
    {
        return [
            'getCountRoles' => $this->roleRepository->getCountRoles(),
            'getCountGuardName' => $this->roleRepository->getCountGuardName(),
            'getCountUser' => $this->roleRepository->getCountUser(),
            'getPermissions' => $this->roleRepository->getPermissions(),
        ];
    }

    public function getPaginatedRoles(?string $search, ?string $status, ?int $perPage)
    {
        return $this->roleRepository->getAllPaginated($search, $status, $perPage);
    }

    public function createRole(array $data)
    {
        return $this->roleRepository->create($data);
    }

    public function getRoleById(string $id)
    {
        return $this->roleRepository->getById($id);
    }

    public function updateRole(string $id, array $data)
    {
        return $this->roleRepository->update($id, $data);
    }

    public function deleteRole(string $id)
    {
        return $this->roleRepository->delete($id);
    }

    public function syncPermissions(string $id, array $permissions)
    {
        return $this->roleRepository->syncPermissions($id, ['permissions' => $permissions]);
    }

    public function getPermissionsData(): array
    {
        return [
            'menus' => $this->roleRepository->getMainMenuWithPermissions(),
        ];
    }
}
