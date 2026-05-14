<?php

namespace App\Interface\Team;

interface TeamRepositoryInterface
{
    public function getAll(?string $search, int $rowPerPage);
    public function findById(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
    public function countTeams(): int;
    public function countDistinctMembers(): int;
    public function all();
}
