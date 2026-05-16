<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Backup extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'system_backup_histories';

    protected $fillable = [
        'name',
        'type',
        'size',
        'status',
        'user_id'
    ];

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('backup_files')
            ->useDisk('local') // Disk local di project ini diarahkan ke storage/app/private
            ->singleFile();
    }

    /**
     * Relationship to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
