# Database Backup System

## Overview
Sistem backup pada Project Management v2 dirancang untuk memberikan keamanan data maksimal dengan pengalaman pengguna yang modern (AJAX-based). Sistem ini menggunakan `spatie/laravel-backup` sebagai engine utama dan `spatie/laravel-medialibrary` untuk manajemen penyimpanan file yang aman.

## Architecture
1. **Engine**: `spatie/laravel-backup` v10.
2. **Trigger**: Manual trigger via `SettingController@runBackup` (menggunakan `Artisan::call`).
3. **Storage Strategy**:
   - File backup pertama kali dibuat di disk `local` (folder `backups`).
   - `BackupWasSuccessful` event dipicu oleh Spatie.
   - `BackupSuccessfulListener` menangkap event tersebut, memindahkan file zip ke **Private Storage** (Media Library) milik model `Backup`.
   - File asli di disk backup dihapus (opsional/otomatis dikelola media library).
4. **Security**: File backup tidak dapat diakses langsung via URL publik. Download dilayani melalui route `settings.download-backup` dengan proteksi middleware auth.

## Technical Components
### Model & Migration
- **Model**: `App\Models\Backup`
- **Table**: `backups` (ULID primary key).
- **Media Collection**: `backup_files` (disk: `local`, private).

### Event Listener
- **Event**: `Spatie\Backup\Events\BackupWasSuccessful`
- **Listener**: `App\Listeners\BackupSuccessfulListener`
- **Logic**: 
  - Mencari file terbaru menggunakan `BackupDestination::create($event->diskName, $event->backupName)->newestBackup()`.
  - Validasi keunikan nama file untuk mencegah double record.
  - Attachment ke Media Library.

### UI / AJAX Flow
- **Container**: `#backupHistoryContainer` (Partial: `_backup_list.blade.php`).
- **Scrollable**: Kontainer memiliki `max-height: 400px` dengan custom scrollbar warna cyan.
- **Loading State**: Menggunakan `SCA.loading()` selama proses backup berjalan. Tombol dinonaktifkan untuk mencegah klik ganda.

## Future Improvements (Planned)
- [ ] **Cloud Backup**: Integrasi dengan Google Drive Storage (Off-site backup).
- [ ] **Queueing**: Memindahkan proses backup ke Queue Job jika ukuran database sudah sangat besar agar tidak memblokir worker Octane.
- [ ] **Auto-Cleanup**: Sinkronisasi cleanup Spatie dengan penghapusan record di tabel `backups`.
