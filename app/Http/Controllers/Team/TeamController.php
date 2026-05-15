<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Resources\Team\TeamResource;
use App\Http\Resources\RoleManagement\UserResource;
use App\Interface\Team\TeamServiceInterface;
use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private TeamServiceInterface $teamService;

    public function __construct(TeamServiceInterface $teamService)
    {
        $this->teamService = $teamService;
        $this->authorizeResource(\App\Models\Team::class, 'team');
    }

    public function index()
    {
        $data = $this->teamService->getIndexData();
        return view('pages.team.index', array_merge($data, [
            'title' => 'Manajemen Tim',
            'subtitle' => 'Kelola grup kerja dan anggota tim Anda.',
        ]));
    }

    public function getData(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Team::class);
        try {
            $search = $request->get('search');
            $rowPerPage = $request->get('rowPerPage', 10);

            $teams = $this->teamService->getPaginatedTeams($search, $rowPerPage);

            return ResponseHelper::success('Berhasil mengambil data tim.', TeamResource::collection($teams)->response()->getData(true));
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal mengambil data tim: ' . $e->getMessage(), 500);
        }
    }

    public function store(StoreTeamRequest $request)
    {
        try {
            $team = $this->teamService->storeTeam($request->validated());
            return ResponseHelper::jsonResponse(true, 'Tim berhasil dibuat.', new TeamResource($team), 201);
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal membuat tim: ' . $e->getMessage(), 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $team = $this->teamService->getTeamDetail($id);
            return ResponseHelper::success('Data tim ditemukan.', new TeamResource($team));
        } catch (\Exception $e) {
            return ResponseHelper::error('Data tim tidak ditemukan.', 404);
        }
    }

    public function update(UpdateTeamRequest $request, string $id)
    {
        try {
            $team = $this->teamService->updateTeam($id, $request->validated());
            return ResponseHelper::success('Tim berhasil diperbarui.', new TeamResource($team));
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal memperbarui tim: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->teamService->deleteTeam($id);
            return ResponseHelper::success('Tim berhasil dihapus.');
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal menghapus tim: ' . $e->getMessage(), 500);
        }
    }

    public function getUsers()
    {
        $this->authorize('viewAny', \App\Models\Team::class);
        try {
            $users = User::role('anggota')->get();
            return ResponseHelper::success('Berhasil mengambil data anggota.', UserResource::collection($users));
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal mengambil data anggota.', 500);
        }
    }
}
