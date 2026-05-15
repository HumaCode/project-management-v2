<?php

namespace App\Interface\Project;

interface ProjectServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage, ?string $sortCol = 'created_at', ?string $sortDir = 'desc');
    public function storeProject(array $data): \App\Models\Project;
    public function getProjectByUlid(string $ulid): \App\Models\Project;
    public function updateProject(string $ulid, array $data): \App\Models\Project;
    public function deleteProject(string $ulid): bool;
    public function getProjectDetailData(string $ulid): array;
    public function getPaginatedActivities(string $projectId, int $perPage = 10);
    public function storeDiskusi(string $projectId, array $data): \App\Models\Diskusi;
    public function updateDiskusi(string $id, array $data): \App\Models\Diskusi;
    public function deleteDiskusi(string $id): void;
}
