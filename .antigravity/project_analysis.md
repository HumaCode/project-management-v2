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
- **Database**: MySQL/MariaDB
- **Key Packages**:
  - `spatie/laravel-permission`: Manajemen Role & Permission.
  - `spatie/laravel-medialibrary`: Manajemen media (Avatar).
  - `laravel/breeze`: Starter kit untuk autentikasi.
  - `fruitcake/laravel-debugbar`: Tool debugging (dev only).

## Architecture Highlights
1.  **Identity Management**:
    - Menggunakan **ULID** (Universally Unique Lexicographically Sortable Identifier) sebagai primary key untuk keamanan dan skalabilitas.
    - Model `User` memiliki fitur:
        - `is_active`: Status aktivasi akun (mendukung alur persetujuan admin).
        - Spatie Media Library terintegrasi untuk Avatar dengan konversi otomatis ke thumbnail.
        - Custom Accessors untuk format tanggal Indonesia (`tgl_indo`) dan inisial nama.
2.  **Modular Controllers**:
    - Terpisah dalam namespace yang jelas: `RoleManagement`, `Setting`, `Auth`.
3.  **Global Helpers**:
    - `Helper.php`: Berisi fungsi `tgl_indo()`, `user()`, dan `menus()`.
    - `ResponseHelper.php`: Wrapper standar untuk JSON response.
4.  **Custom Menu System**:
    - Memiliki tabel `menus` dan `menu_permissions`.
    - Mendukung grouping berdasarkan kategori dan caching menggunakan `Cache::rememberForever`.

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

## Database Schema (Current)
- `users`: Ditambah kolom `username`, `phone`, `avatar`, `is_active`, dan menggunakan ULID.
- `permissions`, `roles`, `model_has_permissions`, dll (Spatie standard).
- `menus`: `id`, `name`, `url`, `icon`, `category`, `orders`, `is_active`.
- `menu_permissions`: Mapping antara menu dan permission.
- `media`: Tabel standar Spatie Media Library.
