<?php

namespace App\Interface\Project;

interface ProjectServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage, ?string $sortCol = 'created_at', ?string $sortDir = 'desc');
    public function storeProject(array $data): \App\Models\Project;
    public function getProjectByUlid(string $id): \App\Models\Project;
    public function updateProject(string $id, array $data): \App\Models\Project;
    public function deleteProject(string $id): bool;
    public function getProjectDetailData(string $id): array;
    public function getPaginatedActivities(string $id, int $perPage = 10);
    public function storeDiskusi(string $id, array $data): \App\Models\Diskusi;
    public function updateDiskusi(string $id, array $data): \App\Models\Diskusi;
    public function deleteDiskusi(string $id): void;
}
