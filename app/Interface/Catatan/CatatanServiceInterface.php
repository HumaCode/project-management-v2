<?php

namespace App\Interface\Catatan;

interface CatatanServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedCatatans(?string $search, ?string $category, ?string $project_id, ?string $priority, int $perPage);
    public function storeCatatan(array $data);
    public function getCatatanById(string $id);
    public function updateCatatan(string $id, array $data);
    public function deleteCatatan(string $id);
}
