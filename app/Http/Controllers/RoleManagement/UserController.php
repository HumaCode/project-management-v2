<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\RoleManagement\UserMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\UserResource;
use App\Interface\RoleManagement\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private string $title = UserMessages::TITLE;

    private string $subtitle = UserMessages::SUBTITLE;

    private string $formView = UserMessages::FORMVIEW;

    private string $indexView = UserMessages::INDEXVIEW;

    private string $createUrl = UserMessages::CREATEURL;

    private string $aksesUrl = UserMessages::AKSESURL;

    private string $aksesEditUrl = UserMessages::AKSESEDITURL;

    private string $editUrl = UserMessages::EDITURL;

    private string $showUrl = UserMessages::SHOWURL;

    private string $storeUrl = UserMessages::STOREURL;

    private string $updateUrl = UserMessages::UPDATEURL;

    private string $destroyUrl = UserMessages::DESTROYURL;

    private string $icon = UserMessages::ICON;

    private string $dataUrl = UserMessages::PAGINATIONURL;

    private string $dataTableId = UserMessages::TABLEID;

    private string $aksesPermission = UserMessages::AKSES_PERMISSION;

    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
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
            'editUrl' => route($this->editUrl, ['user' => '__ID__']),
            'showUrl' => route($this->showUrl, ['user' => '__ID__']),
            'destroyUrl' => route($this->destroyUrl, ['user' => '__ID__']),
            'dataUrl' => route($this->dataUrl),
            'dataTableId' => $this->dataTableId,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'rolesActive' => $this->userRepository->getRoleActive(),
            'countAllUser' => $this->userRepository->countAllUser(),
            'countAllUserActive' => $this->userRepository->countAllUserActive(),
            'countAllUserInactive' => $this->userRepository->countAllUserInactive(),
            'countNewUser' => $this->userRepository->countNewUser(),
        ];

        return view($this->indexView, $data);
    }

    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read '.$this->aksesPermission);

        $request = $request->validate([
            'search' => 'nullable|string',
            'status' => 'nullable|string',
            'type' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $users = $this->userRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['status'] ?? null,
                $request['type'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(true, UserMessages::RETRIEVED_SUCCESS, PaginateResource::make($users, UserResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function approve(string $id)
    {
        Gate::authorize('update '.$this->aksesPermission);

        try {
            $user = $this->userRepository->approve($id);

            return ResponseHelper::jsonResponse(true, UserMessages::UPDATED_SUCCESS, UserResource::make($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function reject(string $id)
    {
        Gate::authorize('update '.$this->aksesPermission);

        try {
            $user = $this->userRepository->reject($id);

            return ResponseHelper::jsonResponse(true, UserMessages::UPDATED_SUCCESS, UserResource::make($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function resetPassword(Request $request, string $id)
    {
        Gate::authorize('update '.$this->aksesPermission);

        try {
            // $result sekarang berisi Objek User, BUKAN Array!
            $result = $this->userRepository->resetPassword($id, $request->all());

            // Kita tentukan pesan sukses kustom berdasarkan mode yang dipilih
            $pesanSukses = ($request->mode === 'link')
                ? 'Link reset berhasil dikirim ke email.'
                : 'Password berhasil diperbarui secara manual.';

            return ResponseHelper::jsonResponse(true, $pesanSukses, UserResource::make($result), 200);

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
