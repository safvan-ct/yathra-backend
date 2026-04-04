<?php
namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function all();
    public function getForDataTable();
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findById(int $id);
    public function syncPermissions(int $roleId, array $permissionIds);
}
