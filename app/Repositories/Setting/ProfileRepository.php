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
        $user = $this->model->findOrFail($id);

        // 1. Ambil file avatar dari array data (jika ada)
        $avatar = $data['avatar'] ?? null;

        // 2. Hapus 'avatar' dari array agar tidak ikut tersimpan di update teks biasa
        unset($data['avatar']);

        // 3. Update data teks ke tabel users
        $user->update($data);

        // 4. Handle upload gambar menggunakan Spatie
        // Karena kita tidak memakai Request, kita gunakan addMedia() yang menerima UploadedFile
        if ($avatar) {
            $user->addMedia($avatar)
                ->toMediaCollection('avatar');
        }

        return $user;
    }
}
