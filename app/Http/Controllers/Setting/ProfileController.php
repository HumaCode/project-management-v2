<?php

namespace App\Http\Controllers\Setting;

use App\Constants\Setting\ProfileMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\ProfileUpdatePasswordRequest;
use App\Http\Requests\Setting\ProfileUpdateRequest;
use App\Interface\Setting\ProfileRepositoryInterface;
use App\Models\User;

class ProfileController extends Controller
{
    private string $title = ProfileMessages::TITLE;

    private string $subtitle = ProfileMessages::SUBTITLE;

    private string $indexView = ProfileMessages::INDEXVIEW;

    private string $icon = ProfileMessages::ICON;

    private string $aksesPermission = ProfileMessages::AKSES_PERMISSION;

    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index()
    {
        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'profile' => $this->profileRepository->getProfileByUserId(user('id')),
        ];

        return view($this->indexView, $data);
    }

    public function update(ProfileUpdateRequest $request, User $user)
    {
        try {
            // 1. Ambil semua data yang sudah lulus validasi (termasuk file gambar jika ada)
            $data = $request->validated();

            // 2. Lempar proses update sepenuhnya ke Repository
            $this->profileRepository->update($user->id, $data);

            // 3. Return response JSON untuk jQuery AJAX
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
            ]);

        } catch (\Exception $e) {
            // 4. Return pesan error JSON jika terjadi kegagalan
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil: '.$e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(ProfileUpdatePasswordRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            $this->profileRepository->updatePassword($user->id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui password: '.$e->getMessage(),
            ], 500);
        }
    }
}
