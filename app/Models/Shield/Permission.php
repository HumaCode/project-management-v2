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

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'created_at_indo',
        'updated_at_indo',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    public function getCreatedAtIndoAttribute()
    {
        if (! $this->created_at) {
            return null;
        }

        return tgl_indo($this->created_at);
    }

    /**
     * The menus that belong to the Permission
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class);
    }

    public function getUpdatedAtIndoAttribute(): ?string
    {
        // 1. Pastikan updated_at memiliki nilai
        if (! $this->updated_at) {
            return null;
        }

        // 2. Bandingkan updated_at dengan created_at
        // Kita gunakan toDateTimeString() (format Y-m-d H:i:s) untuk menghindari
        // bug perbedaan microsecond (milidetik) yang kadang terjadi saat database menyimpan data
        if ($this->created_at && $this->updated_at->toDateTimeString() === $this->created_at->toDateTimeString()) {
            return '-';
        }

        // 3. Jika nilainya berbeda (sudah pernah diupdate), tampilkan format Indo
        return tgl_indo($this->updated_at);
    }
}
