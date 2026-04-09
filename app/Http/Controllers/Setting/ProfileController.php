<?php

namespace App\Http\Controllers\Setting;

use App\Constants\Setting\ProfileMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
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
        $profile = $this->profileRepository->getProfileByUserId($user->id);

        $data = $request->only(['name', 'email', 'phone', 'city']);

        $this->profileRepository->update($profile->id, $data);

        return redirect()->route('profil.index')->with('success', ProfileMessages::UPDATED_SUCCESS);
    }
}
