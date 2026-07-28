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
        $query = $this->model->newQuery()->with(['project', 'uploader', 'media']);

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

            // 1. Ambil semua media_id dari item baru (tipe image maupun HTML content TinyMCE)
            $activeMediaIds = [];
            foreach ($items as $item) {
                if (($item['type'] ?? '') === 'image' && !empty($item['metadata']['media_id'])) {
                    $activeMediaIds[] = (int) $item['metadata']['media_id'];
                }

                if (!empty($item['metadata']['media_ids']) && is_array($item['metadata']['media_ids'])) {
                    foreach ($item['metadata']['media_ids'] as $mId) {
                        $activeMediaIds[] = (int) $mId;
                    }
                }

                if (!empty($item['content'])) {
                    preg_match_all('#/(?:storage|media)/[^"\'\s>]+/(\d+)/#i', $item['content'], $matches);
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $mId) {
                            $activeMediaIds[] = (int) $mId;
                        }
                    }
                }
            }
            $activeMediaIds = array_unique(array_filter($activeMediaIds));

            // 2. Tandai media yang aktif dengan memindahkan ke collection 'builder_images'
            if (!empty($activeMediaIds)) {
                \Spatie\MediaLibrary\MediaCollections\Models\Media::whereIn('id', $activeMediaIds)
                    ->update([
                        'collection_name' => 'builder_images'
                    ]);
            }

            // 3. Bersihkan media lama yang tidak lagi aktif (dihapus dari database dan disk)
            $dokumen->media()
                ->whereIn('collection_name', ['builder_images', 'builder_temp_images'])
                ->whereNotIn('id', $activeMediaIds)
                ->get()
                ->each(function ($media) {
                    $media->delete();
                });

            // 4. Hapus item lama secara satu per satu
            foreach ($dokumen->items as $oldItem) {
                $oldItem->delete();
            }

            // 5. Simpan item baru
            foreach ($items as $index => $item) {
                $type = $item['type'] ?? 'text';
                $newDocItem = $dokumen->items()->create([
                    'type'     => $type,
                    'content'  => $item['content'] ?? null,
                    'metadata' => $item['metadata'] ?? null,
                    'order'    => $index,
                ]);

                // Jika tipe adalah image dan ada media_id, perbarui URL-nya jika perlu
                if ($type === 'image' && !empty($item['metadata']['media_id'])) {
                    $mediaId = $item['metadata']['media_id'];
                    $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                    if ($media) {
                        $newDocItem->update([
                            'content' => $media->getUrl()
                        ]);
                    }
                }
            }

            return $dokumen->load('items');
        });
    }
}
