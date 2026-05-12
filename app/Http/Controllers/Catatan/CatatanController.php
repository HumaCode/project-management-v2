<?php

namespace App\Http\Controllers\Catatan;

use App\Constants\Catatan\CatatanMessages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    private string $title = CatatanMessages::TITLE;
    private string $subtitle = CatatanMessages::SUBTITLE;
    private string $indexView = CatatanMessages::INDEXVIEW;
    private string $icon = CatatanMessages::ICON;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
        ];

        return view($this->indexView, $data);
    }
}
