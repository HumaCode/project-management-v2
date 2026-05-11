<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $project = Project::first();
        $user = User::first();

        if (!$project || !$user) return;

        Dokumen::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'nama' => 'Dokumen Spesifikasi Teknis PPID',
            'versi' => 'v3.0',
            'kategori' => 's',
            'tanggal_upload' => now(),
            'keterangan' => 'Spesifikasi teknis awal.',
            'type' => 'file',
            'status' => 'published',
        ]);

        Dokumen::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'nama' => 'RAB Proyek PPID 2025',
            'versi' => 'v2.1',
            'kategori' => 'r',
            'tanggal_upload' => now(),
            'keterangan' => 'Rencana anggaran biaya.',
            'type' => 'file',
            'status' => 'published',
        ]);
        Dokumen::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'nama' => 'Panduan API Gateway v2',
            'versi' => 'v1.0',
            'kategori' => 'c',
            'tanggal_upload' => now(),
            'keterangan' => 'Dokumentasi teknis API.',
            'type' => 'article',
            'status' => 'published',
        ]);
    }
}
