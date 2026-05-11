<?php

namespace App\Interface\Dokumen;

interface DokumenServiceInterface
{
    public function getPaginatedDokumens($search, $kategori, $project_id, $perPage);
    public function getDokumenStatistics();
}
