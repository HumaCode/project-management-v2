<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\GlobalMessages;
use App\Constants\RoleMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleManagement\Role\RoleStoreRequest;
use App\Http\Requests\RoleManagement\Role\RoleUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\RoleResource;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Models\Shield\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private string $title = RoleMessages::TITLE;
    private string $subtitle = RoleMessages::SUBTITLE;
    private string $formView = RoleMessages::FORMVIEW;
    private string $indexView = RoleMessages::INDEXVIEW;
    private string $createUrl = RoleMessages::CREATEURL;
    private string $aksesUrl = RoleMessages::AKSESURL;
    private string $aksesEditUrl = RoleMessages::AKSESEDITURL;
    private string $editUrl = RoleMessages::EDITURL;
    private string $showUrl = RoleMessages::SHOWURL;

    private string $storeUrl = RoleMessages::STOREURL;

    private string $updateUrl = RoleMessages::UPDATEURL;

    private string $destroyUrl = RoleMessages::DESTROYURL;

    private string $icon = RoleMessages::ICON;

    private string $dataUrl = RoleMessages::PAGINATIONURL;

    private string $dataTableId = RoleMessages::TABLEID;

    private string $aksesPermission = RoleMessages::AKSES_PERMISSION;

    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gate::authorize('read ' . $this->aksesPermission);

        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'createUrl' => route($this->createUrl),
            'editUrl' => route($this->editUrl, ['role' => '__ID__']),
            'aksesUrl' => route($this->aksesUrl, ['role' => '__ID__']),
            'showUrl' => route($this->showUrl, ['role' => '__ID__']),
            'destroyUrl' => route($this->destroyUrl, ['role' => '__ID__']),
            'dataUrl' => route($this->dataUrl),
            'dataTableId' => $this->dataTableId,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
        ];

        return view($this->indexView, $data);
    }

    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read ' . $this->aksesPermission);

        $request = $request->validate([
            'search' => 'nullable|string',
            'status' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $roles = $this->roleRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['status'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(true, RoleMessages::RETRIEVED_SUCCESS, PaginateResource::make($roles, RoleResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Role $role)
    {
        return view($this->formView, [
            'action' => route($this->storeUrl),
            'data' => $role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $role = $this->roleRepository->create($request);

            return ResponseHelper::jsonResponse(true, RoleMessages::CREATED_SUCCESS, new RoleResource($role), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // 
    }

    public function akses(Role $role)
    {
        Gate::authorize('akses ' . $this->aksesPermission);

        return view($this->formView, [
            'action'    => route($this->aksesEditUrl, $role->id),
            'type'      => 'show',
            'data'      => $role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view($this->formView, [
            'action'            => route($this->updateUrl, $role->id),
            'data'              => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        $request = $request->validated();

        try {
            $role = $this->roleRepository->update($role->id, $request);

            return ResponseHelper::jsonResponse(true, RoleMessages::UPDATED_SUCCESS, new RoleResource($role), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $role = $this->roleRepository->getById($role->id);
            if (!$role) {
                return ResponseHelper::jsonResponse(false, GlobalMessages::NOT_FOUND, null, 404);
            }

            $this->roleRepository->delete($role->id);

            return ResponseHelper::jsonResponse(true, RoleMessages::DELETED_SUCCESS, null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
