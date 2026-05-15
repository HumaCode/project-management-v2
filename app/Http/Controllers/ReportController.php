<?php

namespace App\Http\Controllers;

use App\Interface\KategoriDokumen\KategoriDokumenServiceInterface;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\Dokumen\DokumenServiceInterface;
use App\Http\Resources\Dokumen\DokumenResource;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dokumen;

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

    public function preview(Request $request)
    {
        $items = $request->input('items', []);
        
        if (empty($items)) {
            return response()->json(['message' => 'Pilih setidaknya satu dokumen.'], 422);
        }

        $documents = [];
        foreach ($items as $item) {
            $doc = Dokumen::with(['project', 'items'])->find($item['id']);
            if ($doc) {
                // Attach description from request if provided
                $doc->custom_description = $item['desc'] ?? $doc->keterangan;
                
                // Get media path for DomPDF
                $media = $doc->getFirstMedia('files');
                if ($media) {
                    $doc->file_path = $media->getPath();
                    $doc->is_image = str_starts_with($media->mime_type, 'image/');
                }
                
                // Also process items media if it's an article/code
                foreach($doc->items as $docItem) {
                    if ($docItem->type === 'image') {
                        $itemMedia = $docItem->getFirstMedia('item_files');
                        $docItem->file_path = $itemMedia ? $itemMedia->getPath() : null;
                    }
                }
                
                $documents[] = $doc;
            }
        }

        if (empty($documents)) {
            return response()->json(['message' => 'Dokumen tidak ditemukan.'], 404);
        }

        $pdf = Pdf::loadView('pages.report.pdf', [
            'documents' => $documents,
            'title' => 'Pratinjau Laporan Proyek',
            'project' => $documents[0]->project ?? null,
            'date' => now()->translatedFormat('d F Y'),
            'cover_image' => $request->input('cover_image')
        ]);

        return $pdf->stream('laporan-preview.pdf');
    }

    public function generate(Request $request)
    {
        $items = $request->input('items', []);
        
        if (empty($items)) {
            return response()->json(['message' => 'Pilih setidaknya satu dokumen.'], 422);
        }

        $documents = [];
        foreach ($items as $item) {
            $doc = Dokumen::with(['project', 'items'])->find($item['id']);
            if ($doc) {
                $doc->custom_description = $item['desc'] ?? $doc->keterangan;
                $media = $doc->getFirstMedia('files');
                if ($media) {
                    $doc->file_path = $media->getPath();
                    $doc->is_image = str_starts_with($media->mime_type, 'image/');
                }
                
                foreach($doc->items as $docItem) {
                    if ($docItem->type === 'image') {
                        $itemMedia = $docItem->getFirstMedia('item_files');
                        $docItem->file_path = $itemMedia ? $itemMedia->getPath() : null;
                    }
                }

                $documents[] = $doc;
            }
        }

        $pdf = Pdf::loadView('pages.report.pdf', [
            'documents' => $documents,
            'title' => 'Laporan Proyek',
            'project' => $documents[0]->project ?? null,
            'date' => now()->translatedFormat('d F Y'),
            'cover_image' => $request->input('cover_image')
        ]);

        return $pdf->download('laporan-' . now()->timestamp . '.pdf');
    }
}
