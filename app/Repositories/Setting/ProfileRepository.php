<?php

namespace App\Repositories\Setting;

use App\Interface\Setting\ProfileRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;

class ProfileRepository extends BaseRepository implements ProfileRepositoryInterface
{
    public function __construct(User $model)
    {
        // Inject model Role ke BaseRepository
        parent::__construct($model);
    }

    public function getProfileByUserId(string $id)
    {
        return $this->model->findOrFail($id);
    }

    public function update(string $id, array $data)
    {
        $profile = $this->model->findOrFail($id);

        $profile->update($data);

        return $profile;
    }
}
