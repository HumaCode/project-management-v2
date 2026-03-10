<?php

namespace App\Models\Shield;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'type_role',
        'description',
        'is_active',
        'guard_name',
    ];
}
