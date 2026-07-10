<?php

namespace App\Services\Report;

use App\Interface\Report\ReportServiceInterface;
use App\Models\Laporan;
use App\Models\Project;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportService implements ReportServiceInterface
{
    public function generateReport(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $items = $request->input('items', []);
            $projectId = $request->input('project_id');
            
            if (!$projectId || empty($items)) {
                throw new \Exception('Data tidak lengkap.');
            }

            $project = Project::findOrFail($projectId);

            // Prepare documents for PDF
            $documents = [];
            foreach ($items as $item) {
                $doc = Dokumen::with(['items'])->find($item['id']);
                if ($doc) {
                    $doc->custom_description = $item['desc'] ?? $doc->keterangan;
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
                        }
                    }
                    $documents[] = $doc;
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('pages.report.pdf', [
                'documents' => $documents,
                'title' => 'Laporan Proyek: ' . $project->name,
                'project' => $project,
                'date' => now()->translatedFormat('d F Y'),
                'cover_image' => $request->input('cover_image')
            ]);

            // Save Laporan model
            $laporan = Laporan::create([
                'title' => 'Laporan - ' . $project->name . ' - ' . now()->format('d/m/Y H:i'),
                'project_id' => $project->id,
                'user_id' => auth()->id(),
            ]);

            // Save to Spatie Media Library
            $fileName = 'laporan-' . $project->slug . '-' . now()->timestamp . '.pdf';
            $laporan->addMediaFromStream($pdf->output())
                ->usingFileName($fileName)
                ->usingName($laporan->title)
                ->toMediaCollection('reports', 'local'); // disk 'local' is private

            return $laporan;
        });
    }

    public function getHistory(Request $request)
    {
        $user = auth()->user();
        $query = Laporan::with(['project', 'user'])->latest();

        // Access Control Logic
        if (!$user->hasRole(['admin', 'dev'])) {
            $query->whereHas('project.team.members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        return $query->paginate($request->per_page ?? 10);
    }

    public function deleteReport(string $id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->clearMediaCollection('reports');
        return $laporan->delete();
    }
}
