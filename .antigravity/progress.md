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
    - [x] **Inactive Flow 2.0**: Onboarding super dinamis dengan real-time progress bar (6 fields), visual step-tracking (checklist), dan proteksi pengiriman ganda.
    - [x] **Secure Admin Activation**: Signed URL mechanism for one-click admin approval via email.
    - [x] **Dynamic Search Integration**: Support for deep-linking search results via URL parameters.
    - [x] **Passwordless OTP Login**: 2-step OTP verification with professional HTML email and 6-digit box UI.
    - [x] **Forgot Password Simplification**: Streamlined reset link flow with premium single-form UI.
    - [x] **Security Hardening**: Implemented route-level rate limiting (throttling) for all authentication entry points.
- [x] **Menu System**: Database-driven sidebar menus with caching.
- [x] **Project Management (Phase 1: Listing)**:
    - [x] **Database & Relation**: Migration with ULID support and PIC (many-to-many) relationship.
    - [x] **Architecture**: Service-Repository pattern with `ProjectServiceInterface`.
    - [x] **Data Table**: Server-side pagination, sorting, and dynamic search (AJAX).
    - [x] **Premium UI**: Integration of Glassmorphism design from reference HTML.
    - [x] **Stats Overview**: Dashboard-style metrics (Total, In Progress, Done, To Do).
    - [x] **Navigation Fixes**: Singular-to-plural redirect (`/project` -> `/projects`) and menu URL syncing.
    - [x] **Refined PIC Rendering**: Automated fallback from Google Avatar -> Spatie Media -> Initials (e.g., "HZ").
    - [x] **PaginateResource Fix**: Enabled item-wrapping for consistent resource transformation.

- [x] **Project Management (Phase 2: Create & Store Logic)**:
    - [x] **Database Evolution**: Migration for `slug`, `priority`, `notes`, `actual_finished_at`, `color`, and `icon`.
    - [x] **Premium Create View**: 
        - Integrated **CKEditor 5 (v41.1.0)** with multi-line toolbar and Base64 image support.
        - Integrated **Flatpickr** with Dark Glassmorphism theme and Indonesian locale.
        - Dynamic **PIC Selection** with live filtering of 'anggota' role users.
        - **Thumbnail Upload**: Integrated with Spatie Media Library and live preview.
        - **Project Color**: Custom color picker for dashboard accents.
        - Robust **Bootstrap Grid** integration for 100% mobile responsiveness.
    - [x] **Store Logic**: Implementation of backend storage with AJAX (Axios), validation (StoreProjectRequest), and PIC syncing.
    - [ ] **Update & Delete**: CRUD completion.
    - [ ] **Detailed View**: Individual project dashboard with activity log.
- [x] **Documentation Builder Module**:
    - [x] **Architecture**: Service-Repository pattern with `DokumenServiceInterface`.
    - [x] **Data Table**: Server-side pagination, sorting, and dynamic search (AJAX) with category and project filters.
    - [x] **Media Library**: Integrated with Spatie Media Library for file storage and human-readable metadata.
    - [x] **Document Types**: Support for Single File, Article/Manual Book, and Code Documentation.
    - [x] **Monaco Editor**: Integrated for professional code documentation authoring.
    - [x] **Dynamic Metadata**: Added JSON metadata support to `dokumen_items` for persistent language settings in code blocks.
    - [x] **Sticky Save & Redirect**: Professional floating save button with automated redirection to index.
    - [x] **Premium UI/UX**: Re-styled controls (custom select, trash button) with micro-animations and consistent design.
    - [x] **Cascade Delete**: Implementation of model-level cascading delete for document items and associated media files.
    - [x] **Environment Hardening**: Fixed symlink issues and Octane caching protocols for Docker/FrankenPHP.
    - [x] **CRUD Completion**: Implementation of Create, Read (Detail Modal), Update, and Delete functionalities.
    - [x] **UI Polish**: Clean UI with partials-based modals and premium dashboard-style metrics.
    - [x] **Breadcrumbs**: Restored "Home > Dokumen" navigation for better UX.
- [x] **Project Management (Phase 3: Detail Page)**:
    - [x] **Premium Detail UI**: Implementation of Project Dashboard with stats, activity logs, and team overview.
- [x] **Catatan (Notes) Module (Phase 1: UI/UX)**:
    - [x] **Architecture**: Service-Controller pattern with `CatatanController` and `CatatanMessages` constants.
    - [x] **Premium UI**: Dark glassmorphism dashboard with 4 stat cards and interactive data table.
    - [x] **Modular Assets**: Separated logic into `catatan.css` and `catatan.js` with intersection-observer based count-up animations.
    - [x] **Dynamic Modals**: 
        - Integrated **TinyMCE 6** with dark skin and auto-init logic.
        - Implementation of "Drain-Fill" loading animations for all modals (Add, Edit, View, Delete).
        - Responsive breadcrumbs in header action area (Home > Catatan).
    - [x] **Data Integration**: Prepared Blade structures for dynamic data injection (PICs, Categories, Priorities).
- [ ] **Catatan (Notes) Module (Phase 2: Backend Logic)**:
    - [ ] CRUD implementation (Store, Update, Delete).
    - [ ] Dynamic stats calculation.
    - [ ] Category & Project filters integration.
- [ ] **Kategori Management**: Modul untuk mengelola kategori (seperti kategori proyek/kegiatan).
- [ ] **Unit Kerja Management**: Modul untuk mengelola struktur organisasi/unit kerja.
- [ ] **Activity/Task Module**: Implementasi pembuatan aktivitas (berdasarkan history v1).
- [ ] **Monthly Report**: Optimasi dan implementasi laporan bulanan (berdasarkan history v1).
- [ ] **UI Polish**: Terus memantau konsistensi UI/UX pada modul baru.
- [ ] **Recaptcha Integration**: Penambahan keamanan pada form login/register.

## 📝 Notes & Maintenance
- **Development Rituals (Wajib)**:
  1. Setiap ada perubahan pada **Controller, Service, Model, atau Route**, jalankan:
     `docker exec pm_v2_app php artisan octane:reload`
  2. Setiap ada perubahan pada file **.blade.php** (jika tidak ter-update), jalankan:
     `docker exec pm_v2_app php artisan view:clear`
  3. Setiap ada perubahan pada file **.js** atau **.css**, lakukan **Hard Refresh (Ctrl + F5)** pada browser.
- **Project Aesthetics**: Project ini menggunakan pendekatan **Premium Design**, jadi setiap fitur baru harus dipastikan memiliki UI/UX yang modern.
- **Menu System**: Cache menu harus di-clear jika ada perubahan data pada tabel `menus`.
- **Laravel Debugbar**: Telah diinstal untuk mempermudah debugging query dan performa (dev only).

