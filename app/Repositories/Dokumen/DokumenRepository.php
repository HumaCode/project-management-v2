<?php

namespace App\Repositories\Dokumen;

use App\Interface\Dokumen\DokumenRepositoryInterface;
use App\Models\Dokumen;
use App\Repositories\BaseRepository;

class DokumenRepository extends BaseRepository implements DokumenRepositoryInterface
{
    public function __construct(Dokumen $model)
    {
        parent::__construct($model);
    }

    public function getPaginated($search, $kategori, $project_id, $perPage, $type = null)
    {
        $query = $this->model->newQuery()->with(['project', 'uploader']);

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($project_id) {
            $query->where('project_id', $project_id);
        }

        if ($type) {
            $query->where('type', $type);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getStatistics()
    {
        return [
            'total_dokumen' => $this->model->count(),
            'total_kategori' => $this->model->distinct('kategori')->count('kategori'),
            'total_size' => '156 MB', // Placeholder
            'new_this_month' => $this->model->whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function saveItems(string $id, array $items)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $items) {
            $dokumen = $this->model->findOrFail($id);

            // Hapus item lama (jika ingin clean rebuild)
            $dokumen->items()->delete();

            // Simpan item baru
            foreach ($items as $index => $item) {
                $dokumen->items()->create([
                    'type'     => $item['type'],
                    'content'  => $item['content'] ?? null,
                    'metadata' => $item['metadata'] ?? null,
                    'order'    => $index,
                ]);
            }

            return $dokumen->load('items');
        });
    }
}
