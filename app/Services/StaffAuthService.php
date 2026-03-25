<?php
namespace App\Services;

use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class StaffAuthService
{
    public function __construct(
        protected StaffRepositoryInterface $staffRepository
    ) {}

    /**
     * Register a new staff member.
     */
    public function register(string $name, string $email, string $password, int $roleId): Staff
    {
        $staff = $this->staffRepository->create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
        ]);

        // Attach role to staff
        $staff->roles()->attach($roleId);

        return $staff;
    }

    /**
     * Login a staff member.
     */
    public function login(string $email, string $password): ?string
    {
        $staff = $this->staffRepository->findByEmail($email);

        if (! $staff) {
            return null;
        }

        if ($staff->login_attempts >= 5 && $staff->last_login_attempt && $staff->last_login_attempt->diffInMinutes(now()) < 15) {
            return null;
        }

        if (! Hash::check($password, $staff->password)) {
            return null;
        }

        // Reset login attempts
        $this->staffRepository->update($staff->id, ['login_attempts' => 0]);

        // Create API token
        return $staff->createToken('auth_token')->plainTextToken;
    }

    /**
     * Logout a staff member by revoking all tokens.
     */
    public function logout(Staff $staff): bool
    {
        $staff->tokens()->delete();
        return true;
    }

    /**
     * Get staff by email.
     */
    public function getByEmail(string $email): ?Staff
    {
        return $this->staffRepository->findByEmail($email);
    }

    /**
     * Get staff by ID with relations.
     */
    public function getByIdWithRelations(int $id): ?Staff
    {
        return $this->staffRepository->getByIdWithRelations($id);
    }

    /**
     * Update login attempts.
     */
    public function incrementLoginAttempts(Staff $staff): void
    {
        $this->staffRepository->update($staff->id, ['login_attempts' => $staff->login_attempts + 1, 'last_login_attempt' => now()]);
    }
}
