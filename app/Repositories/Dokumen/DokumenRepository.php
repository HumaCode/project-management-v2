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

        $user = auth()->user();
        if ($user && !$user->hasRole('dev') && !$user->hasRole('admin')) {
            $query->where(function($q) use ($user) {
                $q->whereHas('project.team.members', function($mq) use ($user) {
                    $mq->where('users.id', $user->id);
                })->orWhereHas('project', function($pq) use ($user) {
                    $pq->where('created_by', $user->id);
                })->orWhere('user_id', $user->id);
            });
        }

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
        $user = auth()->user();
        $cacheKey = "dokumen_stats_user_{$user->id}";

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user) {
            $dokumenQuery = $this->model->newQuery();
            $mediaQuery = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('collection_name', 'files')
                ->whereHasMorph('model', [Dokumen::class]);

            if ($user && !$user->hasRole('dev') && !$user->hasRole('admin')) {
                // Filter query dokumen utama
                $dokumenQuery->where(function($q) use ($user) {
                    $q->whereHas('project.team.members', function($mq) use ($user) {
                        $mq->where('users.id', $user->id);
                    })->orWhereHas('project', function($pq) use ($user) {
                        $pq->where('created_by', $user->id);
                    })->orWhere('user_id', $user->id);
                });

                // Filter query media library
                $mediaQuery->whereHasMorph('model', [Dokumen::class], function($q) use ($user) {
                    $q->where(function($sq) use ($user) {
                        $sq->whereHas('project.team.members', function($mq) use ($user) {
                            $mq->where('users.id', $user->id);
                        })->orWhereHas('project', function($pq) use ($user) {
                            $pq->where('created_by', $user->id);
                        })->orWhere('user_id', $user->id);
                    });
                });
            }

            $totalBytes = $mediaQuery->sum('size');
            $totalMb = round($totalBytes / 1024 / 1024, 2);

            return [
                'total_dokumen' => $dokumenQuery->count(),
                'total_kategori' => $dokumenQuery->distinct('kategori')->count('kategori'),
                'total_size' => $totalMb . ' MB',
                'new_this_month' => $dokumenQuery->whereMonth('created_at', now()->month)->count(),
            ];
        });
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
