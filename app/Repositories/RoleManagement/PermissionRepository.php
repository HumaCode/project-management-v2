<?php

namespace App\Repositories\RoleManagement;

use App\Constants\GlobalMessages;
use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Models\Shield\Permission;
use App\Repositories\BaseRepository;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function __construct(Permission $model)
    {
        // Inject model ke BaseRepository
        parent::__construct($model);
    }

    public function getAll(?string $search, ?string $limit, bool $execute)
    {
        $query = $this->model->query(); // Gunakan $this->model dari BaseRepository

        if ($search) {
            $query->search($search);
        }

        if ($limit) {
            $query->take((int) $limit);
        }

        $query->orderBy('id', 'desc');

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?int $rowsPerPage)
    {
        return $this->getAll($search, null, false)->paginate($rowsPerPage);
    }

    public function delete(string $id)
    {
        try {
            $record = parent::getById($id);

            if (! $record) {
                return false;
            }

            // --- TAMBAHAN PENGECEKAN DI SINI ---
            // Menggunakan exists() lebih cepat daripada count() karena
            // query akan langsung berhenti ketika menemukan 1 data pertama.
            if ($record->roles()->exists()) {
                throw new \Exception('Permission tidak dapat dihapus karena masih terkait dengan role.');
            }

            if ($record->menus()->exists()) {
                throw new \Exception('Permission tidak dapat dihapus karena masih terkait dengan menu.');
            }
            // -----------------------------------

            return parent::delete($id);
        } catch (\Exception $e) {
            // Karena kita melempar exception kustom di atas,
            // pesan 'Role tidak dapat dihapus...' akan digabungkan ke $e->getMessage()
            throw new \Exception(GlobalMessages::ERROR_DELETED.' '.$e->getMessage());
        }
    }
}
