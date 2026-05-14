<?php

namespace App\Http\Controllers\Dokumen;

use App\Constants\Dokumen\DokumenMessages;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dokumen\DokumenPaginationRequest;
use App\Http\Resources\Dokumen\DokumenResource;
use App\Http\Resources\PaginateResource;
use App\Interface\Dokumen\DokumenServiceInterface;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DokumenController extends Controller
{
    private DokumenServiceInterface $dokumenService;
    private \App\Interface\KategoriDokumen\KategoriDokumenServiceInterface $kategoriService;

    public function __construct(
        DokumenServiceInterface $dokumenService,
        \App\Interface\KategoriDokumen\KategoriDokumenServiceInterface $kategoriService
    ) {
        $this->dokumenService = $dokumenService;
        $this->kategoriService = $kategoriService;
    }

    /**
     * Tampilkan halaman utama manajemen dokumen.
     */
    public function index(): View
    {
        $data = [
            'title'       => DokumenMessages::TITLE,
            'subtitle'    => DokumenMessages::SUBTITLE,
            'icon'        => DokumenMessages::ICON,
            'dataUrl'     => route('dokumen.pagination'),
            'dataTableId' => 'table-dokumen',
            'projects'    => Project::all(),
            'users'       => User::select('id', 'name')->get(),
            'categories'  => $this->kategoriService->getAllKategoris(),
        ];

        $data = array_merge($data, $this->dokumenService->getDokumenStatistics());
        
        return view('pages.dokumen.index', $data);
    }

    /**
     * Ambil data dokumen yang terpaginasi via AJAX.
     */
    public function getAllPaginated(DokumenPaginationRequest $request): JsonResponse
    {
        try {
            $dokumens = $this->dokumenService->getPaginatedDokumens(
                $request->search,
                $request->kategori,
                $request->project_id,
                $request->row_per_page,
                $request->type,
            );

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::RETRIEVED_SUCCESS,
                PaginateResource::make($dokumens, DokumenResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Simpan dokumen baru ke database.
     */
    public function store(\App\Http\Requests\Dokumen\DokumenStoreRequest $request): JsonResponse
    {
        try {
            $dokumen = $this->dokumenService->createDokumen($request->validated());

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::CREATED_SUCCESS,
                new DokumenResource($dokumen),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Ambil data dokumen tunggal.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $dokumen = $this->dokumenService->getDokumenById($id);

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::RETRIEVED_SUCCESS,
                new DokumenResource($dokumen),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Perbarui data dokumen.
     */
    public function update(\App\Http\Requests\Dokumen\DokumenUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $dokumen = $this->dokumenService->updateDokumen($id, $request->validated());

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::UPDATED_SUCCESS,
                new DokumenResource($dokumen),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    public function builder(string $id): View
    {
        $dokumen = \App\Models\Dokumen::with(['items', 'project', 'uploader'])->findOrFail($id);

        $data = [
            'title'    => DokumenMessages::BUILDER_TITLE,
            'subtitle' => DokumenMessages::BUILDER_SUBTITLE,
            'icon'     => 'bi bi-magic',
            'dokumen'  => $dokumen,
        ];

        return view('pages.dokumen.builder', $data);
    }

    /**
     * Simpan konten dari Document Builder.
     */
    public function saveBuilder(\Illuminate\Http\Request $request, string $id): JsonResponse
    {
        try {
            $this->dokumenService->saveDokumenItems($id, $request->items ?? []);

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::UPDATED_SUCCESS,
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Hapus data dokumen dari database.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->dokumenService->deleteDokumen($id);

            return ResponseHelper::jsonResponse(
                true,
                DokumenMessages::DELETED_SUCCESS,
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
