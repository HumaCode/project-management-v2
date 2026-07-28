<?php

namespace App\Http\Controllers;

use App\Interface\KategoriDokumen\KategoriDokumenServiceInterface;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\Dokumen\DokumenServiceInterface;
use App\Http\Resources\Dokumen\DokumenResource;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dokumen;

use App\Interface\Report\ReportServiceInterface;
use App\Models\Laporan;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    protected $projectService;
    protected $kategoriService;
    protected $dokumenService;
    protected $reportService;

    public function __construct(
        ProjectServiceInterface $projectService,
        KategoriDokumenServiceInterface $kategoriService,
        DokumenServiceInterface $dokumenService,
        ReportServiceInterface $reportService
    ) {
        $this->projectService = $projectService;
        $this->kategoriService = $kategoriService;
        $this->dokumenService = $dokumenService;
        $this->reportService = $reportService;
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

        // Check project access
        $project = \App\Models\Project::findOrFail($projectId);
        if (Gate::denies('create', [Laporan::class, $project])) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke project ini.'], 403);
        }

        $assets = $this->dokumenService->getPaginatedDokumens(
            search: null,
            kategori: $categoryId,
            project_id: $projectId,
            perPage: 100
        );

        return DokumenResource::collection($assets);
    }

    public function history(Request $request)
    {
        $history = $this->reportService->getHistory($request);
        
        return response()->json([
            'data' => $history->items(),
            'meta' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'total' => $history->total(),
                'per_page' => $history->perPage(),
            ]
        ]);
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
                    $doc->is_image = str_starts_with($media->mime_type, 'image/');
                    if ($doc->is_image && file_exists($media->getPath())) {
                        $ext = pathinfo($media->getPath(), PATHINFO_EXTENSION);
                        $doc->file_path = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($media->getPath()));
                    } else {
                        $doc->file_path = $media->getPath();
                    }
                }
                
                // Also process items media if it's an article/code/text
                foreach($doc->items as $docItem) {
                    if ($docItem->type === 'image') {
                        $mediaId = $docItem->metadata['media_id'] ?? null;
                        $itemMedia = $mediaId ? \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId) : null;
                        if ($itemMedia && file_exists($itemMedia->getPath())) {
                            $ext = pathinfo($itemMedia->getPath(), PATHINFO_EXTENSION);
                            $docItem->file_path = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($itemMedia->getPath()));
                        } else {
                            $docItem->file_path = null;
                        }
                    } elseif ($docItem->type === 'text' || $docItem->type === 'paragraph') {
                        $docItem->content = $this->processHtmlImagesForPdf($docItem->content);
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
        try {
            $projectId = $request->project_id;
            $project = \App\Models\Project::findOrFail($projectId);
            
            if (Gate::denies('create', [Laporan::class, $project])) {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk membuat laporan di project ini.'], 403);
            }

            $laporan = $this->reportService->generateReport($request);

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dibuat dan disimpan ke riwayat.',
                'data' => $laporan
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function download($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        if (Gate::denies('view', $laporan)) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $media = $laporan->getFirstMedia('reports');
        if (!$media) {
            abort(404, 'File laporan tidak ditemukan.');
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        if (Gate::denies('delete', $laporan)) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus laporan ini.'], 403);
        }

        $this->reportService->deleteReport($id);

        return response()->json(['message' => 'Laporan berhasil dihapus.']);
    }

    /**
     * Konversi semua tag <img> HTML di dalam konten menjadi Base64 untuk DomPDF.
     */
    private function processHtmlImagesForPdf(?string $html): string
    {
        if (empty($html)) return '';

        return preg_replace_callback('/<img[^>]+src=["\']([^"\']+)["\']/i', function ($matches) {
            $originalTag = $matches[0];
            $src = $matches[1];

            if (str_starts_with($src, 'data:image/')) {
                return $originalTag;
            }

            $path = parse_url($src, PHP_URL_PATH);
            if ($path) {
                // Strip leading slashes and relative path dots like ../ or ./
                $cleanPath = preg_replace('#^(\.\./|\./|/)+#', '', $path);
                
                // Try public_path first
                $fullPath = public_path($cleanPath);

                if (!file_exists($fullPath)) {
                    $storageRelative = preg_replace('#^storage/#i', '', $cleanPath);
                    $fullPath = storage_path('app/public/' . $storageRelative);
                }

                if (file_exists($fullPath)) {
                    $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $mime = match (strtolower($ext)) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'svg' => 'image/svg+xml',
                        'webp' => 'image/webp',
                        default => 'image/jpeg',
                    };
                    $base64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
                    return str_replace($src, $base64, $originalTag);
                }
            }

            return $originalTag;
        }, $html);
    }
}
