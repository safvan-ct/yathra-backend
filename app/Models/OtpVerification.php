<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['phone', 'otp', 'type', 'is_verified', 'verified_at', 'expires_at'])]
class OtpVerification extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'expires_at'  => 'datetime',
        ];
    }

    /**
     * Check if OTP has expired.
     */
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    /**
     * Verify the OTP.
     */
    public function verify()
    {
        $this->update(['is_verified' => true, 'verified_at' => now()]);
    }
}
