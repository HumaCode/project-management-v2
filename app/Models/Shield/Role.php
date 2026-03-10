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
}
