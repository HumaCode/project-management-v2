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
            $catatan = $this->catatanRepository->create($data);
            $this->clearCatatanCache();
            return $catatan;
        });
    }

    public function getCatatanById(string $id)
    {
        return $this->catatanRepository->findById($id, ['user', 'project']);
    }

    public function updateCatatan(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $catatan = $this->catatanRepository->update($id, $data);
            $this->clearCatatanCache();
            return $catatan;
        });
    }

    public function deleteCatatan(string $id)
    {
        return DB::transaction(function () use ($id) {
            $result = $this->catatanRepository->delete($id);
            $this->clearCatatanCache();
            return $result;
        });
    }

    private function clearCatatanCache(): void
    {
        $user = auth()->user();
        if ($user) {
            \Illuminate\Support\Facades\Cache::forget("catatan_stats_user_{$user->id}");
        }
    }
}
