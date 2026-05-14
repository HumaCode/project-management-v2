<?php

namespace Database\Seeders;

use App\Models\KategoriDokumen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KategoriDokumenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@pm-v2.test')->first() ?? User::first();
        
        if (!$admin) return;

        $categories = [
            [
                'name' => 'Spesifikasi',
                'slug' => 's',
                'description' => 'Dokumen spesifikasi teknis dan kebutuhan proyek.',
                'icon' => 'bi-file-earmark-text',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'RAB / Anggaran',
                'slug' => 'r',
                'description' => 'Rencana Anggaran Biaya dan dokumen keuangan proyek.',
                'icon' => 'bi-cash-stack',
                'color' => '#10b981',
            ],
            [
                'name' => 'Laporan',
                'slug' => 'l',
                'description' => 'Laporan harian, mingguan, atau bulanan.',
                'icon' => 'bi-journal-check',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Source Code',
                'slug' => 'c',
                'description' => 'Dokumentasi terkait kode sumber dan teknis pemrograman.',
                'icon' => 'bi-code-square',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Berita Acara',
                'slug' => 'b',
                'description' => 'Dokumen berita acara serah terima atau rapat.',
                'icon' => 'bi-clipboard-check',
                'color' => '#6366f1',
            ],
            [
                'name' => 'Desain',
                'slug' => 'd',
                'description' => 'Dokumen desain UI/UX, arsitektur, atau sistem.',
                'icon' => 'bi-palette',
                'color' => '#ec4899',
            ],
            [
                'name' => 'Dokumentasi',
                'slug' => 'doc',
                'description' => 'Dokumentasi teknis atau operasional.',
                'icon' => 'bi-book',
                'color' => '#64748b',
            ],
            [
                'name' => 'Manual Book',
                'slug' => 'mb',
                'description' => 'Panduan penggunaan sistem bagi user.',
                'icon' => 'bi-info-circle',
                'color' => '#0ea5e9',
            ],
        ];

        foreach ($categories as $cat) {
            KategoriDokumen::updateOrCreate(
                ['slug' => $cat['slug']],
                array_merge($cat, ['created_by' => $admin->id])
            );
        }
    }
}
