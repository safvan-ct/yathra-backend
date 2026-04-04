<?php
namespace App\Repositories\Interfaces;

interface PermissionRepositoryInterface
{
    public function all();
    public function getForDataTable();
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findById(int $id);
}
