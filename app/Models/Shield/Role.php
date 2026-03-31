<?php

namespace App\Models\Shield;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'type_role',
        'is_active',
        'description',
        'guard_name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'created_at_indo',
        'updated_at_indo',
        'permission_count_label',
        'permissions_percentage',
        'users_count_label',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    public function scopeRoleType($query, ?string $type)
    {
        if (!$type) {
            return $query;
        }

        return $query->where('type_role', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', '0');
    }



    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getCreatedAtIndoAttribute(): ?string
    {
        if (!$this->created_at) {
            return null;
        }

        // Lebih aman gunakan format langsung daripada helper global
        return tgl_indo($this->created_at);
    }

    public function getUpdatedAtIndoAttribute(): ?string
    {
        // 1. Pastikan updated_at memiliki nilai
        if (!$this->updated_at) {
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

    public function getPermissionCountLabelAttribute()
    {
        if (strtolower($this->name ?? '') === 'dev') {
            return '∞';
        }

        $count = $this->attributes['permissions_count'] ?? $this->permissions()->count();
        return $count;
    }

    public function getPermissionsPercentageAttribute()
    {
        // 1. Jika role adalah 'dev', langsung berikan 100%
        if (strtolower($this->name ?? '') === 'dev') {
            return 100;
        }

        // 2. Ambil total seluruh permission yang ada di database
        // (Lihat tips performa di bawah untuk optimasi query ini)
        $totalPermissionsInSystem = Permission::count();

        // 3. Hindari error Division by Zero jika belum ada permission sama sekali
        if ($totalPermissionsInSystem === 0) {
            return 0;
        }

        // 4. Ambil jumlah permission yang dimiliki role ini (menggunakan logika yang sama dengan label Anda)
        $rolePermissionsCount = $this->attributes['permissions_count'] ?? $this->permissions()->count();

        // 5. Hitung persentase dan bulatkan (misal: 50.5% jadi 51%)
        $percentage = ($rolePermissionsCount / $totalPermissionsInSystem) * 100;

        return round($percentage);
    }

    public function getUsersCountLabelAttribute()
    {
        return $this->attributes['users_count'] ?? $this->users()->count();
    }
}
