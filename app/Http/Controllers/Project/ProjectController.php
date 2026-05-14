<?php

namespace App\Http\Controllers\Project;

use App\Constants\Project\ProjectMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\Project\ProjectResource;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\Team\TeamServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    private string $title = ProjectMessages::TITLE;
    private string $subtitle = ProjectMessages::SUBTITLE;
    private string $indexView = ProjectMessages::INDEXVIEW;
    private string $showView = ProjectMessages::SHOWVIEW;
    private string $dataUrl = ProjectMessages::PAGINATIONURL;
    private string $dataTableId = ProjectMessages::TABLEID;
    private string $icon = ProjectMessages::ICON;
    private string $aksesPermission = ProjectMessages::AKSES_PERMISSION;

    private ProjectServiceInterface $projectService;
    private TeamServiceInterface $teamService;

    public function __construct(ProjectServiceInterface $projectService, TeamServiceInterface $teamService)
    {
        $this->projectService = $projectService;
        $this->teamService = $teamService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For now, let's skip Gate if permission doesn't exist yet, or just use it
        // Gate::authorize('read ' . $this->aksesPermission);

        $stats = $this->projectService->getIndexData();

        $data = array_merge([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'dataUrl' => route($this->dataUrl),
            'showUrl' => route(ProjectMessages::SHOWURL, ['project' => '__ID__']),
            'editUrl' => route(ProjectMessages::EDITURL, ['project' => '__ID__']),
            'destroyUrl' => route(ProjectMessages::DESTROYURL, ['project' => '__ID__']),
            'dataTableId' => $this->dataTableId,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
        ], $stats);

        return view($this->indexView, $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('create ' . $this->aksesPermission);

        $teams = $this->teamService->getAllTeams();

        $data = [
            'title' => 'Tambah ' . $this->title,
            'subtitle' => 'Buat project baru & tetapkan tim kerja',
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'teams' => $teams,
        ];

        return view(ProjectMessages::CREATEVIEW, $data);
    }

    /**
     * Get paginated projects for DataTable.
     */
    public function getAllPaginated(Request $request)
    {
        // Gate::authorize('read ' . $this->aksesPermission);

        $params = $request->validate([
            'search' => 'nullable|string',
            'status' => 'nullable|string',
            'row_per_page' => 'required|integer',
            'sort_col' => 'nullable|string',
            'sort_dir' => 'nullable|string|in:asc,desc',
        ]);

        try {
            $projects = $this->projectService->getPaginatedProjects(
                $params['search'] ?? null,
                $params['status'] ?? null,
                $params['row_per_page'],
                $params['sort_col'] ?? 'created_at',
                $params['sort_dir'] ?? 'desc'
            );

            // We'll need a ProjectResource later, for now we can just return raw if needed, 
            // but let's be consistent and create ProjectResource.
            return ResponseHelper::success(
                ProjectMessages::RETRIEVED_SUCCESS,
                PaginateResource::make($projects, ProjectResource::class)
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\Project\StoreProjectRequest $request)
    {
        // Gate::authorize('create ' . $this->aksesPermission);

        try {
            $project = $this->projectService->storeProject($request->validated());

            return ResponseHelper::success(
                ProjectMessages::CREATED_SUCCESS,
                $project
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Gate::authorize('read ' . $this->aksesPermission);
        
        $project = $this->projectService->getProjectByUlid($id);

        $data = [
            'title' => 'Detail ' . $this->title,
            'subtitle' => 'Lihat detail perkembangan project',
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'detailDataUrl' => route('projects.detailData', ['project' => $id]),
            'projectId' => $id,
            'project' => $project,
        ];

        return view($this->showView, $data);
    }

    /**
     * Get project detail data for AJAX.
     */
    public function getDetailData(string $id)
    {
        try {
            $data = $this->projectService->getProjectDetailData($id);
            return ResponseHelper::success('Data detail project berhasil diambil', $data);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Gate::authorize('update ' . $this->aksesPermission);

        $project = $this->projectService->getProjectByUlid($id);
        $teams = $this->teamService->getAllTeams();

        $data = [
            'title' => 'Ubah ' . $this->title,
            'subtitle' => 'Perbarui detail project & tim kerja',
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'project' => $project,
            'teams' => $teams,
        ];

        return view(ProjectMessages::EDITVIEW, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\Project\StoreProjectRequest $request, string $id)
    {
        // Gate::authorize('update ' . $this->aksesPermission);

        try {
            $project = $this->projectService->updateProject($id, $request->validated());

            return ResponseHelper::success(
                ProjectMessages::UPDATED_SUCCESS,
                $project
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Gate::authorize('delete ' . $this->aksesPermission);

        try {
            $this->projectService->deleteProject($id);

            return ResponseHelper::success(
                ProjectMessages::DELETED_SUCCESS,
                null
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a new discussion comment with optional attachments.
     */
    public function storeDiskusi(Request $request, string $id)
    {
        $params = $request->validate([
            'content' => 'nullable|string',
            'parent_id' => 'nullable|exists:diskusis,id',
            'files' => 'nullable|array',
            'files.*' => 'file|max:20480', // 20MB max per file
        ]);

        try {
            $diskusi = $this->projectService->storeDiskusi($id, $params);
            return ResponseHelper::success('Komentar berhasil dikirim', $diskusi);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    public function updateDiskusi(Request $request, string $id)
    {
        $params = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $diskusi = $this->projectService->updateDiskusi($id, $params);
            return ResponseHelper::success('Komentar berhasil diperbarui', $diskusi);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    public function destroyDiskusi(string $id)
    {
        try {
            $this->projectService->deleteDiskusi($id);
            return ResponseHelper::success('Komentar berhasil dihapus');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Securely serve media files from Diskusi attachments.
     */
    public function showMedia(string $mediaId)
    {
        try {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($mediaId);
            
            if (!auth()->check()) abort(403);

            $path = $media->getPath();
            
            // If it's an image, serve as file (inline) for lightbox
            if (str_starts_with($media->mime_type, 'image/')) {
                return response()->file($path);
            }

            // Otherwise, force download
            return response()->download($path, $media->file_name);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
