<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\RoleMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\RoleResource;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    private string $title               = RoleMessages::TITLE;
    private string $subtitle            = RoleMessages::SUBTITLE;
    private string $formView            = RoleMessages::FORMVIEW;
    private string $indexView           = RoleMessages::INDEXVIEW;

    private string $createUrl           = RoleMessages::CREATEURL;
    private string $editUrl             = RoleMessages::EDITURL;
    private string $showUrl             = RoleMessages::SHOWURL;
    private string $storeUrl            = RoleMessages::STOREURL;
    private string $updateUrl           = RoleMessages::UPDATEURL;
    private string $destroyUrl          = RoleMessages::DESTROYURL;

    private string $icon                = RoleMessages::ICON;
    private string $dataUrl             = RoleMessages::PAGINATIONURL;
    private string $dataTableId         = RoleMessages::TABLEID;
    private string $aksesPermission     = RoleMessages::AKSES_PERMISSION;


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
            'title'             => $this->title,
            'subtitle'          => $this->subtitle,
            'createUrl'         => route($this->createUrl),
            'editUrl'           => route($this->editUrl, ['role' => '__ID__']),
            'showUrl'           => route($this->showUrl, ['role' => '__ID__']),
            'destroyUrl'        => route($this->destroyUrl, ['role' => '__ID__']),
            'dataUrl'           => route($this->dataUrl),
            'dataTableId'       => $this->dataTableId,
            'icon'              => $this->icon,
            'permissionAkses'   => $this->aksesPermission,
        ];

        return view($this->indexView, $data);
    }

    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read ' . $this->aksesPermission);

        $request = $request->validate([
            'search'        => 'nullable|string',
            'status'        => 'nullable|string',
            'row_per_page'  => 'required|integer'
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
