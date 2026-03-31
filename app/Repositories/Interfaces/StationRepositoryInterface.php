<?php
namespace App\Repositories\Interfaces;

interface StationRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15, bool $withInfo = false);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}
