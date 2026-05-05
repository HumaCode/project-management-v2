<?php

namespace App\Interface\RoleManagement;

interface UserServiceInterface
{
    public function getUserStatistics(): array;
    public function getPaginatedUsers(?string $search, ?string $status, ?string $type, ?int $perPage);
    public function createUser(array $data);
    public function getUserById(string $id);
    public function updateUser(string $id, array $data);
    public function deleteUser(string $id);
    public function approveUser(string $id);
    public function rejectUser(string $id);
    public function resetUserPassword(string $id, array $data);
    public function getActiveRoles();
}
