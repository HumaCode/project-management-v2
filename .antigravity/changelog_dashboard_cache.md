# Log Perubahan Arsitektur — 15 Mei 2026

## 1. Modul Dashboard (Dinamis & Teroptimasi)
- **Status**: Migrasi dari Hardcoded ke Dynamic.
- **Arsitektur**: Service-Repository Pattern.
- **Fitur Baru**:
    - Statistik Proyek Aktif, Dokumen, & Deadline diambil secara real-time.
    - Grafik "Project Baru per Bulan" (menggantikan grafik upload).
    - Grafik bersifat dinamis filling (stretch) mengikuti tinggi kartu.
    - Urutan bar grafik: dari bawah ke atas (Bottom-up).
- **File Terkait**:
    - `app/Services/Dashboard/DashboardService.php`
    - `app/Repositories/Dashboard/DashboardRepository.php`
    - `resources/views/pages/dashboard/dashboard-dev.blade.php`

## 2. Sistem Caching (Cache Versioning)
- **Status**: Implementasi Global Invalidation.
- **Inovasi**: Menggunakan `project_cache_version` untuk membersihkan cache secara massal tanpa merusak performa.
- **Penerapan**: 
    - Setiap kali ada `store`, `update`, atau `delete` proyek, versi cache dinaikkan (+1).
    - Dashboard dan Tabel Proyek akan otomatis memuat data terbaru segera setelah versi berubah.
- **Manfaat**: Menghilangkan masalah "data lama masih muncul" setelah input data baru.

## 3. Standar Pengurutan (Sorting)
- **Default**: Newest First (`created_at DESC`, `id DESC`).
- **Perbaikan**: Memperbaiki file `project.js` yang sebelumnya memaksa urutan `asc` (lama ke baru) secara hardcoded.

## 4. Perbaikan UI/UX Grafik
- **Layout**: Area grafik mengisi seluruh ruang kartu (`flex: 1`).
- **Animasi**: Batang grafik tumbuh dari bawah ke atas dengan durasi 1.2 detik.
- **Label**: Menggunakan nama bulan asli (Dec, Jan, Feb...) sesuai 6 bulan terakhir.

---
*Catatan ini dibuat otomatis oleh Antigravity Assistant.*
