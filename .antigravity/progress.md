# Project Progress & Roadmap

## ✅ Completed (Done)
- [x] **Project Initialization**: Setup Laravel 12 + Tailwind 4 + Vite 7.
- [x] **Authentication System**: Login & Register flow using Laravel Breeze.
- [x] **Database Foundation**: Migrations for ULID, Spatie Permission, and Media Library.
- [x] **Core Helpers**: implementation of `user()`, `tgl_indo()`, and `menus()` helpers.
- [x] **User Management**:
    - List users with server-side pagination & search.
    - Approve/Reject new user registration.
    - Reset password functionality.
    - Role assignment.
    - Modal Detail User.
    - **Activation/Deactivation Toggle** with AJAX.
- [x] **Role & Permission Management**:
    - CRUD for Roles and Permissions.
    - Interactive "Akses" (permission assignment) for Roles.
    - **Added "Activate" permission** to support granular activation control.
- [x] **Profile Management**:
    - Update profile info & password.
    - Avatar upload with automatic thumbnail conversion.
- [x] **Authentication & Onboarding**:
    - [x] **Google Socialite**: Integration for Google login.
    - [x] **Inactive Flow**: Seamless onboarding for users with incomplete profiles.
    - [x] **Secure Admin Activation**: Signed URL mechanism for one-click admin approval via email.
    - [x] **Dynamic Search Integration**: Support for deep-linking search results via URL parameters.
    - [x] **Passwordless OTP Login**: 2-step OTP verification with professional HTML email and 6-digit box UI.
    - [x] **Forgot Password Simplification**: Streamlined reset link flow with premium single-form UI.
- [x] **Menu System**: Database-driven sidebar menus with caching.

## 🚀 To-Do (Upcoming/Planned)
- [ ] **Kategori Management**: Modul untuk mengelola kategori (seperti kategori proyek/kegiatan).
- [ ] **Unit Kerja Management**: Modul untuk mengelola struktur organisasi/unit kerja.
- [ ] **Activity/Task Module**: Implementasi pembuatan aktivitas (berdasarkan history v1).
- [ ] **Monthly Report**: Optimasi dan implementasi laporan bulanan (berdasarkan history v1).
- [ ] **UI Polish**: Terus memantau konsistensi UI/UX pada modul baru.
- [ ] **Recaptcha Integration**: Penambahan keamanan pada form login/register.

## 📝 Notes
- Project ini menggunakan pendekatan **Premium Design**, jadi setiap fitur baru harus dipastikan memiliki UI/UX yang modern.
- Pastikan menjalankan `php artisan storage:link` jika avatar tidak muncul.
- Cache menu harus di-clear jika ada perubahan data pada tabel `menus`.
- Laravel Debugbar telah diinstal untuk mempermudah debugging query dan performa (dev only).
