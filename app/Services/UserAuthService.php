<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserAuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new user.
     */
    public function register(string $phone, string $pin): User
    {
        return $this->userRepository->create(['phone' => $phone, 'pin' => Hash::make($pin)]);
    }

    /**
     * Login a user.
     */
    public function login(string $phone, string $pin): ?string
    {
        $user = $this->userRepository->findByPhone($phone);

        if (! $user) {
            return null;
        }

        if (! Hash::check($pin, $user->pin)) {
            return null;
        }

        // Reset login attempts
        $this->userRepository->update($user->id, ['login_attempts' => 0]);

        // Create API token
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Logout a user by revoking all tokens.
     */
    public function logout(User $user): bool
    {
        $user->tokens()->delete();
        return true;
    }

    /**
     * Reset PIN for a user.
     */
    public function resetPin(string $phone, string $newPin): ?User
    {
        $user = $this->userRepository->findByPhone($phone);

        if (! $user) {
            return null;
        }

        return $this->userRepository->update($user->id, ['pin' => Hash::make($newPin)]);
    }

    /**
     * Get user by phone.
     */
    public function getByPhone(string $phone): ?User
    {
        return $this->userRepository->findByPhone($phone);
    }

    /**
     * Update login attempts.
     */
    public function incrementLoginAttempts(User $user): void
    {
        $this->userRepository->update($user->id, ['login_attempts' => $user->login_attempts + 1, 'last_login_attempt' => now()]);
    }
}
