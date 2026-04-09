<?php

namespace App\Http\Controllers\Setting;

use App\Constants\Setting\ProfileMessages;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    private string $title = ProfileMessages::TITLE;

    private string $subtitle = ProfileMessages::SUBTITLE;

    private string $indexView = ProfileMessages::INDEXVIEW;

    private string $icon = ProfileMessages::ICON;

    private string $aksesPermission = ProfileMessages::AKSES_PERMISSION;

    public function index()
    {
        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
        ];

        return view($this->indexView, $data);
    }
}
