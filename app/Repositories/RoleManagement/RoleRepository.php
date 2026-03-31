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

    public function getMainMenuWithPermissions()
    {
        return Menu::with('permissions')->get();
    }
}
