<?php

namespace App\Listeners;

use App\Models\Backup;
use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BackupSuccessfulListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BackupWasSuccessful $event): void
    {
        try {
            // Di Laravel Backup v10, kita perlu mengambil destinasi backup secara manual
            $backupDestination = \Spatie\Backup\BackupDestination\BackupDestination::create($event->diskName, $event->backupName);
            $newestBackup = $backupDestination->newestBackup();

            if (!$newestBackup) {
                return;
            }

            $relativePath = $newestBackup->path();
            $filename = basename($relativePath);

            // Cek apakah file ini sudah pernah dicatat (mencegah duplikasi)
            if (\App\Models\Backup::where('name', $filename)->exists()) {
                return;
            }

            $disk = \Illuminate\Support\Facades\Storage::disk($event->diskName);
            $absolutePath = $disk->path($relativePath);

            // Simpan record ke database
            $backup = \App\Models\Backup::create([
                'name' => $filename,
                'disk' => $event->diskName,
                'path' => $relativePath,
                'size' => $this->formatBytes($disk->size($relativePath)),
                'type' => request()->has('only-db') ? 'manual' : 'auto', // Deteksi jika dijalankan via controller
                'user_id' => auth()->id(),
            ]);

            // Pindahkan file ke Media Library (Private Storage)
            $backup->addMedia($absolutePath)
                  ->toMediaCollection('backup_files');

            // Hapus file asli dari disk backup (opsional, karena sudah dipindah ke media)
            // $disk->delete($relativePath);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Backup Listener Error: ' . $e->getMessage());
        }
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
