<?php

namespace App\Services\Project;

use App\Interface\Project\ProjectServiceInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    public function getIndexData(): array
    {
        $counts = Project::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count,
            SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done_count,
            SUM(CASE WHEN status = 'to_do' THEN 1 ELSE 0 END) as to_do_count
        ")->first();

        return [
            'total_projects' => (int) ($counts->total ?? 0),
            'in_progress' => (int) ($counts->in_progress_count ?? 0),
            'done' => (int) ($counts->done_count ?? 0),
            'to_do' => (int) ($counts->to_do_count ?? 0),
        ];
    }

    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage)
    {
        // Eager load media juga untuk menghindari N+1 saat memproses avatar di Resource
        $query = Project::with(['creator.media', 'pics.media']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($rowPerPage);
    }

    public function storeProject(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            // Transform dates
            $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            
            $data['created_by'] = auth()->id();

            $project = Project::create($data);

            // Sync PICs
            if (isset($data['pics'])) {
                $project->pics()->sync($data['pics']);
            }

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }

            return $project;
        });
    }

    public function getProjectByUlid(string $ulid): Project
    {
        return Project::with(['pics', 'media'])->findOrFail($ulid);
    }

    public function updateProject(string $ulid, array $data): Project
    {
        return DB::transaction(function () use ($ulid, $data) {
            $project = Project::findOrFail($ulid);

            // Transform dates
            if (isset($data['start_date'])) {
                $data['start_date'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['start_date'])->format('Y-m-d');
            }
            if (isset($data['deadline'])) {
                $data['deadline'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['deadline'])->format('Y-m-d');
            }

            $project->update($data);

            // Sync PICs
            if (isset($data['pics'])) {
                $project->pics()->sync($data['pics']);
            }

            // Handle Thumbnail
            if (isset($data['thumbnail']) && $data['thumbnail'] instanceof \Illuminate\Http\UploadedFile) {
                $project->clearMediaCollection('thumbnail');
                $project->addMedia($data['thumbnail'])
                    ->toMediaCollection('thumbnail');
            }

            return $project;
        });
    }

    public function deleteProject(string $ulid): bool
    {
        return DB::transaction(function () use ($ulid) {
            $project = Project::findOrFail($ulid);

            // Bersihkan relasi many-to-many dengan PIC (agar bersih di pivot table)
            $project->pics()->detach();

            // Hapus project (karena trait InteractsWithMedia, file fisik di public/storage 
            // umumnya otomatis terhapus saat model di-delete, namun jika diperlukan bisa dipanggil clearMediaCollection)
            $project->clearMediaCollection('thumbnail');

            return $project->delete();
        });
    }
}
