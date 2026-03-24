<?php
namespace App\Services;

use App\Models\OtpVerification;

class OtpService
{
    /**
     * Generate and send OTP for PIN reset.
     */
    public function generateOtpForPinReset(string $phone): OtpVerification
    {
        // Invalidate previous OTPs
        OtpVerification::where('phone', $phone)
            ->where('type', 'pin_reset')
            ->where('is_verified', false)
            ->delete();

        $otp = rand(100000, 999999);

        return OtpVerification::create([
            'phone'      => $phone,
            'otp'        => $otp,
            'type'       => 'pin_reset',
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Verify OTP.
     */
    public function verifyOtp(string $phone, string $otp, string $type = 'pin_reset'): bool
    {
        $verification = OtpVerification::where('phone', $phone)
            ->where('otp', $otp)
            ->where('type', $type)
            ->where('is_verified', false)
            ->first();

        if (! $verification) {
            return false;
        }

        if ($verification->isExpired()) {
            return false;
        }

        $verification->verify();
        return true;
    }

    /**
     * Get unverified OTP.
     */
    public function getUnverifiedOtp(string $phone, string $type = 'pin_reset'): ?OtpVerification
    {
        return OtpVerification::where('phone', $phone)
            ->where('type', $type)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();
    }
}
