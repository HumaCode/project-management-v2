# Project Analysis: Project Management V2

## Overview
Proyek ini merupakan sistem manajemen proyek (V2) yang dibangun menggunakan Laravel 12. Fokus utama saat ini adalah pada pondasi sistem, manajemen pengguna, dan peran (role management).

## Tech Stack
- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Frontend**: 
  - Tailwind CSS 4.0 (Modern styling)
  - Alpine.js (Lightweight interactivity)
  - Vite 7 (Build tool)
  - **CKEditor 5** (Rich text editing with media support)
  - **Flatpickr** (Premium date picker with custom themes)
  - **Monaco Editor**: Professional code editing for documentation.
  - **fslightbox**: Premium lightbox for image and media previews.
- **Database**: MySQL/MariaDB
- **Key Packages**:
  - `spatie/laravel-permission`: Manajemen Role & Permission.
  - `spatie/laravel-medialibrary`: Manajemen media (Avatar).
  - `laravel/socialite`: Integrasi Login Media Sosial (Google).
  - `laravel/breeze`: Starter kit untuk autentikasi.
  - `fruitcake/laravel-debugbar`: Tool debugging (dev only).

## Architecture Highlights
1.  **Identity Management**:
    - Menggunakan **ULID** (Universally Unique Lexicographically Sortable Identifier) sebagai primary key untuk keamanan dan skalabilitas.
    - Model `User` memiliki fitur:
        - `is_active`: Status aktivasi akun.
        - `google_id` & `is_socialite`: Untuk integrasi Socialite (Google).
        - Spatie Media Library terintegrasi untuk Avatar.
2.  **Advanced Authentication Flow**:
    - **Google Auth**: Pendaftaran otomatis via Socialite.
    - **Inactive Onboarding**: Pengguna melengkapi profil (username/phone) sebelum aktivasi.
    - **Secure Admin Activation**: Tautan email berbasis **Signed URL** untuk akses instan Admin yang aman.
3.  **Modular Controllers**:
    - Terpisah dalam namespace yang jelas: `RoleManagement`, `Setting`, `Auth`.
3.  **Global Helpers**:
    - `Helper.php`: Berisi fungsi `tgl_indo()`, `user()`, dan `menus()`.
    - `ResponseHelper.php`: Wrapper standar untuk JSON response.
4.  **Service-Repository Pattern**:
    - Diimplementasikan pada modul baru (misal: Project Management) untuk memisahkan logika bisnis dari kontroler.
    - Menggunakan interface untuk abstraksi (misal: `ProjectServiceInterface`).
5.  **Custom Menu System**:
    - Memiliki tabel `menus` dan `menu_permissions`.
    - Mendukung grouping berdasarkan kategori dan caching menggunakan `Cache::rememberForever`.
5.  **Controller Messages Pattern**:
    - Setiap modul memiliki class `Messages` (misal: `UserMessages`) untuk mengelola semua string statis.
    - String yang dikelola meliputi: Title, Subtitle, View Path, Route Name, Table ID, dan Pesan Success/Error.
    - Hal ini memudahkan manajemen konten tanpa harus menyentuh logika controller.
6.  **Advanced Resource Pagination**:
    - `PaginateResource.php` dikustomisasi untuk mendukung pembungkusan item (wrapping) dengan resource class tertentu.
    - Hal ini memungkinkan standarisasi format pagination sambil tetap menjaga transformasi data yang spesifik untuk setiap model.
9.  **Automatic Content Handling**:
    - Implementasi **Auto-Slug** pada model Project untuk URL yang SEO-friendly.
    - Penggunaan **Base64 Upload Adapter** pada CKEditor untuk menangani gambar tanpa konfigurasi storage yang rumit di awal.

## Current Modules & Features
- **Dashboard**: Panel ringkasan utama.
- **Role & Permission Management**: 
  - CRUD Roles & Permissions.
  - Pengaturan akses (assign permission ke role).
- **User Management**:
  - CRUD Pengguna.
  - Alur Approve/Reject untuk aktivasi akun.
  - Reset Password.
  - Modal Detail User (AJAX-based).
  - Pencarian dan filter berdasarkan Role/Status.
- **Profile Settings**:
  - Update data diri dan password.
  - Upload avatar dengan single-file collection (otomatis hapus file lama).
- **Authentication**: Login, Register, Forgot Password via Laravel Breeze.
- **Project Management**:
  - Manajemen list proyek dengan status & progress.
  - **Arsitektur Berbasis Tim**: Penugasan proyek kini dilakukan per-Tim (Team-based) untuk kolaborasi yang lebih baik.
  - Server-side data table & filtering.
  - **WhatsApp-Style Diskusi**: Sistem obrolan interaktif terintegrasi dengan gaya WhatsApp (Me vs Others, bottom-to-top flow).
  - **Secure Media System**: Penanganan lampiran file/gambar secara privat menggunakan Spatie Media Library dengan akses aman via controller.
  - **Reply & Quote Logic**: Fitur balas pesan dengan kutipan teks/gambar dan navigasi scroll otomatis ke pesan asli.
  - **Edit & Delete Policy**: Kontrol integritas diskusi dengan gembok waktu 5 menit untuk pengeditan dan penghapusan pesan oleh pemilik.
  - **Premium PIC Rendering**: Logika otomatis untuk menampilkan Avatar (Google/Spatie) atau Inisial Nama (Fallback).
  - **Advanced Project Creation**: Form input premium dengan dukungan Rich Text, Media Embed, Thumbnail Upload (Media Library), dan Project Color.
  - **AJAX Store Logic**: Pengiriman data menggunakan Axios dengan validasi StoreProjectRequest.
  - Integrasi desain premium (Glassmorphism).
- **Documentation Builder**:
  - Manajemen dokumen (Single File, Manual Book, Code).
  - Integrasi penuh dengan Spatie Media Library.
  - Editor penulisan kode profesional via Monaco Editor.
  - CRUD & Detail modal interaktif dengan pratinjau cerdas.
  - Filter data yang kuat (Kategori, Project, Tipe).
- **Catatan (Notes) Module**:
  - Manajemen catatan personal atau terkait proyek.
  - CRUD penuh menggunakan AJAX dengan notifikasi toast.
  - Integrasi CKEditor 5 untuk penulisan isi catatan yang kaya (rich-text).
  - Filter interaktif berbasis Select2 (Project) dan Debounced Search.
  - Dashboard statistik real-time untuk ringkasan catatan.
- **Team Management Module**:
  - Pengelolaan tim kerja lintas proyek.
  - Fitur penugasan peran (role) dinamis untuk setiap anggota tim.
  - Dashboard statistik tim dan modal detail premium.
  - Eager loading untuk optimasi performa avatar dan role.
  - Integrasi penuh dengan sistem loading global (SCA.loading).
- **UI/UX Standardization (Standard Terbaru)**:
  - **Breadcrumb**: Dipindahkan ke sisi kanan atas agar konsisten dengan modul `Project Index` dan `User Management`.
  - **Header Grid**: Menggunakan struktur `ph-wrap` dengan `align-items: center` untuk perataan vertikal elemen judul dan navigasi.
  - **Responsive Actions**: Menggunakan Bootstrap Grid (`col-6`, `col-sm-auto`) pada area aksi untuk memastikan tombol tetap fluid dan rapi di mobile.
  - **Feature Decoupling**: Fungsionalitas upload dokumen kini dipusatkan hanya di modul **Dokumentasi** untuk menghindari redundansi logika dan UI di halaman detail proyek.

## Database Schema (Current)
- `users`: `id` (ULID), `username` (nullable), `google_id`, `gender`, `phone`, `avatar`, `is_active`, `is_socialite`.
- `permissions`, `roles`, `model_has_permissions`, dll (Spatie standard).
- `menus`, `menu_permissions`.
- `media`: Spatie Media Library.
- `projects`: `id` (ULID), `name`, `slug`, `description`, `notes`, `status` (enum), `priority` (enum), `color`, `icon`, `start_date`, `deadline`, `progress`, `actual_finished_at`, `team_id`, `created_by`.
- `dokumens`: `id` (ULID), `nama`, `versi`, `kategori`, `type` (file/article/code), `project_id`, `user_id`.
- `dokumen_items`: `id` (ULID), `dokumen_id`, `title`, `content`, `order`.
- `catatans`: `id` (ULID), `title`, `category`, `priority`, `content`, `project_id`, `user_id`.
- `diskusis`: `id` (ULID), `project_id`, `user_id`, `parent_id` (Reply reference), `content`.
- `teams`: `id` (ULID), `name`, `description`, `created_by`.
- `team_user`: `team_id`, `user_id`, `role` (Pivot untuk peran di tim).

