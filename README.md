# Project Management System (PMS) V2

![Laravel 12](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-^8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TinyMCE 7](https://img.shields.io/badge/TinyMCE-7.x-348BFB?style=for-the-badge&logo=tinymce&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.1-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Mermaid.js](https://img.shields.io/badge/Mermaid.js-10.6-FF3670?style=for-the-badge&logo=mermaid&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)

Platform manajemen proyek terpadu berbasis **Laravel 12**, dilengkapi dengan **Modul Catatan & Berkas Privat Terenkripsi**, **TinyMCE 7 Full Suite Document Builder**, **Visual Interactive Diagram Builder**, **Penjana Laporan PDF (DomPDF + Spatie Media Library)**, serta sistem manajemen tim & hak akses (Spatie) berestetika **Glassmorphism Modern**.

---

## 🚀 Fitur Unggulan

### 📌 1. Modul Catatan & Lampiran Privat Terenkripsi
* **Private Storage Security:** Berkas lampiran catatan disimpan secara privat di `storage/app/private/catatan/...` via Spatie Media Library `local` disk untuk menjamin keamanan dokumen sensitif.
* **Encrypted Media URLs:** Menggunakan enkripsi token `Crypt::encryptString()` pada parameter URL route `/catatan/media/{encrypted_id}` untuk mencegah serangan *ID Enumeration* / peretasan ID berkas mentah.
* **Interactive Media Preview Modal:** Modal pratinjau media popup (*Nested Modal*) yang mendukung tampilan **Gambar** (fit container) dan **Dokumen PDF** (viewer `<iframe>` interaktif) secara langsung tanpa berpindah halaman.
* **Dynamic Custom Flatpickr Datepicker:**
  * Kompatibilitas penuh **Mode Terang (Light Theme)** dan **Mode Gelap (Dark Theme)**.
  * Bidang tanggal `date` dengan tata letak ikon kalender di sebelah kiri input box dan penyesuaian lebar presisi 100% sejajar dengan dropdown proyek terkait.
  * Selector bulan & tahun berbentuk *Pill Dropdown Badge* dengan rentang tahun dinamis berbasis perulangan `for` (`startYear = currentYear - 2` hingga `endYear = currentYear + 3`).

### 📝 2. Rich Text Document Builder (TinyMCE 7)
* **Word & Notion-Style Experience:** Pengalaman mengetik satu kanvas terpadu yang praktis, fleksibel, dan modern.
* **Full Suite Toolbar & Menubar:** Mendukung format *Headings (H1-H6)*, *Bold/Italic/Underline/Strikethrough*, *Text/Background Color Picker*, *Lists*, *Table Builder*, *Code Sample Snippets*, *Accordion Widget*, dan *Emoticons*.
* **Spatie Media Library Integration:** Upload gambar via *drag & drop* atau dialog file yang terintegrasi langsung dengan koleksi Spatie Media Library (`builder_images`), aman dari pembersihan otomatis.
* **Responsive Height Calculation:** Kanvas editor menyesuaikan tinggi resolusi layar (*dynamic viewport height calc*) untuk kenyamanan mengetik maksimal.

### 📄 3. Penjana & Pratinjau Laporan PDF (DomPDF + Spatie)
* **Live PDF Preview Stream:** Pratinjau dokumen laporan proyek secara *real-time* via `/laporan/preview`.
* **Automatic Base64 HTML Image Converter:** Konversi otomatis tag `<img>` dan path gambar dari Rich Text HTML ke Base64 Data URI untuk rendering gambar PDF yang presisi tanpa ketergantungan jaringan.
* **Drag & Drop Reorderable Canvas:** Penyusunan aset dokumen dengan fitur *drag & drop sortable*, margin yang lapang, dan tampilan kartu responsif mode terang & gelap.

### 📊 4. Visual Interactive Diagram Builder
* **2-Way Realtime Sync:** Sinkronisasi dua arah otomatis antara **Form Visual** dan **Editor Kode Mermaid.js**.
* **Interactive Drag & Drop Nodes:** Node langkah diagram dapat digeser secara kustom dengan mouse. Ujung garis panah & kepala panah terkunci secara geometris (*Geometric Anchor Proximity*) serta bergerak meluncur secara real-time.
* **Direct Smooth & Elastic Curves:** Lintasan garis panah melengkung/lurus secara elastis (*Rubber-Band Scaling*) dan bebas dari gelombang S-curve yang mengganggu.
* **Cursor Specificity & Pintasan Modal:** Indikator kursor khusus untuk pergeseran node (*Move / Panah 4 Arah*) dan *pan canvas* (*Grab / Tangan*).
* **Target Focus & Zoom Controls:** Tombol fokus diagram bergaya Google Maps (`crosshair`), kontrol pan & zoom responsif.
* **High-Res PNG Export:** Fitur ekspor gambar diagram berkualitas tinggi.

### 👥 5. Manajemen Pengguna & Tim (RBAC)
* Hak akses berbasis peran (*Role-Based Access Control*) menggunakan `spatie/laravel-permission`.
* Integrasi **Google OAuth Single Sign-On (SSO)** via `laravel/socialite`.
* Sidebar bernavigasi halus dengan fitur **Hover Auto-Expand** pada posisi *collapsed*.

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
* **Editor Engine:** TinyMCE 7 Full Suite
* **Styling:** Vanilla CSS (Glassmorphism), Tailwind CSS ^3.1, Bootstrap Icons ^1.11
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
