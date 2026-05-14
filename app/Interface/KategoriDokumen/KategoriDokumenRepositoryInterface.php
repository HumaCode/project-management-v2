<?php

namespace App\Interface\KategoriDokumen;

interface KategoriDokumenRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage);
    public function findById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
    public function all();
    public function countAll(): int;
    public function countUsedInDocuments(): int;
}
