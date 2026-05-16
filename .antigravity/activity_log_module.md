# Modul Log Aktivitas Sistem (AJAX-Powered)

Modul ini diimplementasikan untuk mencatat, menampilkan, dan mengekspor seluruh aktivitas sistem menggunakan paket `spatie/laravel-activitylog` dengan antarmuka bertema *cyberpunk* yang konsisten dengan dashboard utama.

## 1. Fitur Utama
- **AJAX Loading**: Data log dimuat tanpa reload halaman untuk pengalaman pengguna yang mulus.
- **Filter Dinamis**: Pencarian berdasarkan:
  - **Rentang Tanggal**: Menggunakan `flatpickr` dengan mode range.
  - **Aksi**: Created, Updated, Deleted, Login, dll.
  - **Modul**: System, Project, Document.
- **Export Excel (XLS)**: Ekspor data yang disaring ke format Excel dengan penataan (styling) profesional.
- **Paginasi Cyberpunk**: Navigasi halaman yang disesuaikan dengan estetika gelap dashboard.

## 2. Struktur File
- **Controller**: `app/Http/Controllers/SettingController.php`
  - `activities()`: Menangani request data via AJAX.
  - `exportActivities()`: Menangani ekspor data ke Excel (HTML Table format).
- **Routes**: `routes/web.php`
  - `settings.activities` (GET)
  - `settings.activities.export` (GET)
- **Views**:
  - `resources/views/pages/setting/index.blade.php`: Container utama dan UI Filter/Export Bar.
  - `resources/views/pages/setting/partials/_activity_log.blade.php`: Tabel data log aktivitas.
- **Assets**:
  - `public/assets/auth/backend/js/setting.js`: Logika AJAX, Event Delegation, dan inisialisasi Flatpickr.

## 3. Detail Implementasi Teknik
### Logika Filter Tanggal
Backend menggunakan `preg_split` untuk menangani pemisah rentang tanggal fleksibel (" to " atau " - ") dari Flatpickr:
```php
$dates = preg_split('/ (to|-) /', $dateInput);
if (count($dates) == 2) {
    $query->whereBetween('created_at', [trim($dates[0]) . ' 00:00:00', trim($dates[1]) . ' 23:59:59']);
}
```

### Ekspor Excel
Menggunakan teknik stream response dengan format tabel HTML agar Excel dapat membaca styling (warna header) dan format kolom secara otomatis tanpa library tambahan (Laravel Excel).

## 4. Cara Penggunaan
1. Buka menu **Setting** > **Backup & Maintenance**.
2. Klik tombol **Filter** untuk menyaring data di tabel.
3. Klik tombol **Export** untuk membuka panel ekspor, pilih tanggal, dan klik **Download Excel**.

---
*Dokumentasi diperbarui: 2026-05-16*
