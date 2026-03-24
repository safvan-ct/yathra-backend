<?php
namespace App\Repositories\Interfaces;

interface StaffRepositoryInterface
{
    /**
     * Find staff by email.
     */
    public function findByEmail(string $email);

    /**
     * Create a new staff member.
     */
    public function create(array $data);

    /**
     * Update staff.
     */
    public function update(int $id, array $data);

    /**
     * Get staff by ID with roles and permissions.
     */
    public function getById(int $id);

    /**
     * Get staff by ID with relations.
     */
    public function getByIdWithRelations(int $id);
}
