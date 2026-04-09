<?php

namespace App\Interface\Setting;

use App\Interface\BaseRepositoryInterface;

interface ProfileRepositoryInterface extends BaseRepositoryInterface
{
    public function getProfileByUserId(string $id);

    public function update(string $id, array $data);
}
