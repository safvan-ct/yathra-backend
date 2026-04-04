<?php
namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function getAllRoles()
    {
        return $this->roleRepository->all();
    }

    public function getForDataTable()
    {
        return $this->roleRepository->getForDataTable();
    }

    public function createRole(array $data)
    {
        $role = $this->roleRepository->create($data);
        if (isset($data['permissions'])) {
            $this->roleRepository->syncPermissions($role->id, $data['permissions']);
        }
        return $role;
    }

    public function updateRole(int $id, array $data)
    {
        $role = $this->roleRepository->update($id, $data);
        if ($role && isset($data['permissions'])) {
            $this->roleRepository->syncPermissions($id, $data['permissions']);
        }
        return $role;
    }

    public function deleteRole(int $id)
    {
        return $this->roleRepository->delete($id);
    }

    public function findRoleById(int $id)
    {
        return $this->roleRepository->findById($id);
    }
}
