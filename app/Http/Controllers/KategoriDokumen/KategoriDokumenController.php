<?php

namespace App\Http\Controllers\KategoriDokumen;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriDokumen\StoreKategoriDokumenRequest;
use App\Http\Requests\KategoriDokumen\UpdateKategoriDokumenRequest;
use App\Http\Resources\KategoriDokumen\KategoriDokumenResource;
use App\Interface\KategoriDokumen\KategoriDokumenServiceInterface;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;

class KategoriDokumenController extends Controller
{
    private KategoriDokumenServiceInterface $kategoriService;

    public function __construct(KategoriDokumenServiceInterface $kategoriService)
    {
        $this->kategoriService = $kategoriService;
    }

    public function index()
    {
        $stats = $this->kategoriService->getKategoriStats();
        return view('pages.kategori-dokumen.index', [
            'title' => 'Kategori Dokumen',
            'subtitle' => 'Kelola kategori untuk pengorganisasian dokumen proyek.',
            'stats' => $stats,
        ]);
    }

    public function getData(Request $request)
    {
        try {
            $search = $request->get('search');
            $rowPerPage = $request->get('rowPerPage', 10);

            $kategoris = $this->kategoriService->getPaginatedKategoris($search, $rowPerPage);
            $stats = $this->kategoriService->getKategoriStats();

            return ResponseHelper::success('Berhasil mengambil data kategori.', [
                'list' => KategoriDokumenResource::collection($kategoris)->response()->getData(true),
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal mengambil data kategori: ' . $e->getMessage(), 500);
        }
    }

    public function store(StoreKategoriDokumenRequest $request)
    {
        try {
            $kategori = $this->kategoriService->createKategori($request->validated());
            return ResponseHelper::jsonResponse(true, 'Kategori berhasil dibuat.', new KategoriDokumenResource($kategori), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal membuat kategori: ' . $e->getMessage(), 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $kategori = $this->kategoriService->getKategoriById($id);
            return ResponseHelper::success('Data kategori ditemukan.', new KategoriDokumenResource($kategori));
        } catch (\Exception $e) {
            return ResponseHelper::error('Data kategori tidak ditemukan.', 404);
        }
    }

    public function update(UpdateKategoriDokumenRequest $request, string $id)
    {
        try {
            $kategori = $this->kategoriService->updateKategori($id, $request->validated());
            return ResponseHelper::success('Kategori berhasil diperbarui.', new KategoriDokumenResource($kategori));
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal memperbarui kategori: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->kategoriService->deleteKategori($id);
            return ResponseHelper::success('Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal menghapus kategori: ' . $e->getMessage(), 500);
        }
    }
}
