<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users      = ['dev', 'admin', 'anggota']; // Saya hapus satu 'administrator' duplikat
        $default    = [
            'email_verified_at' => now(),
            'password'          => Hash::make('123'),
            'remember_token'    => Str::random(10)
        ];

        foreach ($users as $value) {
            $user = User::firstOrCreate(
                ['username' => $value],
                [...$default, ...[
                    'name'              => ucwords($value),
                    'email'             => $value . '@gmail.com',
                    'is_active'         => '1',
                ]]
            );
            
            // Assign role only if it's created or doesn't have it
            $user->assignRole($value);
        }

        $faker = \Faker\Factory::create('id_ID');

        for ($i = 0; $i < 100; $i++) {
            $username = $faker->unique()->userName();
            $user = User::firstOrCreate(
                ['username' => $username],
                [...$default, ...[
                    'name'              => $faker->name(),
                    'email'             => $faker->unique()->safeEmail(),
                    'is_active'         => '1',
                ]]
            );
            
            if (!$user->hasRole('anggota')) {
                $user->assignRole('anggota');
            }
        }

        //--------------- 100 user berhasil di buat
    }
}
