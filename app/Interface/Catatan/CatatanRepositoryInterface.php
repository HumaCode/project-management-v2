<?php

namespace App\Interface\Catatan;

use App\Interface\BaseRepositoryInterface;

interface CatatanRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginated(?string $search, ?string $category, ?string $project_id, ?string $priority, int $perPage);
    public function getStatistics();
}
