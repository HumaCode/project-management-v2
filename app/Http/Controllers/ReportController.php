<?php

namespace App\Http\Controllers;

use App\Interface\KategoriDokumen\KategoriDokumenServiceInterface;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\Dokumen\DokumenServiceInterface;
use App\Http\Resources\Dokumen\DokumenResource;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $projectService;
    protected $kategoriService;
    protected $dokumenService;

    public function __construct(
        ProjectServiceInterface $projectService,
        KategoriDokumenServiceInterface $kategoriService,
        DokumenServiceInterface $dokumenService
    ) {
        $this->projectService = $projectService;
        $this->kategoriService = $kategoriService;
        $this->dokumenService = $dokumenService;
    }

    public function index(Request $request)
    {
        $title = "Laporan PDF";
        $subtitle = "Susun dokumen dan gambar menjadi laporan PDF profesional";
        $icon = "bi bi-file-earmark-pdf-fill";
        
        $projects = $this->projectService->getAllProjects();
        $kategoris = $this->kategoriService->getAllKategoris();

        return view('pages.report.index', compact('projects', 'kategoris', 'title', 'subtitle', 'icon'));
    }

    public function getAssets(Request $request)
    {
        $projectId = $request->project_id;
        $categoryId = $request->category_id;

        if (!$projectId) {
            return response()->json([]);
        }

        // We use paginate with a large number or implement a getAll in service
        // For now, let's use the existing paginated method but with a high limit
        $assets = $this->dokumenService->getPaginatedDokumens(
            search: null,
            kategori: $categoryId,
            project_id: $projectId,
            perPage: 100
        );

        return DokumenResource::collection($assets);
    }

    public function generate(Request $request)
    {
        // Placeholder for PDF generation
        return response()->json(['message' => 'Laporan sedang diproses...']);
    }
}
