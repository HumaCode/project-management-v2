<?php

namespace App\Models\Shield;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasUlids;

    protected $fillable = [
        'name',
        'guard_name',
    ];
}
