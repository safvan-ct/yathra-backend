<?php
namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function all()
    {
        return Role::all();
    }

    public function getForDataTable()
    {
        return Role::query()->withCount('permissions');
    }

    public function create(array $data)
    {
        return Role::create($data);
    }

    public function update(int $id, array $data)
    {
        $role = Role::find($id);
        if ($role) {
            $role->update($data);
            return $role;
        }
        return null;
    }

    public function delete(int $id)
    {
        $role = Role::find($id);
        if ($role) {
            return $role->delete();
        }
        return false;
    }

    public function findById(int $id)
    {
        return Role::with('permissions')->find($id);
    }

    public function syncPermissions(int $roleId, array $permissionIds)
    {
        $role = Role::find($roleId);
        if ($role) {
            return $role->permissions()->sync($permissionIds);
        }
        return false;
    }
}
