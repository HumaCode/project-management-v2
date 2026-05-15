# Dynamic PDF Reporting & Cover Builder System

## Overview
Sistem ini memungkinkan pengguna untuk membangun cover laporan secara dinamis menggunakan editor berbasis Fabric.js (Canva-like) dan menghasilkan laporan PDF profesional yang menggabungkan cover tersebut dengan konten dokumen proyek.

## Technical Components

### 1. Cover Builder (Frontend)
- **Library**: [Fabric.js](http://fabricjs.com/)
- **File**: `public/assets/auth/backend/js/cover-builder-v2.js`
- **Features**:
    - 16 Design Templates (Modern, Corporate, Blueprint, Futuristic, etc.)
    - Drag-and-drop elements (Text, Shapes, Project Info)
    - Scrollable template sidebar.
    - Integration with `SCA.dialog` for consistent UX.
- **Data Flow**: Desain diekspor sebagai Base64 Image dan dikirim ke backend melalui form input tersembunyi.

### 2. PDF Engine (Backend)
- **Library**: `Barryvdh\DomPDF`
- **Template**: `resources/views/pages/report/pdf.blade.php`
- **Layout Logic**:
    - **Native Page Margins**: Menggunakan `@page { margin: 2.5cm 2cm 2.5cm 2cm; }` untuk konsistensi halaman konten.
    - **Full-Bleed Cover**: Menggunakan `@page:first { margin: 0; }` dan `z-index: 9999` untuk memastikan cover menempati seluruh halaman pertama tanpa margin dan tanpa footer.
    - **Conditional Header**: Header utama hanya muncul di halaman pertama konten jika cover tidak digunakan. Jika cover ada, header dihilangkan untuk menghindari redundansi.
    - **Fixed Footer**: Menampilkan identitas laporan dan penomoran halaman otomatis di setiap lembar (kecuali tertutup cover).

### 3. UI Components
- **Code Window**: Blok kode ditampilkan dalam container ala MacOS dengan tombol kontrol (Red/Yellow/Green dots), latar belakang gelap, dan font monospace.
- **Standard Alignment**: Seluruh teks laporan dipaksa rata kiri (Left Aligned) sejajar dengan garis pembatas biru (Blue Bar) untuk estetika profesional.
- **Centered Images**: Elemen visual (gambar) otomatis diletakkan di tengah (Center) untuk keseimbangan tata letak.

## Maintenance Notes
- Jika ingin menambah template baru, edit fungsi `applyTemplate(templateName)` di `cover-builder-v2.js`.
- Pastikan folder `storage/fonts` memiliki izin tulis untuk DomPDF jika menggunakan font kustom di masa depan.
- Setiap perubahan pada file JS/Blade PDF memerlukan `php artisan octane:reload` jika dijalankan di environment Octane.
