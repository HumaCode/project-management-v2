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
        $projects = Project::orderBy('name')->get();
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
            $data = $request->validated();
            // If user_id is not sent, we keep the existing one (handled by not including it in update)
            // or we can explicitly remove it from the data array if it's null
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
            $this->catatanService->deleteCatatan($id);
            return ResponseHelper::success('Catatan berhasil dihapus');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
