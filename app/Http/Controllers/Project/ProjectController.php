<?php

namespace App\Http\Controllers\Project;

use App\Constants\Project\ProjectMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\Project\ProjectResource;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\RoleManagement\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    private string $title = ProjectMessages::TITLE;
    private string $subtitle = ProjectMessages::SUBTITLE;
    private string $indexView = ProjectMessages::INDEXVIEW;
    private string $dataUrl = ProjectMessages::PAGINATIONURL;
    private string $dataTableId = ProjectMessages::TABLEID;
    private string $icon = ProjectMessages::ICON;
    private string $aksesPermission = ProjectMessages::AKSES_PERMISSION;

    private ProjectServiceInterface $projectService;
    private UserServiceInterface $userService;

    public function __construct(ProjectServiceInterface $projectService, UserServiceInterface $userService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
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

        $users = $this->userService->getUsersByRole('anggota');

        $data = [
            'title' => 'Tambah ' . $this->title,
            'subtitle' => 'Buat project baru & tetapkan anggota tim',
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'users' => $users,
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
        ]);

        try {
            $projects = $this->projectService->getPaginatedProjects(
                $params['search'] ?? null,
                $params['status'] ?? null,
                $params['row_per_page']
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
}
