<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Diskusi extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('diskusi');
    }

    protected $fillable = [
        'project_id',
        'user_id',
        'parent_id',
        'content',
    ];

    /**
     * Get the message being replied to.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Diskusi::class, 'parent_id');
    }

    /**
     * Get the replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(Diskusi::class, 'parent_id');
    }

    /**
     * Get the user that wrote the discussion.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project the discussion belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Helper for human-friendly time.
     */
    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
