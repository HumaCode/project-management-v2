<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        $projects = [
            [
                'name' => 'Sistem Informasi PPID Kota Pekalongan',
                'description' => 'Sistem manajemen dokumen publik',
                'status' => 'in_progress',
                'start_date' => '2024-10-01',
                'deadline' => '2025-01-07',
                'progress' => 75,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Aplikasi E-Commerce Mobile',
                'description' => 'Platform belanja online Flutter',
                'status' => 'in_progress',
                'start_date' => '2024-11-15',
                'deadline' => '2025-01-15',
                'progress' => 42,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Sistem Absensi Karyawan',
                'description' => 'Absensi QR Code & GPS',
                'status' => 'done',
                'start_date' => '2024-08-01',
                'deadline' => '2024-12-01',
                'progress' => 100,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Dashboard Monitoring IoT',
                'description' => 'Visualisasi data sensor real-time',
                'status' => 'to_do',
                'start_date' => '2025-01-10',
                'deadline' => '2025-02-28',
                'progress' => 10,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Portal Layanan Publik Kota',
                'description' => 'Portal terintegrasi layanan masyarakat',
                'status' => 'in_progress',
                'start_date' => '2024-09-01',
                'deadline' => '2024-12-31',
                'progress' => 60,
                'created_by' => $user->id,
            ],
        ];

        foreach ($projects as $project) {
            $p = Project::create($project);
            // Assign some random pics if needed, for now just the creator
            $p->pics()->attach($user->id);
        }
    }
}
