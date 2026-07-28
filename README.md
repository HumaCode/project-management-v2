# Project Management System (PMS) V2

![Laravel 12](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-^8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Mermaid.js](https://img.shields.io/badge/Mermaid.js-10.6-FF3670?style=for-the-badge&logo=mermaid&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

Platform manajemen proyek terpadu berbasis **Laravel 12**, dilengkapi dengan fitur **Visual Interactive Diagram Builder**, manajemen tim & hak akses (Spatie), pengelolaan dokumen, serta sistem autentikasi modern.

---

## 🚀 Fitur Unggulan

### 📊 1. Visual Interactive Diagram Builder
* **2-Way Realtime Sync:** Sinkronisasi dua arah otomatis antara **Form Visual** dan **Editor Kode Mermaid.js**.
* **Interactive Drag & Drop Nodes:** Node langkah diagram dapat digeser secara kustom dengan mouse. Ujung garis panah & kepala panah terkunci secara geometris (*Geometric Anchor Proximity*) serta bergerak meluncur secara real-time.
* **Direct Smooth & Elastic Curves:** Lintasan garis panah melengkung/lurus secara elastis (*Rubber-Band Scaling*) dan bebas dari gelombang S-curve yang mengganggu.
* **Target Focus & Zoom Controls:** Tombol fokus diagram bergaya Google Maps (`crosshair`), kontrol pan & zoom responsif.
* **Light & Dark Theme Support:** Tampilan UI beradaptasi penuh terhadap mode terang dan gelap dengan kontras warna yang nyaman di mata.
* **High-Res PNG Export:** Fitur ekspor gambar diagram berkualitas tinggi.

### 👥 2. Manajemen Pengguna & Tim (RBAC)
* Hak akses berbasis peran (*Role-Based Access Control*) menggunakan `spatie/laravel-permission`.
* Integrasi **Google OAuth Single Sign-On (SSO)** via `laravel/socialite`.
* Fitur manajemen status pengguna (aktif / non-aktif).

### 📄 3. Manajemen Dokumen & Catatan
* Manajemen dokumen berkategori dan lampiran file menggunakan `spatie/laravel-medialibrary`.
* Fitur ekspor dokumen ke format PDF via `barryvdh/laravel-dompdf`.
* Pencatatan log aktivitas sistem dengan `spatie/laravel-activitylog`.

---

## 🛠️ Teknologi & Dependensi

### Backend
* **Framework:** Laravel ^12.0 (PHP ^8.2)
* **Authentication:** Laravel Breeze, Laravel Socialite (Google OAuth)
* **Permissions:** `spatie/laravel-permission` ^7.2
* **Media & Files:** `spatie/laravel-medialibrary` ^11.21
* **PDF Generator:** `barryvdh/laravel-dompdf` ^3.1
* **Activity Logging:** `spatie/laravel-activitylog` ^5.0
* **Websockets:** `laravel/reverb` ^1.0

### Frontend
* **Build Tool:** Vite ^7.0
* **Styling:** Vanilla CSS, Tailwind CSS ^3.1, Bootstrap Icons ^1.11
* **Reactivity:** Alpine.js ^3.4
* **Diagram Engine:** Mermaid.js ^10.6

---

## ⚙️ Panduan Instalasi & Pengoperasian

### 1. Prasyarat Sistem
* PHP >= 8.2 dengan ekstensi (PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, GD).
* Composer >= 2.x
* Node.js >= 18.x & NPM
* Database MySQL / MariaDB

### 2. Langkah Instalasi

```bash
# 1. Clone Repositori
git clone https://github.com/username/project-management-v2.git
cd project-management-v2

# 2. Install Dependensi Backend (Composer)
composer install

# 3. Install Dependensi Frontend (NPM)
npm install

# 4. Salin File Konfigurasi Environment
cp .env.example .env

# 5. Generate Application Key
php artisan key:generate

# 6. Konfigurasi Database pada file .env
# DB_DATABASE=project_management_v2
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Jalankan Migrasi & Database Seeder
php artisan migrate --seed

# 8. Buat Storage Symlink
php artisan storage:link

# 9. Jalankan Server Lokal & Vite
npm run dev
php artisan serve
```

Aplikasi dapat diakses melalui peramban di `http://localhost:8000`.

---

## 📄 Lisensi

Proyek ini dilindungi di bawah lisensi [MIT License](LICENSE).
