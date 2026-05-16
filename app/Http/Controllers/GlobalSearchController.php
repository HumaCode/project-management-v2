<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Dokumen;
use App\Models\Catatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GlobalSearchController extends Controller
{
    /**
     * Search across multiple models.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'projects' => [],
                'documents' => [],
                'notes' => []
            ]);
        }

        // Search Projects
        $projects = Project::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'status'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->name,
                    'url' => route('projects.show', $p->id),
                    'extra' => strtoupper($p->status)
                ];
            });


        // Search Documents
        $documents = Dokumen::where('nama', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get(['id', 'nama', 'kategori'])
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'title' => $d->nama,
                    'url' => route('dokumen.builder', $d->id),
                    'extra' => strtoupper($d->kategori)
                ];
            });

        // Search Notes
        $notes = Catatan::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'category'])
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'url' => route('catatan.index') . "?search=" . urlencode($n->title),
                    'extra' => strtoupper($n->category)
                ];
            });

        return response()->json([
            'projects' => $projects,
            'documents' => $documents,
            'notes' => $notes
        ]);
    }
}
