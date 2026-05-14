<?php

namespace App\Repositories\KategoriDokumen;

use App\Interface\KategoriDokumen\KategoriDokumenRepositoryInterface;
use App\Models\KategoriDokumen;
use App\Models\Dokumen;
use Illuminate\Support\Str;

class KategoriDokumenRepository implements KategoriDokumenRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage)
    {
        $query = KategoriDokumen::with('creator');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($rowPerPage);
    }

    public function findById(string $id)
    {
        return KategoriDokumen::with('creator')->findOrFail($id);
    }

    public function create(array $data)
    {
        return KategoriDokumen::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? 'bi bi-folder',
            'color' => $data['color'] ?? '#00c8ff',
            'created_by' => auth()->id(),
        ]);
    }

    public function update(string $id, array $data)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        $kategori->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? $kategori->icon,
            'color' => $data['color'] ?? $kategori->color,
        ]);

        return $kategori;
    }

    public function delete(string $id)
    {
        $kategori = KategoriDokumen::findOrFail($id);
        return $kategori->delete();
    }

    public function all()
    {
        return KategoriDokumen::orderBy('name')->get();
    }

    public function countAll(): int
    {
        return KategoriDokumen::count();
    }

    public function countUsedInDocuments(): int
    {
        // Mencari kategori yang slug-nya ada di kolom 'kategori' tabel dokumens
        return KategoriDokumen::whereIn('slug', function($query) {
            $query->select('kategori')->from('dokumens');
        })->count();
    }
}
