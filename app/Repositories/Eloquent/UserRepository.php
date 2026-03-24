<?php
namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Find user by phone.
     */
    public function findByPhone(string $phone)
    {
        return User::where('phone', $phone)->first();
    }

    /**
     * Create a new user.
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Update user.
     */
    public function update(int $id, array $data)
    {
        $user = User::find($id);

        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    /**
     * Get user by ID.
     */
    public function getById(int $id)
    {
        return User::find($id);
    }
}
