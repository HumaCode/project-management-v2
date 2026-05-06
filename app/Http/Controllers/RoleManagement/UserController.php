<?php

namespace App\Http\Controllers\RoleManagement;

use App\Constants\RoleManagement\UserMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleManagement\User\UserStoreRequest;
use App\Http\Requests\RoleManagement\User\UserUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\RoleManagement\UserResource;
use App\Interface\RoleManagement\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private string $title = UserMessages::TITLE;
    private string $subtitle = UserMessages::SUBTITLE;
    private string $formView = UserMessages::FORMVIEW;
    private string $indexView = UserMessages::INDEXVIEW;
    private string $createUrl = UserMessages::CREATEURL;
    private string $editUrl = UserMessages::EDITURL;
    private string $showUrl = UserMessages::SHOWURL;
    private string $storeUrl = UserMessages::STOREURL;
    private string $updateUrl = UserMessages::UPDATEURL;
    private string $destroyUrl = UserMessages::DESTROYURL;
    private string $icon = UserMessages::ICON;
    private string $dataUrl = UserMessages::PAGINATIONURL;
    private string $dataTableId = UserMessages::TABLEID;
    private string $aksesPermission = UserMessages::AKSES_PERMISSION;
    private string $detailView = UserMessages::DETAILVIEW;

    private UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
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
            'rolesActive' => $this->userService->getActiveRoles(),
        ];

        // Ambil statistik melalui Service
        $data = array_merge($data, $this->userService->getUserStatistics());

        return view($this->indexView, $data);
    }

    public function getAllPaginated(Request $request)
    {
        Gate::authorize('read ' . $this->aksesPermission);

        $validated = $request->validate([
            'search'       => 'nullable|string',
            'status'       => 'nullable|string',
            'type'         => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $users = $this->userService->getPaginatedUsers(
                $validated['search'] ?? null,
                $validated['status'] ?? null,
                $validated['type'] ?? null,
                $validated['row_per_page'],
            );

            return ResponseHelper::jsonResponse(
                true,
                UserMessages::RETRIEVED_SUCCESS,
                PaginateResource::make($users, UserResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        return view($this->formView, [
            'action' => route($this->storeUrl),
            'data'   => $user,
            'rolesActive'  => $this->userService->getActiveRoles(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return ResponseHelper::jsonResponse(
                true,
                UserMessages::CREATED_SUCCESS,
                new UserResource($user),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view($this->formView, [
            'action' => route($this->updateUrl, $user->id),
            'data'   => $user,
            'rolesActive'  => $this->userService->getActiveRoles(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $updated = $this->userService->updateUser($user->id, $request->validated());

            return ResponseHelper::jsonResponse(
                true,
                UserMessages::UPDATED_SUCCESS,
                new UserResource($updated),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user->id);

            return ResponseHelper::jsonResponse(true, UserMessages::DELETED_SUCCESS, null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view($this->detailView, [
            'data' => $user
        ]);
    }

    /**
     * Menyetujui user (Aktivasi).
     */
    public function approve(string $id)
    {
        try {
            $user = $this->userService->approveUser($id);

            return ResponseHelper::jsonResponse(
                true,
                'User berhasil diaktifkan.',
                new UserResource($user),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Menolak/Menonaktifkan user.
     */
    public function reject(string $id)
    {
        try {
            $user = $this->userService->rejectUser($id);

            return ResponseHelper::jsonResponse(
                true,
                'User berhasil dinonaktifkan.',
                new UserResource($user),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Reset password user.
     */
    public function resetPassword(Request $request, string $id)
    {
        $validated = $request->validate([
            'mode'        => 'required|in:manual,link',
            'newPassword' => 'required_if:mode,manual|nullable|string|min:8',
        ]);

        try {
            $user = $this->userService->resetUserPassword($id, $validated);

            $msg = ($validated['mode'] === 'manual')
                ? 'Password berhasil direset secara manual.'
                : 'Link reset password berhasil dikirim ke email user.';

            return ResponseHelper::jsonResponse(true, $msg, new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
