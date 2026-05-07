<?php

namespace App\Repositories\RoleManagement;

use App\Constants\GlobalMessages;
use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Models\Shield\Role;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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

        // --- FILTER TYPE (ROLE) ---
        // Cek jika $type ada isinya dan bukan 'all'
        if (! empty($type) && $type !== 'all') {
            // Gunakan scope bawaan Spatie untuk memfilter user berdasarkan nama role
            $query->role($type);

            // Catatan: Jika kamu masih mempertahankan function scopeRoleType()
            // di model User dari obrolan sebelumnya, kamu juga bisa memakai:
            // $query->roleType($type);
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

    public function create(array $data)
    {
        try {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            // Jika dibuat melalui admin panel, otomatis diverifikasi & aktif
            $data['email_verified_at'] = now();
            if (!isset($data['is_active'])) {
                $data['is_active'] = '1';
            }

            // Pisahkan role dari data mass-assignment
            $roleName = $data['role'] ?? null;
            unset($data['role']);

            $user = parent::create($data);

            if ($roleName) {
                $user->assignRole($roleName);
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_CREATING . $e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        try {
            if (isset($data['password']) && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Pisahkan role dari data mass-assignment
            $roleName = $data['role'] ?? null;
            unset($data['role']);

            $user = parent::update($id, $data);

            if ($roleName) {
                $user->syncRoles($roleName);
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING . $e->getMessage());
        }
    }

    public function delete(string $id)
    {
        try {
            $user = parent::getById($id);

            if (!$user) {
                return false;
            }

            // Mencegah menghapus diri sendiri
            if ($user->id === auth()->id()) {
                throw new \Exception('Anda tidak dapat menghapus akun Anda sendiri.');
            }

            return parent::delete($id);
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_DELETED . ' ' . $e->getMessage());
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
            $user->email_verified_at = Carbon::now(); // Optional: set email_verified_at saat user disetujui
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

    public function resetPassword(string $id, array $data)
    {
        try {
            // Cari user
            $user = $this->model->where('id', $id)->first();

            if (! $user) {
                throw new \Exception('User tidak ditemukan.');
            }

            // MODE 1: MANUAL (Admin menginputkan password sendiri)
            if (($data['mode'] ?? '') === 'manual') {

                if (empty($data['newPassword'])) {
                    throw new \Exception('Password baru tidak boleh kosong.');
                }

                $user->password = Hash::make($data['newPassword']);
                $user->save();

                // RETURN OBJEK USER (Bukan Array)
                return $user;
            }

            // MODE 2: LINK (Kirim email ke user)
            elseif (($data['mode'] ?? '') === 'link') {

                $status = Password::broker()->sendResetLink(
                    ['email' => $user->email]
                );

                if ($status !== Password::RESET_LINK_SENT) {
                    throw new \Exception(__($status));
                }

                // Jika kirim link sukses, password di database tidak berubah,
                // tapi kita tetap return objek User agar Resource di Controller tidak error.
                return $user;
            }

            // Jika mode tidak dikenali
            throw new \Exception('Metode reset password tidak valid.');
        } catch (\Exception $e) {
            throw new \Exception(GlobalMessages::ERROR_UPDATING.' '.$e->getMessage());
        }
    }

    public function getRoleActive()
    {
        return Role::where('is_active', '1')->get(['id', 'name', 'is_active']);
    }

    public function countAllUser()
    {
        return $this->model->count();
    }

    public function countAllUserActive()
    {
        return $this->model->where('is_active', '1')->count();
    }

    public function countAllUserInactive()
    {
        return $this->model->where('is_active', '0')->count();
    }

    public function countNewUser(int $days = 7)
    {
        return $this->model
            ->where('created_at', '>=', now()->subDays($days))
            ->count();
    }

    public function getUsersByRole(string $role)
    {
        return $this->model->role($role)->where('is_active', '1')->get();
    }
}
