<?php

namespace App\Models\Konfigurasi;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $fillable = [
        'menu_id',
        'permission_id',
    ];

    protected $table = 'menu_permission';
}
