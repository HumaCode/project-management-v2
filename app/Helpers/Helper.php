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

        // 2. Logika khusus untuk 'initial' (Inisial Nama)
        if ($field === 'initial') {
            $words = explode(' ', $user->name);
            return collect($words)
                ->map(fn($name) => Str::upper(Str::substr($name, 0, 1)))
                ->take(2) // Biasanya inisial cukup 2 huruf (misal: AS)
                ->implode('');
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
    function menus()
    {
        return Cache::rememberForever('menus', function () {
            return Menu::active()
                ->orderBy('orders')
                ->get()
                ->groupBy('category');
        });
    }
}

if (!function_exists('urlMenu')) {
    function urlMenu()
    {
        return Cache::rememberForever('urlMenu', function () {
            return Menu::active()
                ->whereNotNull('url')
                ->pluck('url')
                ->toArray();
        });
    }
}
