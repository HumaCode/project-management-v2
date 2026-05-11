<?php

namespace App\Services\Project;

use App\Interface\Project\ProjectServiceInterface;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ProjectService implements ProjectServiceInterface
{
    public function getIndexData(): array
    {
        return [
            'total_projects' => Project::count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'done' => Project::where('status', 'done')->count(),
            'to_do' => Project::where('status', 'to_do')->count(),
        ];
    }

    public function getPaginatedProjects(?string $search, ?string $status, int $rowPerPage)
    {
        $query = Project::with(['creator', 'pics']);

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
