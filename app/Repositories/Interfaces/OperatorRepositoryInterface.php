<?php
namespace App\Repositories\Interfaces;

interface OperatorRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15);
    public function getQuery(array $filters = []);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
