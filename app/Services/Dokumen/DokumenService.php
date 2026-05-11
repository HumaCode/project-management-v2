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

    public function getPaginatedDokumens($search, $kategori, $project_id, $perPage, $type = null)
    {
        return $this->dokumenRepository->getPaginated($search, $kategori, $project_id, $perPage, $type);
    }

    public function getDokumenStatistics()
    {
        return $this->dokumenRepository->getStatistics();
    }

    public function createDokumen(array $data)
    {
        // Tanggal upload default ke hari ini jika kosong
        if (empty($data['tanggal_upload'])) {
            $data['tanggal_upload'] = now();
        }

        $dokumen = $this->dokumenRepository->create($data);

        // Jika ada file dan tipe adalah file, simpan ke media library
        if (isset($data['file']) && $data['type'] === 'file') {
            $dokumen->addMedia($data['file'])->toMediaCollection('files');
        }

        return $dokumen;
    }

    public function updateDokumen(string $id, array $data)
    {
        $dokumen = $this->dokumenRepository->update($id, $data);

        if (isset($data['file']) && $data['type'] === 'file') {
            // Hapus media lama dan ganti dengan yang baru
            $dokumen->clearMediaCollection('files');
            $dokumen->addMedia($data['file'])->toMediaCollection('files');
        }

        return $dokumen;
    }

    public function getDokumenById(string $id)
    {
        return $this->dokumenRepository->getById($id);
    }
}
