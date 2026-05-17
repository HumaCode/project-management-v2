<?php

namespace App\Models\Konfigurasi;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class MenuPermission extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('menu_permission');
    }
    protected $fillable = [
        'menu_id',
        'permission_id',
    ];

    protected $table = 'menu_permission';
}
