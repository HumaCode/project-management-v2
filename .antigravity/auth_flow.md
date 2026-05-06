# Authentication & Admin Activation Flow

Dokumentasi alur autentikasi lanjutan yang diimplementasikan pada Proyek V2.

## 1. Google Authentication (Socialite)
- **Library**: `laravel/socialite`
- **Driver**: `google`
- **Workflow**:
    1. User mengklik "Login with Google".
    2. Jika user baru, sistem membuat record `User` dengan `is_socialite = true` dan `google_id`.
    3. Jika data profil (seperti `username` atau `phone`) masih kosong, user diarahkan ke middleware `inactive`.

## 2. Inactive User Handling
- **Middleware**: `EnsureProfileIsComplete` (custom logic di `InactiveUserController`)
- **UI**: `auth/inactive.blade.php`
- **Fitur**:
    - User diminta melengkapi `username`, `phone`, dan `gender`.
    - Menggunakan **AJAX submission** dengan dialog konfirmasi `SCA.confirm`.
    - Setelah profil lengkap, user berstatus `is_active = 0` dan sistem mengirim notifikasi email ke Admin.

## 3. Secure Admin Notification
- **Notification**: `App\Notifications\AccountCompletionNotification`
- **Channel**: `mail` (Mailtrap/SMTP)
- **Secure Mechanism**:
    - Menggunakan **Laravel Signed URL** (`temporarySignedRoute`) dengan expiry 7 hari.
    - URL mengarah ke `/auto-login/{id}` dengan signature validasi.

## 4. Admin Auto-Login & Activation
- **Controller**: `App\Http\Controllers\Auth\AutoLoginController`
- **Workflow**:
    1. Admin mengklik "Lihat Detail & Aktifkan" di email.
    2. Sistem memvalidasi signature URL.
    3. Jika valid, Admin otomatis di-login-kan (`Auth::login($admin)`).
    4. Admin diarahkan ke halaman `/users?search={USER_ID}`.
- **Frontend Filter**:
    - File `resources/views/pages/role-management/users/index.blade.php` mendeteksi parameter `search` dari URL.
    - Jika ada, tabel akan langsung memfilter data berdasarkan ID user tersebut secara instan.

## 5. Granular Permissions
- Ditambahkan permission `activate users` untuk mengontrol siapa yang bisa mengaktifkan akun.
- UI konfigurasi hak akses di Role Management telah diperbarui untuk mendukung permission ini.
