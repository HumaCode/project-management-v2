<?php

namespace App\Services\KategoriDokumen;

use App\Interface\KategoriDokumen\KategoriDokumenRepositoryInterface;
use App\Interface\KategoriDokumen\KategoriDokumenServiceInterface;

class KategoriDokumenService implements KategoriDokumenServiceInterface
{
    protected $kategoriRepository;

    public function __construct(KategoriDokumenRepositoryInterface $kategoriRepository)
    {
        $this->kategoriRepository = $kategoriRepository;
    }

    public function getPaginatedKategoris(?string $search, int $rowPerPage)
    {
        return $this->kategoriRepository->getAll($search, $rowPerPage);
    }

    public function getKategoriById(string $id)
    {
        return $this->kategoriRepository->findById($id);
    }

    public function createKategori(array $data)
    {
        return $this->kategoriRepository->create($data);
    }

    public function updateKategori(string $id, array $data)
    {
        return $this->kategoriRepository->update($id, $data);
    }

    public function deleteKategori(string $id)
    {
        return $this->kategoriRepository->delete($id);
    }

    public function getKategoriStats()
    {
        return [
            'total' => $this->kategoriRepository->countAll(),
            'used' => $this->kategoriRepository->countUsedInDocuments(),
        ];
    }

    public function getAllKategoris()
    {
        return $this->kategoriRepository->all();
    }
}
