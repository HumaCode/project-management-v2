<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\RoleManagement\PermissionMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleManagement\Permission\PermissionStoreRequest;
use App\Http\Requests\RoleManagement\Permission\PermissionUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\PermissionResource;
use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Models\Shield\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    private string $title = PermissionMessages::TITLE;

    private string $subtitle = PermissionMessages::SUBTITLE;

    private string $formView = PermissionMessages::FORMVIEW;

    private string $indexView = PermissionMessages::INDEXVIEW;

    private string $createUrl = PermissionMessages::CREATEURL;

    private string $aksesUrl = PermissionMessages::AKSESURL;

    private string $aksesEditUrl = PermissionMessages::AKSESEDITURL;

    private string $editUrl = PermissionMessages::EDITURL;

    private string $showUrl = PermissionMessages::SHOWURL;

    private string $storeUrl = PermissionMessages::STOREURL;

    private string $updateUrl = PermissionMessages::UPDATEURL;

    private string $destroyUrl = PermissionMessages::DESTROYURL;

    private string $icon = PermissionMessages::ICON;

    private string $dataUrl = PermissionMessages::PAGINATIONURL;

    private string $dataTableId = PermissionMessages::TABLEID;

    private string $aksesPermission = PermissionMessages::AKSES_PERMISSION;

    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
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
            'editUrl' => route($this->editUrl, ['permission' => '__ID__']),
            'showUrl' => route($this->showUrl, ['permission' => '__ID__']),
            'destroyUrl' => route($this->destroyUrl, ['permission' => '__ID__']),
            'dataUrl' => route($this->dataUrl),
            'dataTableId' => $this->dataTableId,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
        ];

        return view($this->indexView, $data);
    }

    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read '.$this->aksesPermission);

        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $permissions = $this->permissionRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(true, PermissionMessages::RETRIEVED_SUCCESS, PaginateResource::make($permissions, PermissionResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Permission $permission)
    {
        return view($this->formView, [
            'action' => route($this->storeUrl),
            'data' => $permission,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $permission = $this->permissionRepository->create($request);

            return ResponseHelper::jsonResponse(true, PermissionMessages::CREATED_SUCCESS, new PermissionResource($permission), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view($this->formView, [
            'action' => route($this->updateUrl, $permission->id),
            'data' => $permission,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $request = $request->validated();

        try {
            $permission = $this->permissionRepository->update($permission->id, $request);

            return ResponseHelper::jsonResponse(true, PermissionMessages::UPDATED_SUCCESS, new PermissionResource($permission), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            $this->permissionRepository->delete($permission->id);

            return ResponseHelper::jsonResponse(true, PermissionMessages::DELETED_SUCCESS, null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
