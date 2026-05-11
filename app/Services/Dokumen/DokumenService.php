<?php

namespace App\Services\Dokumen;

use App\Interface\Dokumen\DokumenRepositoryInterface;
use App\Interface\Dokumen\DokumenServiceInterface;

class DokumenService implements DokumenServiceInterface
{
    private DokumenRepositoryInterface $dokumenRepository;

    public function __construct(DokumenRepositoryInterface $dokumenRepository)
    {
        $this->dokumenRepository = $dokumenRepository;
    }

    public function getPaginatedDokumens($search, $kategori, $project_id, $perPage)
    {
        return $this->dokumenRepository->getPaginated($search, $kategori, $project_id, $perPage);
    }

    public function getDokumenStatistics()
    {
        return $this->dokumenRepository->getStatistics();
    }
}
