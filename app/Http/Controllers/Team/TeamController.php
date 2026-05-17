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
use App\Models\Team;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeamController extends Controller implements HasMiddleware
{
    private TeamServiceInterface $teamService;

    public function __construct(TeamServiceInterface $teamService)
    {
        $this->teamService = $teamService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:viewAny,' . \App\Models\Team::class, only: ['index', 'getData']),
            new Middleware('can:create,' . \App\Models\Team::class, only: ['store']),
            new Middleware('can:update,team', only: ['update', 'edit']),
            new Middleware('can:delete,team', only: ['destroy']),
        ];
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
            $stats = $this->teamService->getIndexData();

            return ResponseHelper::success('Berhasil mengambil data tim.', 
                TeamResource::collection($teams)
                     ->additional(['stats' => $stats])
                     ->response()
                     ->getData(true)
            );
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

    public function edit(Team $team)
    {
        try {
            $teamDetail = $this->teamService->getTeamDetail($team->id);
            return ResponseHelper::success('Data tim ditemukan.', new TeamResource($teamDetail));
        } catch (\Exception $e) {
            return ResponseHelper::error('Data tim tidak ditemukan.', 404);
        }
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        try {
            $updatedTeam = $this->teamService->updateTeam($team->id, $request->validated());
            return ResponseHelper::success('Tim berhasil diperbarui.', new TeamResource($updatedTeam));
        } catch (\Exception $e) {
            return ResponseHelper::error('Gagal memperbarui tim: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(Team $team)
    {
        try {
            $this->teamService->deleteTeam($team->id);
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
