<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Dokumen extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia;

    protected $fillable = [
        'project_id',
        'user_id',
        'nama',
        'versi',
        'kategori',
        'type',
        'status',
        'tanggal_upload',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    /**
     * Get the project that the document belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the items (content) of the document.
     */
    public function items(): HasMany
    {
        return $this->hasMany(DokumenItem::class, 'dokumen_id')->orderBy('order');
    }

    /**
     * Map category code to label.
     */
    public function getKategoriLabelAttribute(): string
    {
        $map = [
            's' => 'Spesifikasi',
            'r' => 'RAB / Anggaran',
            'l' => 'Laporan',
            'c' => 'Source Code',
            'b' => 'Berita Acara',
            'd' => 'Desain',
        ];

        return $map[$this->kategori] ?? 'Tidak Diketahui';
    }
}
