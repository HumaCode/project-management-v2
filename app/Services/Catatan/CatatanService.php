<?php

namespace App\Services\Catatan;

use App\Interface\Catatan\CatatanRepositoryInterface;
use App\Interface\Catatan\CatatanServiceInterface;
use Illuminate\Support\Facades\DB;

class CatatanService implements CatatanServiceInterface
{
    private CatatanRepositoryInterface $catatanRepository;

    public function __construct(CatatanRepositoryInterface $catatanRepository)
    {
        $this->catatanRepository = $catatanRepository;
    }

    public function getIndexData(): array
    {
        return $this->catatanRepository->getStatistics();
    }

    public function getPaginatedCatatans(?string $search, ?string $category, ?string $project_id, ?string $priority, int $perPage)
    {
        return $this->catatanRepository->getPaginated($search, $category, $project_id, $priority, $perPage);
    }

    public function storeCatatan(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->catatanRepository->create($data);
        });
    }

    public function getCatatanById(string $id)
    {
        return $this->catatanRepository->findById($id, ['user', 'project']);
    }

    public function updateCatatan(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->catatanRepository->update($id, $data);
        });
    }

    public function deleteCatatan(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->catatanRepository->delete($id);
        });
    }
}
