<?php

namespace App\Interface;

interface BaseRepositoryInterface
{
    public function getById(string $id);
    public function findById(string $id, array $relations = []);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
}
