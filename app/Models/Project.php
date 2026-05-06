<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'deadline',
        'progress',
        'created_by',
    ];

    /**
     * Get the user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the PICs (users) assigned to the project.
     */
    public function pics(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
}
