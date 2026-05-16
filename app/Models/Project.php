<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Project extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('project');
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'priority',
        'start_date',
        'deadline',
        'progress',
        'notes',
        'actual_finished_at',
        'created_by',
        'team_id',
        'color',
        'icon',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'actual_finished_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = \Illuminate\Support\Str::slug($project->name) . '-' . \Illuminate\Support\Str::random(5);
            }
        });
    }

    /**
     * Get the user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the team assigned to the project.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the PICs (users) assigned to the project.
     */
    public function pics(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    /**
     * Get the documents for the project.
     */
    public function dokumens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Get the notes for the project.
     */
    public function catatans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Catatan::class);
    }

    /**
     * Get the discussions for the project.
     */
    public function diskusis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Diskusi::class);
    }

    /**
     * Get the generated reports for the project.
     */
    public function laporans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Laporan::class);
    }
}
