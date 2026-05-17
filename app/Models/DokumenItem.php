<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class DokumenItem extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName('dokumen_item');
    }

    protected $fillable = [
        'dokumen_id',
        'type',
        'content',
        'metadata',
        'order',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the master document that this item belongs to.
     */
    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class);
    }
}
