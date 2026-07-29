<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Catatan extends Model implements HasMedia
{
    use HasFactory, HasUlids, LogsActivity, InteractsWithMedia;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('catatan');
    }

    protected $fillable = [
        'title',
        'category',
        'priority',
        'date',
        'content',
        'project_id',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $appends = ['created_at_human'];

    public function getCreatedAtHumanAttribute(): string
    {
        return $this->created_at ? $this->created_at->diffForHumans() : '-';
    }

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

    /**
     * Register media collections for Catatan.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('catatan_attachments')
            ->useDisk('local');
    }
}
