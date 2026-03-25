<?php

use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\DistrictController;
use App\Http\Controllers\Api\V1\StaffAuthController;
use App\Http\Controllers\Api\V1\StateController;
use App\Http\Controllers\Api\V1\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // User Authentication Routes (Mobile - Phone + PIN)
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserAuthController::class, 'register']);
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::post('/request-pin-reset-otp', [UserAuthController::class, 'requestPinResetOtp']);
        Route::post('/reset-pin', [UserAuthController::class, 'resetPin']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserAuthController::class, 'logout']);
            Route::get('/me', [UserAuthController::class, 'getCurrentUser']);
        });
    });

    // Staff Authentication Routes (Email-based)
    Route::prefix('staff')->group(function () {
        Route::post('/register', [StaffAuthController::class, 'register']);
        Route::post('/login', [StaffAuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [StaffAuthController::class, 'logout']);
            Route::get('/me', [StaffAuthController::class, 'getCurrentStaff']);
        });
    });

    // Location Routes
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::apiResource('states', StateController::class);
        Route::apiResource('districts', DistrictController::class);
        Route::apiResource('cities', CityController::class);
    });
});
