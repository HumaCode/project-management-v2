<?php

namespace Database\Seeders\Shield;

use App\Models\Shield\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'dev',
                'slug'        => 'developer',
                'type_role'   => 'system',
                'description' => 'Memiliki hak akses tertinggi (Super Admin) dengan kontrol penuh atas seluruh sistem, konfigurasi inti, dan pemeliharaan teknis website.',
            ],
            [
                'name'        => 'admin',
                'slug'        => 'administrator',
                'type_role'   => 'system',
                'description' => 'Bertugas mengelola operasional harian website, seperti manajemen pengguna dan master data, dengan batasan akses pada konfigurasi sistem tingkat lanjut.',
            ],
            [
                'name'        => 'anggota',
                'slug'        => 'anggota',
                'type_role'   => 'system',
                'description' => 'Anggota tim',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
