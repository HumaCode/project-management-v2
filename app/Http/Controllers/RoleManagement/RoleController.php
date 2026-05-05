<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\GlobalMessages;
use App\Constants\RoleManagement\RoleMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleManagement\Role\AksesRoleRequest;
use App\Http\Requests\RoleManagement\Role\RoleStoreRequest;
use App\Http\Requests\RoleManagement\Role\RoleUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\RoleResource;
use App\Interface\RoleManagement\RoleServiceInterface;
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

    private RoleServiceInterface $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('read ' . $this->aksesPermission);

        $stats = $this->roleService->getIndexData();

        $data = array_merge([
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
        ], $stats);

        return view($this->indexView, $data);
    }

    /**
     * Get paginated roles for DataTable.
     */
    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read ' . $this->aksesPermission);

        $params = $request->validate([
            'search' => 'nullable|string',
            'status' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $roles = $this->roleService->getPaginatedRoles(
                $params['search'] ?? null,
                $params['status'] ?? null,
                $params['row_per_page']
            );

            return ResponseHelper::success(
                RoleMessages::RETRIEVED_SUCCESS,
                PaginateResource::make($roles, RoleResource::class)
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
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
        try {
            $role = $this->roleService->createRole($request->validated());

            return ResponseHelper::success(
                RoleMessages::CREATED_SUCCESS,
                new RoleResource($role),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the access configuration form.
     */
    public function akses(Role $role)
    {
        Gate::authorize('akses ' . $this->aksesPermission);

        return view($this->formView, [
            'action' => route($this->aksesEditUrl, $role->id),
            'type' => 'akses',
            'data' => $role,
        ]);
    }

    /**
     * Update access configuration (permissions) for a role.
     */
    public function aksesedit(AksesRoleRequest $request, Role $role)
    {
        Gate::authorize('akses ' . $this->aksesPermission);

        try {
            $validated = $request->validated();
            $this->roleService->syncPermissions($role->id, $validated['permissions'] ?? []);

            return ResponseHelper::success(RoleMessages::AKSES_UPDATED_SUCCESS);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view($this->formView, [
            'action' => route($this->updateUrl, $role->id),
            'data' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $role = $this->roleService->updateRole($role->id, $request->validated());

            return ResponseHelper::success(
                RoleMessages::UPDATED_SUCCESS,
                new RoleResource($role)
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->deleteRole($role->id);

            return ResponseHelper::success(RoleMessages::DELETED_SUCCESS);
        } catch (\Exception $e) {
            // Check if it's a 404 or a logical error from repository
            $code = str_contains($e->getMessage(), GlobalMessages::NOT_FOUND) ? 404 : 500;
            return ResponseHelper::error($e->getMessage(), $code);
        }
    }
}
