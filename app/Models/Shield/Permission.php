<?php

namespace App\Models\Shield;

use App\Models\Konfigurasi\Menu;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasUlids;

    protected $fillable = ['name', 'guard_name'];

    protected $appends = [
        'created_at_indo',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    public function getCreatedAtIndoAttribute()
    {
        if (!$this->created_at) {
            return null;
        }

        return tgl_indo($this->created_at);
    }

    /**
     * The menus that belong to the Permission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
    }
}
