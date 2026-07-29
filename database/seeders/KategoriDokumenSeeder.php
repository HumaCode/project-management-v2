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
        $admin = User::where('email', 'dev@gmail.com')->first() ?? User::first();
        
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
            [
                'name' => 'Diagram & Arsitektur',
                'slug' => 'diagram',
                'description' => 'Diagram sistem, Flowchart, ERD, DFD, dan arsitektur aplikasi.',
                'icon' => 'bi-diagram-3',
                'color' => '#00c8ff',
            ],
            [
                'name' => 'Kontrak & SPK',
                'slug' => 'kontrak',
                'description' => 'Surat Perintah Kerja (SPK), Kontrak, dan perjanjian kerjasama.',
                'icon' => 'bi-file-earmark-pdf',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Testing & UAT',
                'slug' => 'testing',
                'description' => 'Dokumen pengujian sistem, Test Cases, dan User Acceptance Test.',
                'icon' => 'bi-check2-square',
                'color' => '#14b8a6',
            ],
            [
                'name' => 'Notulen Rapat',
                'slug' => 'notulen',
                'description' => 'Catatan dan kesepakatan hasil diskusi rapat proyek (MoM).',
                'icon' => 'bi-card-heading',
                'color' => '#a855f7',
            ],
            [
                'name' => 'SOP & Panduan',
                'slug' => 'sop',
                'description' => 'Standar Operasional Prosedur dan kebijakan tata kelola proyek.',
                'icon' => 'bi-file-earmark-ruled',
                'color' => '#64748b',
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
