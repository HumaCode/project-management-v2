<?php

namespace App\Http\Controllers\Dokumen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dokumen',
            'subtitle' => 'Manajemen Dokumen'
        ];
        
        return view('pages.dokumen.index', $data);
    }
}
