<?php
namespace App\Repositories\Eloquent;

use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return Staff::where('email', $email)->first();
    }

    public function create(array $data)
    {
        return Staff::create($data);
    }

    public function update(int $id, array $data)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->update($data);
            return $staff;
        }
        return null;
    }

    public function getById(int $id)
    {
        return Staff::find($id);
    }

    public function getByIdWithRelations(int $id)
    {
        return Staff::with(['roles.permissions'])->find($id);
    }

    public function getForDataTable(?int $roleId = null)
    {
        $query = Staff::query()->with('roles');

        if ($roleId > 0) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        return $query;
    }

    public function toggleStatus(int $id, string $column): bool
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->$column = ! $staff->$column;
            return $staff->save();
        }
        return false;
    }

    public function delete(int $id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            return $staff->delete();
        }
        return false;
    }

    public function syncRoles(int $staffId, array $roleIds)
    {
        $staff = Staff::find($staffId);
        if ($staff) {
            return $staff->roles()->sync($roleIds);
        }
        return false;
    }
}
