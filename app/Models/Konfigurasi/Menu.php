<?php

namespace App\Models\Konfigurasi;

use App\Models\Shield\Permission;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'url',
        'category',
        'icon',
        'is_active',
        'orders',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('menus');
            Cache::forget('urlMenu');
        });

        static::deleted(function () {
            Cache::forget('menus');
            Cache::forget('urlMenu');
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
        });
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_permission', 'menu_id', 'permission_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getCountAttribute()
    {
        // if ($this->url == 'project') {
        //     return \App\Models\Project::count(); // Ambil jumlah asli dari tabel project
        // }

        // if ($this->url == 'dokumen') {
        //     return \App\Models\Document::count();
        // }

        return null;
    }
}
