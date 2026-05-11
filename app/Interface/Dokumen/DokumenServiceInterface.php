<?php

namespace App\Interface\Dokumen;

interface DokumenServiceInterface
{
    public function getPaginatedDokumens($search, $kategori, $project_id, $perPage, $type = null);
    public function getDokumenStatistics();
    public function createDokumen(array $data);
    public function updateDokumen(string $id, array $data);
    public function getDokumenById(string $id);
}
