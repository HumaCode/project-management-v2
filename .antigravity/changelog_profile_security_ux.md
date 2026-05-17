# Log Perubahan Profile, Keamanan, & UI/UX Polish — 17 Mei 2026

## 1. Dashboard Profil Dinamis (Dynamic Stats)
- **Status**: Migrasi dari placeholder statis ke Query Database real-time.
- **Fitur Baru**:
    - **Total Proyek**: Menghitung kontribusi user (pembuat proyek, PIC utama, atau anggota tim yang mengelola proyek).
    - **Task Selesai**: Menghitung proyek terafiliasi dengan status `'done'`.
    - **Dokumen**: Menghitung seluruh item dokumen di dalam proyek di mana user berpartisipasi.
    - **Total Tim**: Menghitung tim yang aktif diikuti oleh user (`team_user`).
- **File Terkait**:
    - [ProfileController.php](file:///home/humacode/Development/Projects/project-management-v2/app/Http/Controllers/Setting/ProfileController.php)

## 2. Pembaruan Zona Bahaya & Sesi Aktif (Danger Zone & Session Management)
- **Status**: Refactoring alur aksi sensitif.
- **Inovasi**:
    - **Single-Step Dialog**: Menghilangkan modal konfirmasi password yang membebani alur user.
    - **SCA Promise Confirmation**: Menggunakan `SCA.dialog` premium untuk konfirmasi deaktifasi & hapus akun dengan UI cyberpunk yang menyatu.
    - **Deaktifasi Aman**: Menyetel `is_active = 0` dan `email_verified_at = null` di database demi integritas keamanan.
    - **Active Session Revocation**: Menampilkan seluruh sesi aktif dari browser user secara dinamis dari tabel `sessions` dengan tombol pencabutan instan (single session / mass log-out).
- **File Terkait**:
    - [ProfileController.php](file:///home/humacode/Development/Projects/project-management-v2/app/Http/Controllers/Setting/ProfileController.php)
    - [form-bahaya.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/profile/partials/form-bahaya.blade.php)
    - [form-sesi.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/profile/partials/form-sesi.blade.php)

## 3. Pencarian Anggota Modal Tim (Team Member Real-Time Search)
- **Status**: Implementasi fitur pencarian pada modal Pilih Anggota.
- **Inovasi**:
    - **Sleek Search Box**: Menempatkan input pencarian modern di samping kanan header modal Pilih Anggota.
    - **Client-Side DOM Filtering**: Memfilter nama (`user.name`) dan role (`user.role_name`) secara instan (<1ms) saat mengetik.
    - **Selection Preservation**: Menggunakan filter `.show()` dan `.hide()` yang cerdas sehingga status checkbox dan input role anggota yang sudah dicentang tetap dipertahankan meskipun elemen tersebut tersembunyi selama pencarian.
    - **Reset Otomatis**: Input pencarian dibersihkan secara otomatis setiap kali modal dibuka.
- **File Terkait**:
    - [modal-form.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/team/partials/modal-form.blade.php)
    - [team.js](file:///home/humacode/Development/Projects/project-management-v2/public/assets/auth/backend/js/team.js)

## 4. Toggle Tampilan Live Threat Monitor (IDS)
- **Status**: Perbaikan visual layout halaman Pengaturan Sistem.
- **Penerapan**:
    - Menambahkan `id="liveThreatMonitor"` ke container monitoring ancaman.
    - Mengintegrasikan logika transisi di file `setting.js` sehingga Live Threat Monitor secara otomatis disembunyikan (`fadeOut`) saat masuk ke detail pengaturan (seperti Profil Sistem) dan ditampilkan kembali (`fadeIn`) saat user mengklik "Kembali ke Pengaturan", menjaga layout tetap teratur dan fokus pada konten yang dibuka.
- **File Terkait**:
    - [index.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/index.blade.php)
    - [setting.js](file:///home/humacode/Development/Projects/project-management-v2/public/assets/auth/backend/js/setting.js)

## 5. Safeguard jQuery Undefined Race Condition
- **Status**: Pengamanan inisialisasi script.
- **Perbaikan**:
    - Membungkus seluruh kode script inline pada komponen profil (`form-sesi`, `form-bahaya`, `form-aktivitas`) ke dalam event listener `DOMContentLoaded` untuk mencegah error `Uncaught ReferenceError: $ is not defined` yang terjadi akibat jQuery dimuat di bagian footer layout master.
- **File Terkait**:
    - [form-sesi.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/profile/partials/form-sesi.blade.php)
    - [form-bahaya.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/profile/partials/form-bahaya.blade.php)
    - [form-aktivitas.blade.php](file:///home/humacode/Development/Projects/project-management-v2/resources/views/pages/setting/profile/partials/form-aktivitas.blade.php)

---
*Catatan ini dibuat otomatis oleh Antigravity Assistant.*
