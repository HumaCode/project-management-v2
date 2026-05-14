<?php

namespace App\Interface\KategoriDokumen;

interface KategoriDokumenServiceInterface
{
    public function getPaginatedKategoris(?string $search, int $rowPerPage);
    public function getKategoriById(string $id);
    public function createKategori(array $data);
    public function updateKategori(string $id, array $data);
    public function deleteKategori(string $id);
    public function getKategoriStats();
    public function getAllKategoris();
}
