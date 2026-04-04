<?php
namespace App\Services;

use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class StaffService
{
    public function __construct(
        protected StaffRepositoryInterface $staffRepository
    ) {}

    public function getForDataTable(?int $roleId = null)
    {
        return $this->staffRepository->getForDataTable($roleId);
    }

    public function toggleStatus(int $id, string $column)
    {
        return $this->staffRepository->toggleStatus($id, $column);
    }

    public function createStaff(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $staff = $this->staffRepository->create($data);

        if ($staff && isset($data['roles'])) {
            $this->staffRepository->syncRoles($staff->id, $data['roles']);
        }

        return $staff;
    }

    public function updateStaff(int $id, array $data)
    {
        if (isset($data['password']) && ! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $staff = $this->staffRepository->update($id, $data);

        if ($staff && isset($data['roles'])) {
            $this->staffRepository->syncRoles($id, $data['roles']);
        }

        return $staff;
    }

    public function deleteStaff(int $id)
    {
        return $this->staffRepository->delete($id);
    }

    public function findStaffById(int $id)
    {
        return $this->staffRepository->getByIdWithRelations($id);
    }
}
