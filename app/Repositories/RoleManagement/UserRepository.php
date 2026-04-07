<?php

namespace App\Repositories\RoleManagement;

use App\Constants\GlobalMessages;
use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Models\Shield\Role;
use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        // Inject model Role ke BaseRepository
        parent::__construct($model);
    }

    public function getAll(?string $search, ?string $limit, ?string $status, ?string $type, bool $execute)
    {
        $query = $this->model->query(); // Gunakan $this->model dari BaseRepository

        if ($search) {
            $query->search($search);
        }
        if (! empty($status) && $status !== 'all') {
            $status === 'active' ? $query->active() : $query->inactive();
        }
        if ($limit) {
            $query->take((int) $limit);
        }

        $query->orderBy('id', 'desc');

        return $execute ? $query->get() : $query;
    }

    public function getAllPaginated(?string $search, ?string $status, ?string $type, ?int $rowsPerPage)
    {
        return $this->getAll($search, null, $status, $type, false)->paginate($rowsPerPage);
    }

    // OVERRIDE Create dari Base untuk menyisipkan data spesifik role
    public function create(array $data)
    {
        try {
            // Modifikasi array data sebelum dilempar ke mass-assignment BaseRepository
            $data['name'] = strtolower($data['name']);
            $data['slug'] = $data['slug'];
            $data['type_role'] = 'custom';
            $data['is_active'] = '1';
            $data['description'] = $data['description'];
            $data['guard_name'] = 'web';

            return parent::create($data);
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_CREATING.$e->getMessage());
        }
    }

    // OVERRIDE Update dari Base untuk menyisipkan data spesifik role
    public function update(string $id, array $data)
    {
        try {
            $data['name'] = strtolower($data['name']);
            $data['slug'] = $data['slug'];
            $data['type_role'] = $data['type_role'];
            $data['is_active'] = $data['is_active'];
            $data['description'] = $data['description'];

            return parent::update($id, $data);
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING.$e->getMessage());
        }
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
            if ($record->users()->exists()) {
                // Lempar error yang akan ditangkap oleh blok catch di bawah
                throw new \Exception('Role tidak dapat dihapus karena masih digunakan oleh user aktif.');
            }
            // -----------------------------------

            return parent::delete($id);
        } catch (\Exception $e) {
            // Karena kita melempar exception kustom di atas,
            // pesan 'Role tidak dapat dihapus...' akan digabungkan ke $e->getMessage()
            throw new \Exception(GlobalMessages::ERROR_DELETED.' '.$e->getMessage());
        }
    }

    public function approve(string $id)
    {
        try {
            $user = parent::getById($id);

            if (! $user) {
                throw new \Exception('User tidak ditemukan.');
            }

            $user->is_active = '1';
            $user->email_verified_at = now(); // Optional: set email_verified_at saat user disetujui
            $user->save();

            return $user;
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING.' '.$e->getMessage());
        }
    }

    public function reject(string $id)
    {
        try {
            $user = parent::getById($id);

            if (! $user) {
                throw new \Exception('User tidak ditemukan.');
            }

            $user->is_active = '0';
            $user->email_verified_at = null; // Optional: reset email_verified_at jika user ditolak
            $user->save();

            return $user;
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING.' '.$e->getMessage());
        }
    }

    public function getRoleActive()
    {
        return Role::where('is_active', '1')->get(['id', 'name', 'is_active']);
    }
}
