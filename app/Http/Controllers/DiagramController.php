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
        $projects = Project::orderBy('name')->get();
        return view('pages.diagrams.index', compact('projects'));
    }

    public function getAllPaginated(Request $request)
    {
        $query = Diagram::with(['project', 'creator']);

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
