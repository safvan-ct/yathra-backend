<?php
namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function all()
    {
        return Permission::all();
    }

    public function getForDataTable()
    {
        return Permission::query();
    }

    public function create(array $data)
    {
        return Permission::create($data);
    }

    public function update(int $id, array $data)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->update($data);
            return $permission;
        }
        return null;
    }

    public function delete(int $id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            return $permission->delete();
        }
        return false;
    }

    public function findById(int $id)
    {
        return Permission::find($id);
    }
}
