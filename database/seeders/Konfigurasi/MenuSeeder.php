<?php

namespace Database\Seeders\Konfigurasi;

use App\Models\Konfigurasi\Menu;
use App\Traits\HasMenuPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('menus_data');
        Cache::forget('menus_url_list');

        // =============================
        // MASTER
        // =============================

        $menus = [

            // MASTER
            [
                'name' => 'Permohonan Aplikasi',
                'url' => 'permohonan-aplikasi',
                'category' => 'MASTER',
                'icon' => 'bi bi-file-earmark-plus-fill',
                'orders' => 0,
            ],
            [
                'name' => 'Project',
                'url' => 'projects',
                'category' => 'MASTER',
                'icon' => 'bi bi-kanban-fill',
                'orders' => 1,
            ],
            [
                'name' => 'Dokumen',
                'url' => 'dokumen',
                'category' => 'MASTER',
                'icon' => 'bi bi-folder2-open',
                'orders' => 2,
            ],
            [
                'name' => 'Catatan',
                'url' => 'catatan',
                'category' => 'MASTER',
                'icon' => 'bi bi-journal-text',
                'orders' => 3,
            ],
            [
                'name' => 'Laporan PDF',
                'url' => 'laporan',
                'category' => 'MASTER',
                'icon' => 'bi bi-file-earmark-pdf-fill',
                'orders' => 4,
            ],
            [
                'name' => 'Diagram Sistem',
                'url' => 'diagrams',
                'category' => 'MASTER',
                'icon' => 'bi bi-diagram-2-fill',
                'orders' => 5,
            ],

            // =============================
            // MANAGEMENT
            // =============================

            [
                'name' => 'Manajemen Tim',
                'url' => 'tim',
                'category' => 'MANAGEMENT',
                'icon' => 'bi bi-people-fill',
                'orders' => 5,
            ],
            [
                'name' => 'Kategori Dokumen',
                'url' => 'kategori-dokumen',
                'category' => 'MANAGEMENT',
                'icon' => 'bi bi-tags-fill',
                'orders' => 6,
            ],

            // =============================
            // ROLE MANAGEMENT
            // =============================

            [
                'name' => 'Role',
                'url' => 'roles',
                'category' => 'ROLE MANAGEMENT',
                'icon' => 'bi bi-diagram-3-fill',
                'orders' => 7,
                'permissions' => ['menu', 'create', 'read', 'show', 'update', 'delete', 'akses'],
            ],
            [
                'name' => 'Permission',
                'url' => 'permissions',
                'category' => 'ROLE MANAGEMENT',
                'icon' => 'bi bi-shield-lock',
                'orders' => 8,
            ],
            [
                'name' => 'Users',
                'url' => 'users',
                'category' => 'ROLE MANAGEMENT',
                'icon' => 'bi bi-people-fill',
                'orders' => 9,
                'permissions' => ['menu', 'create', 'read', 'show', 'update', 'delete', 'activate'],
            ],

            // =============================
            // SETTING
            // =============================

            [
                'name' => 'Profil',
                'url' => 'profil',
                'category' => 'SETTING',
                'icon' => 'bi bi-person-badge-fill',
                'orders' => 10,
            ],
            [
                'name' => 'Pengaturan Sistem',
                'url' => 'settings',
                'category' => 'SETTING',
                'icon' => 'bi bi-gear-fill',
                'orders' => 11,
            ],

        ];

        foreach ($menus as $data) {
            // Ambil dan pisahkan 'permissions' dari array utama agar tidak ikut ter-insert
            // ke table 'menus' jika kolom permissions tidak ada di database.
            $customPermissions = $data['permissions'] ?? null;

            // Hapus 'permissions' dari array agar firstOrCreate tidak error (opsional jika kolom tidak ada)
            unset($data['permissions']);

            $menu = Menu::updateOrCreate(
                ['url' => $data['url']],
                $data
            );

            if ($menu->url === 'projects') {
                // dev, admin, anggota mendapatkan semua permission project
                $this->attachMenupermission($menu, $customPermissions, ['dev', 'admin', 'anggota']);
                // role user HANYA mendapatkan permission menu, read, dan show untuk project
                $this->attachMenupermission($menu, ['menu', 'read', 'show'], ['user']);
            } elseif ($menu->url === 'profil') {
                $this->attachMenupermission($menu, $customPermissions, ['dev', 'admin', 'anggota', 'user']);
            } elseif ($menu->url === 'permohonan-aplikasi') {
                $this->attachMenupermission($menu, $customPermissions, ['dev', 'admin', 'user']);
            } elseif (in_array($menu->url, ['dokumen', 'catatan'])) {
                $this->attachMenupermission($menu, $customPermissions, ['dev', 'admin', 'anggota']);
            } else {
                $roles = ['dev', 'admin'];
                // Jika $customPermissions null, fungsi Anda akan otomatis membuat defaultnya
                // Jika ada nilainya, ia akan menggunakan array khusus tersebut.
                $this->attachMenupermission($menu, $customPermissions, $roles);
            }
        }
    }
}
