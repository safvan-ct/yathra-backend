<?php
namespace App\Repositories\Interfaces;

interface StaffRepositoryInterface
{
    public function findByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
    public function getById(int $id);
    public function getByIdWithRelations(int $id);
    public function getForDataTable(?int $roleId = null);
    public function toggleStatus(int $id, string $column): bool;
    public function delete(int $id);
    public function syncRoles(int $staffId, array $roleIds);
}
