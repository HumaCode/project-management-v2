<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Catatan extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'category',
        'priority',
        'content',
        'project_id',
        'user_id',
    ];

    /**
     * Get the user who owns the note.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the note.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
