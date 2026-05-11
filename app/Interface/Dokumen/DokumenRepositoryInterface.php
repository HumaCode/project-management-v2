<?php

namespace App\Interface\Dokumen;

use App\Interface\BaseRepositoryInterface;

interface DokumenRepositoryInterface extends BaseRepositoryInterface
{
    public function getPaginated($search, $kategori, $project_id, $perPage);
    public function getStatistics();
}
