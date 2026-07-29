<?php

namespace App\Http\Controllers\Catatan;

use App\Constants\Catatan\CatatanMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Catatan\StoreCatatanRequest;
use App\Http\Requests\Catatan\UpdateCatatanRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\Catatan\CatatanResource;
use App\Interface\Catatan\CatatanServiceInterface;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    private string $title = CatatanMessages::TITLE;
    private string $subtitle = CatatanMessages::SUBTITLE;
    private string $indexView = CatatanMessages::INDEXVIEW;
    private string $icon = CatatanMessages::ICON;
    private string $dataTableId = CatatanMessages::TABLEID;
    private string $dataUrl = CatatanMessages::PAGINATIONURL;
    private string $storeUrl = CatatanMessages::STOREURL;

    private CatatanServiceInterface $catatanService;

    public function __construct(CatatanServiceInterface $catatanService)
    {
        $this->catatanService = $catatanService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = $this->catatanService->getIndexData();
        $user = auth()->user();
        $projectQuery = Project::orderBy('name');
        
        // Filter project jika bukan dev/admin
        if (!$user->hasRole('dev') && !$user->hasRole('admin')) {
            $projectQuery->where(function($q) use ($user) {
                $q->whereHas('team.members', function($mq) use ($user) {
                    $mq->where('users.id', $user->id);
                })->orWhere('created_by', $user->id);
            });
        }
        
        $projects = $projectQuery->get();
        $users = User::orderBy('name')->get();

        $data = array_merge([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'dataUrl' => route($this->dataUrl),
            'dataTableId' => $this->dataTableId,
            'storeUrl' => route($this->storeUrl),
            'projects' => $projects,
            'users' => $users,
        ], $stats);

        return view($this->indexView, $data);
    }

    /**
     * Get paginated catatans for DataTable.
     */
    public function getAllPaginated(Request $request)
    {
        $params = $request->validate([
            'search' => 'nullable|string',
            'category' => 'nullable|string',
            'project_id' => 'nullable|string',
            'priority' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $catatans = $this->catatanService->getPaginatedCatatans(
                $params['search'] ?? null,
                $params['category'] ?? null,
                $params['project_id'] ?? null,
                $params['priority'] ?? null,
                $params['row_per_page']
            );

            return ResponseHelper::success(
                'Data catatan berhasil diambil',
                PaginateResource::make($catatans, CatatanResource::class)
            );
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created catatan.
     */
    public function store(StoreCatatanRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Cek akses ke project jika diisi
            if (!empty($data['project_id'])) {
                $this->authorize('create', [\App\Models\Catatan::class, $data['project_id']]);
            }

            if (!isset($data['user_id'])) {
                $data['user_id'] = auth()->id();
            }
            $catatan = $this->catatanService->storeCatatan($data);
            return ResponseHelper::success('Catatan berhasil ditambahkan', CatatanResource::make($catatan));
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Get single catatan detail.
     */
    public function show(string $id)
    {
        try {
            $catatan = $this->catatanService->getCatatanById($id);
            $this->authorize('view', $catatan);

            return ResponseHelper::success('Detail catatan berhasil diambil', CatatanResource::make($catatan));
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified catatan.
     */
    public function update(UpdateCatatanRequest $request, string $id)
    {
        try {
            $catatan = \App\Models\Catatan::findOrFail($id);
            $this->authorize('update', $catatan);

            $data = $request->validated();
            if (empty($data['user_id'])) {
                unset($data['user_id']);
            }
            $catatan = $this->catatanService->updateCatatan($id, $data);
            return ResponseHelper::success('Catatan berhasil diperbarui', CatatanResource::make($catatan));
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified catatan.
     */
    public function destroy(string $id)
    {
        try {
            $catatan = $this->catatanService->getCatatanById($id);
            $this->authorize('delete', $catatan);

            $this->catatanService->deleteCatatan($id);
            return ResponseHelper::success('Catatan berhasil dihapus');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Securely serve media attachments from private storage.
     */
    public function showMedia($mediaId)
    {
        try {
            $id = \App\Helpers\MediaHasher::decode($mediaId) ?? (is_numeric($mediaId) ? (int)$mediaId : null);
            if (!$id) {
                try {
                    $id = \Illuminate\Support\Facades\Crypt::decryptString($mediaId);
                } catch (\Exception $e) {
                    abort(404);
                }
            }

            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);

            if (!auth()->check()) {
                abort(403);
            }

            $path = $media->getPath();

            if (!file_exists($path)) {
                abort(404);
            }

            if (str_starts_with($media->mime_type, 'image/') || $media->mime_type === 'application/pdf') {
                return response()->file($path, [
                    'Content-Type' => $media->mime_type,
                    'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
                ]);
            }

            return response()->download($path, $media->file_name);
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
