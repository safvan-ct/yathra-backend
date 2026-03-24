<?php
namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    /**
     * Find user by phone.
     */
    public function findByPhone(string $phone);

    /**
     * Create a new user.
     */
    public function create(array $data);

    /**
     * Update user.
     */
    public function update(int $id, array $data);

    /**
     * Get user by ID.
     */
    public function getById(int $id);
}
