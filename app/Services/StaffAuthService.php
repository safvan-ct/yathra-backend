<?php
namespace App\Services;

use App\Models\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffAuthService
{
    public function __construct(
        protected StaffRepositoryInterface $staffRepository
    ) {}

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

    public function sessionLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::guard('staff')->attempt($credentials, $remember);
    }

    public function logout(Staff $staff): bool
    {
        $staff->tokens()->delete();
        return true;
    }

    public function sessionLogout(): void
    {
        Auth::guard('staff')->logout();
    }

    public function getByEmail(string $email): ?Staff
    {
        return $this->staffRepository->findByEmail($email);
    }

    public function getByIdWithRelations(int $id): ?Staff
    {
        return $this->staffRepository->getByIdWithRelations($id);
    }

    public function incrementLoginAttempts(Staff $staff): void
    {
        $this->staffRepository->update($staff->id, ['login_attempts' => $staff->login_attempts + 1, 'last_login_attempt' => now()]);
    }
}
