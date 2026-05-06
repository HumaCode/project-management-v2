<?php

namespace App\Interface\Project;

interface ProjectServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage);
}
