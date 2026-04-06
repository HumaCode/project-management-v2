<?php

namespace App\Repositories\RoleManagement;

use App\Constants\GlobalMessages;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Models\Konfigurasi\Menu;
use App\Models\Shield\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        // Inject model Role ke BaseRepository
        parent::__construct($model);
    }

    public function getAll(?string $search,  ?string $limit, ?string $status, bool $execute)
    {
        $query = $this->model->query(); // Gunakan $this->model dari BaseRepository

        if ($search) $query->search($search);
        if (!empty($status) && $status !== 'all') {
            $status === 'active' ? $query->active() : $query->inactive();
        }
        if ($limit) $query->take((int)$limit);

        $query->orderBy('id', 'desc')->with(['permissions', 'users']);

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?string $status, ?int $rowsPerPage)
    {
        return $this->getAll($search, null, $status, false)->paginate($rowsPerPage);
    }

    // OVERRIDE Create dari Base untuk menyisipkan data spesifik role
    public function create(array $data)
    {
        try {
            // Modifikasi array data sebelum dilempar ke mass-assignment BaseRepository
            $data['name']           = strtolower($data['name']);
            $data['slug']           = $data['slug'];
            $data['type_role']      = 'custom';
            $data['is_active']      = '1';
            $data['description']    = $data['description'];
            $data['guard_name']     = "web";


            return parent::create($data);
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_CREATING . $e->getMessage());
        }
    }

    // OVERRIDE Update dari Base untuk menyisipkan data spesifik role
    public function update(string $id, array $data)
    {
        try {
            $data['name']           = strtolower($data['name']);
            $data['slug']           = $data['slug'];
            $data['type_role']      = $data['type_role'];
            $data['is_active']      = $data['is_active'];
            $data['description']    = $data['description'];

            return parent::update($id, $data);
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING . $e->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $record = parent::getById($id);

            if (!$record) {
                return false;
            }

            // --- TAMBAHAN PENGECEKAN DI SINI ---
            // Menggunakan exists() lebih cepat daripada count() karena
            // query akan langsung berhenti ketika menemukan 1 data pertama.
            if ($record->users()->exists()) {
                // Lempar error yang akan ditangkap oleh blok catch di bawah
                throw new \Exception('Role tidak dapat dihapus karena masih digunakan oleh user aktif.');
            }
            // -----------------------------------

            return parent::delete($id);
        } catch (\Exception $e) {
            // Karena kita melempar exception kustom di atas,
            // pesan 'Role tidak dapat dihapus...' akan digabungkan ke $e->getMessage()
            throw new \Exception(GlobalMessages::ERROR_DELETED . ' ' . $e->getMessage());
        }
    }

    public function syncPermissions(string $id, array $data)
    {
        try {
            $role = parent::getById($id);

            if (!$role) {
                throw new \Exception(GlobalMessages::NOT_FOUND);
            }

            // 1. Eksekusi sinkronisasi ke Database
            $role->syncPermissions($data['permissions'] ?? []);

            // 2. 🔥 WAJIB: Refresh relasi permission agar memuat data terbaru dari DB
            $role->load('permissions');

            return $role->permissions;

        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING . ' ' . $e->getMessage());
        }
    }

    public function getMainMenuWithPermissions()
    {
        return Menu::with('permissions')->get();
    }
}
