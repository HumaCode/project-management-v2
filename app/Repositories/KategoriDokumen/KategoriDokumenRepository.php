<?php

namespace App\Repositories\KategoriDokumen;

use App\Interface\KategoriDokumen\KategoriDokumenRepositoryInterface;
use App\Models\KategoriDokumen;
use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KategoriDokumenRepository implements KategoriDokumenRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage)
    {
        $query = KategoriDokumen::with('creator:id,name');

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
        return KategoriDokumen::with('creator:id,name')->findOrFail($id);
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
        return KategoriDokumen::select('id', 'name', 'slug', 'icon', 'color')->orderBy('name')->get();
    }

    public function countAll(): int
    {
        return KategoriDokumen::count();
    }

    public function countUsedInDocuments(): int
    {
        // Gunakan WHERE EXISTS yang lebih cepat pada indeks SQL
        return KategoriDokumen::whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('dokumens')
                  ->whereColumn('dokumens.kategori', 'kategori_dokumens.slug');
        })->count();
    }
}
