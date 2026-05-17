<?php

namespace App\Http\Controllers;

use App\Models\Diagram;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DiagramController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projectsQuery = Project::orderBy('name');
        
        if (!$user->hasRole(['dev', 'admin'])) {
            $projectsQuery->whereHas('pics', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        $projects = $projectsQuery->get();
        return view('pages.diagrams.index', compact('projects'));
    }

    public function getAllPaginated(Request $request)
    {
        $user = auth()->user();
        $query = Diagram::with(['project', 'creator']);

        // Limit visibility based on role
        if (!$user->hasRole(['dev', 'admin'])) {
            $query->whereHas('project', function($q) use ($user) {
                $q->whereHas('pics', function($q2) use ($user) {
                    $q2->where('users.id', $user->id);
                });
            });
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('project', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->has('project_id') && $request->project_id != '') {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $diagrams = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json([
            'status' => 'success',
            'data' => $diagrams
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:flowchart,erd,dfd',
        ]);

        $user = auth()->user();
        if (!$user->hasRole(['dev', 'admin'])) {
            $project = Project::findOrFail($request->project_id);
            if (!$project->pics()->where('users.id', $user->id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda tidak memiliki akses ke proyek ini'
                ], 403);
            }
        }

        try {
            DB::beginTransaction();
            $diagram = Diagram::create([
                'project_id' => $request->project_id,
                'name' => $request->name,
                'type' => $request->type,
                'created_by' => auth()->id(),
            ]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Diagram berhasil dibuat',
                'data' => $diagram
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal membuat diagram: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $diagram = Diagram::findOrFail($id);
            $this->authorize('delete', $diagram);
            
            $diagram->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Diagram berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus diagram'
            ], 500);
        }
    }

    public function builder($id)
    {
        $diagram = Diagram::findOrFail($id);
        $this->authorize('view', $diagram);
        
        return view('pages.diagrams.builder', compact('diagram'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'nullable|array',
            'mermaid_syntax' => 'required|string',
        ]);

        try {
            $diagram = Diagram::findOrFail($id);
            $this->authorize('update', $diagram);
            
            $diagram->update([
                'content' => $request->content,
                'mermaid_syntax' => $request->mermaid_syntax,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Diagram berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal update diagram: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }
}
