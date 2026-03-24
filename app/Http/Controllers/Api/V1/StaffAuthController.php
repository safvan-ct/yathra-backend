<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffLoginRequest;
use App\Http\Requests\StaffRegisterRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\StaffResource;
use App\Models\Role;
use App\Services\ActivityLogService;
use App\Services\StaffAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaffAuthController extends Controller
{
    public function __construct(
        protected StaffAuthService $staffAuthService,
        protected ActivityLogService $activityLogService
    ) {}

    /**
     * Register a new staff member.
     */
    public function register(StaffRegisterRequest $request): JsonResponse
    {
        $role = Role::where('name', $request->input('role'))->first();

        if (! $role) {
            return ApiResponse::error('Role not found', null, 404);
        }

        $staff = $this->staffAuthService->register($request->input('name'), $request->input('email'), $request->input('password'), $role->id);

        $staff = $staff->load('roles');

        return ApiResponse::success(new StaffResource($staff), 'Staff registered successfully', 201);
    }

    /**
     * Login a staff member.
     */
    public function login(StaffLoginRequest $request): JsonResponse
    {
        $staff = $this->staffAuthService->getByEmail($request->input('email'));

        if (! $staff) {
            return ApiResponse::error('Invalid email or password', null, 401);
        }

        $staff->tokens()->delete();

        $token = $this->staffAuthService->login($request->input('email'), $request->input('password'));

        if (! $token) {
            $this->staffAuthService->incrementLoginAttempts($staff);
            return ApiResponse::error('Invalid email or password', null, 401);
        }

        $staff = $staff->load('roles');

        return ApiResponse::success(['staff' => new StaffResource($staff), 'token' => $token], 'Login successful');
    }

    /**
     * Logout a staff member.
     */
    public function logout(Request $request): JsonResponse
    {
        $staff = $request->user('staff');
        $this->staffAuthService->logout($staff);

        return ApiResponse::success(null, 'Logout successful');
    }

    /**
     * Get current staff.
     */
    public function getCurrentStaff(Request $request): JsonResponse
    {
        $staff = $this->staffAuthService->getByIdWithRelations($request->user()->id);

        return ApiResponse::success(new StaffResource($staff), 'Staff retrieved successfully');
    }
}
