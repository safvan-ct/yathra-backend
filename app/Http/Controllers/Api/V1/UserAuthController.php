<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserResetPinRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\UserResource;
use App\Services\ActivityLogService;
use App\Services\OtpService;
use App\Services\UserAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function __construct(
        protected UserAuthService $userAuthService,
        protected OtpService $otpService,
        protected ActivityLogService $activityLogService
    ) {}

    /**
     * Register a new user.
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = $this->userAuthService->register($request->input('phone'), $request->input('pin'));

        return ApiResponse::success(new UserResource($user), 'User registered successfully', 201);
    }

    /**
     * Login a user.
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $this->userAuthService->getByPhone($request->input('phone'));

        if (! $user) {
            return ApiResponse::error('Invalid phone or PIN', null, 401);
        }

        $user->tokens()->delete();

        $token = $this->userAuthService->login($request->input('phone'), $request->input('pin'));

        if (! $token) {
            $this->userAuthService->incrementLoginAttempts($user);
            return ApiResponse::error('Invalid phone or PIN', null, 401);
        }

        return ApiResponse::success(['user' => new UserResource($user), 'token' => $token], 'Login successful');
    }

    /**
     * Logout a user.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->userAuthService->logout($user);

        return ApiResponse::success(null, 'Logout successful');
    }

    /**
     * Request OTP for PIN reset.
     */
    public function requestPinResetOtp(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string|exists:users,phone']);

        $otp = $this->otpService->generateOtpForPinReset($request->input('phone'));

        // In production, send OTP via SMS
        // SmsService::send($request->input('phone'), "Your OTP is: {$otp->otp}");

        return ApiResponse::success(
            [
                'phone'      => $request->input('phone'),
                'otp'        => $otp->otp, // Remove in production
                'expires_at' => $otp->expires_at,
            ],
            'OTP sent successfully'
        );
    }

    /**
     * Verify OTP and reset PIN.
     */
    public function resetPin(UserResetPinRequest $request): JsonResponse
    {
        $phone = $request->input('phone');
        $otp   = $request->input('otp');

        if ($otp && ! $this->otpService->verifyOtp($phone, $otp)) {
            return ApiResponse::error('Invalid or expired OTP', null, 400);
        }

        $user = $this->userAuthService->resetPin($phone, $request->input('new_pin'));

        if (! $user) {
            return ApiResponse::error('User not found', null, 404);
        }

        return ApiResponse::success(new UserResource($user), 'PIN reset successfully');
    }

    /**
     * Get current user.
     */
    public function getCurrentUser(Request $request): JsonResponse
    {
        $user = $request->user();

        return ApiResponse::success(new UserResource($user), 'User retrieved successfully');
    }
}
