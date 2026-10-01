<?php

use App\Models\Konfigurasi\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('tgl_indo')) {
    function tgl_indo($tgl, $tampil_hari = false, $tampil_jam = false)
    {
        if (!$tgl) return null;

        // 🔹 Kalau input adalah Carbon instance, ubah ke string Y-m-d H:i:s
        if ($tgl instanceof \Carbon\Carbon) {
            $tgl = $tgl->format('Y-m-d H:i:s');
        }

        $nama_hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
        $nama_bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tahun   = substr($tgl, 0, 4);
        $bulan   = $nama_bulan[(int) substr($tgl, 5, 2)];
        $tanggal = substr($tgl, 8, 2);
        $text    = '';

        if ($tampil_hari) {
            $urutan_hari = date('w', strtotime($tgl));
            $hari        = $nama_hari[$urutan_hari];
            $text        = "$hari, $tanggal $bulan $tahun";
        } else {
            $text = "$tanggal $bulan $tahun";
        }

        if ($tampil_jam) {
            $jam = substr($tgl, 11, 5);
            $text .= " - $jam";
        }

        return $text;
    }
}

if (!function_exists('user')) {
    /**
     * Helper sakti untuk data user login, nama, inisial, dan Spatie Role/Permission.
     */
    function user($field = null, $limit = null)
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        // 1. Jika panggil user() tanpa parameter, return object user utuh
        if (is_null($field)) {
            return $user;
        }

        // 2. Gunakan Accessor dari Model User (Lebih konsisten & hemat memori)
        if ($field === 'initial') {
            return $user->initials;
        }

        if ($field === 'avatar') {
            return $user->display_avatar;
        }

        // 3. Logika khusus untuk 'name' dengan limit kata
        if ($field === 'name' && is_numeric($limit)) {
            $words = explode(' ', $user->name);
            return implode(' ', array_slice($words, 0, $limit));
        }

        // 4. Integrasi Spatie: Cek Role (user('is', 'admin'))
        if ($field === 'is') {
            return $user->hasRole($limit); // $limit di sini berfungsi sebagai nama role
        }

        // 5. Integrasi Spatie: Cek Permission (user('can', 'edit-post'))
        if ($field === 'can') {
            return $user->can($limit); // $limit di sini berfungsi sebagai nama permission
        }

        // 6. Integrasi Spatie: Ambil Nama Role Pertama (user('role'))
        if ($field === 'role') {
            return $user->getRoleNames()->first();
        }

        // 7. Default: ambil field dari database (id, email, username, dll)
        return $user->$field ?? null;
    }
}


if (!function_exists('menus')) {
    function menus($grouped = true)
    {
        // 1. Ambil data asli (flat) dari cache agar query hanya 1x
        $allMenus = Cache::rememberForever('menus_data', function () {
            try {
                return Menu::active()
                    ->orderBy('orders')
                    ->get();
            } catch (\Throwable $e) {
                return collect();
            }
        });

        if (!($allMenus instanceof \Illuminate\Support\Collection)) {
            $allMenus = collect($allMenus);
        }

        // 2. Jika minta grouped (untuk Sidebar), lakukan grouping di memori PHP
        if ($grouped) {
            return $allMenus->groupBy('category');
        }

        // 3. Jika tidak, kembalikan data flat (untuk urlMenu)
        return $allMenus;
    }
}

if (!function_exists('urlMenu')) {
    function urlMenu()
    {
        // Cache hasil akhir array URL agar tidak perlu pluck() di setiap request
        return Cache::rememberForever('menus_url_list', function () {
            $m = menus(false);
            return ($m instanceof \Illuminate\Support\Collection) ? $m->whereNotNull('url')->pluck('url')->toArray() : [];
        });
    }
}

if (!function_exists('convertToWebp')) {
    /**
     * Mengubah file gambar yang diunggah menjadi format WebP tanpa mengurangi kualitas secara kasar.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $quality Quality 0-100 (Default 90 untuk visual jernih/tidak pecah)
     * @return \Illuminate\Http\UploadedFile
     */
    function convertToWebp(\Illuminate\Http\UploadedFile $file, int $quality = 90): \Illuminate\Http\UploadedFile
    {
        $mime = $file->getMimeType();
        if (!str_starts_with($mime, 'image/')) {
            return $file;
        }

        // Jika sudah webp, tidak perlu konversi ulang
        if ($mime === 'image/webp') {
            return $file;
        }

        $filePath = $file->getRealPath();
        $image = false;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = @imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($filePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($filePath);
                if ($image) {
                    imagepalettetotruecolor($image);
                }
                break;
            case 'image/bmp':
            case 'image/x-ms-bmp':
                $image = @imagecreatefrombmp($filePath);
                break;
        }

        if (!$image) {
            return $file;
        }

        $tempPath = sys_get_temp_dir() . '/' . Str::random(40) . '.webp';
        
        // Simpan sebagai WebP dengan kualitas tinggi
        if (imagewebp($image, $tempPath, $quality)) {
            imagedestroy($image);
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            
            return new \Illuminate\Http\UploadedFile(
                $tempPath,
                $originalName,
                'image/webp',
                null,
                true // test mode / local temporary file
            );
        }

        imagedestroy($image);
        return $file;
    }
}

