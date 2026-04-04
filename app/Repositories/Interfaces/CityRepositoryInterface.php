<?php
namespace App\Repositories\Interfaces;

interface CityRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15);
    public function find(int $id);
    public function create(array $data);
    public function bulkCreate(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
