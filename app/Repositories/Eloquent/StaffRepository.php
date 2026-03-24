<?php
namespace App\Repositories\Eloquent;

use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    /**
     * Find staff by email.
     */
    public function findByEmail(string $email)
    {
        return Staff::where('email', $email)->first();
    }

    /**
     * Create a new staff member.
     */
    public function create(array $data)
    {
        return Staff::create($data);
    }

    /**
     * Update staff.
     */
    public function update(int $id, array $data)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->update($data);
            return $staff;
        }
        return null;
    }

    /**
     * Get staff by ID.
     */
    public function getById(int $id)
    {
        return Staff::find($id);
    }

    /**
     * Get staff by ID with relations.
     */
    public function getByIdWithRelations(int $id)
    {
        return Staff::with(['roles.permissions'])->find($id);
    }
}
