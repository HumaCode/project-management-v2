<?php

namespace App\Services\RoleManagement;

use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Interface\RoleManagement\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserStatistics(): array
    {
        return [
            'countAllUser'         => $this->userRepository->countAllUser(),
            'countAllUserActive'   => $this->userRepository->countAllUserActive(),
            'countAllUserInactive' => $this->userRepository->countAllUserInactive(),
            'countNewUser'         => $this->userRepository->countNewUser(),
        ];
    }

    public function getPaginatedUsers(?string $search, ?string $status, ?string $type, ?int $perPage)
    {
        return $this->userRepository->getAllPaginated($search, $status, $type, $perPage);
    }

    public function createUser(array $data)
    {
        // Logika tambahan seperti hashing password biasanya sudah ada di model atau repository,
        // tapi kita bisa pastikan di sini jika perlu.
        return $this->userRepository->create($data);
    }

    public function getUserById(string $id)
    {
        return $this->userRepository->getById($id);
    }

    public function updateUser(string $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(string $id)
    {
        return $this->userRepository->delete($id);
    }

    public function approveUser(string $id)
    {
        return $this->userRepository->approve($id);
    }

    public function rejectUser(string $id)
    {
        return $this->userRepository->reject($id);
    }

    public function resetUserPassword(string $id, array $data)
    {
        return $this->userRepository->resetPassword($id, $data);
    }

    public function getActiveRoles()
    {
        return $this->userRepository->getRoleActive();
    }

    public function getUsersByRole(string $role)
    {
        return $this->userRepository->getUsersByRole($role);
    }
}
