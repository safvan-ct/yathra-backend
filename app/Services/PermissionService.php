<?php
namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionService
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {}

    public function getAllPermissions()
    {
        return $this->permissionRepository->all();
    }

    public function getForDataTable()
    {
        return $this->permissionRepository->getForDataTable();
    }

    public function createPermission(array $data)
    {
        return $this->permissionRepository->create($data);
    }

    public function updatePermission(int $id, array $data)
    {
        return $this->permissionRepository->update($id, $data);
    }

    public function deletePermission(int $id)
    {
        return $this->permissionRepository->delete($id);
    }

    public function findPermissionById(int $id)
    {
        return $this->permissionRepository->findById($id);
    }
}
