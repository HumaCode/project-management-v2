<?php

namespace App\Services\Team;

use App\Interface\Team\TeamRepositoryInterface;
use App\Interface\Team\TeamServiceInterface;

class TeamService implements TeamServiceInterface
{
    private TeamRepositoryInterface $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function getIndexData(): array
    {
        return [
            'total_teams' => $this->teamRepository->countTeams(),
            'total_members' => $this->teamRepository->countDistinctMembers(),
        ];
    }

    public function getPaginatedTeams(?string $search, int $rowPerPage)
    {
        return $this->teamRepository->getAll($search, $rowPerPage);
    }

    public function storeTeam(array $data)
    {
        return $this->teamRepository->create($data);
    }

    public function updateTeam(string $id, array $data)
    {
        return $this->teamRepository->update($id, $data);
    }

    public function deleteTeam(string $id)
    {
        return $this->teamRepository->delete($id);
    }

    public function getTeamDetail(string $id)
    {
        return $this->teamRepository->findById($id);
    }

    public function getAllTeams()
    {
        return $this->teamRepository->all();
    }
}
