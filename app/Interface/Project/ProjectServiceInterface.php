<?php

namespace App\Interface\Project;

interface ProjectServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage);
    public function storeProject(array $data): \App\Models\Project;
}
