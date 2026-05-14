<?php

namespace App\Interface\Team;

interface TeamServiceInterface
{
    public function getIndexData(): array;
    public function getPaginatedTeams(?string $search, int $rowPerPage);
    public function storeTeam(array $data);
    public function updateTeam(string $id, array $data);
    public function deleteTeam(string $id);
    public function getTeamDetail(string $id);
}
