<?php

use App\Http\Controllers\Api\V1\Admin\SuggestionController as AdminSuggestionController;
use App\Http\Controllers\Api\V1\BusController;
use App\Http\Controllers\Api\V1\CityController;
use App\Http\Controllers\Api\V1\DistrictController;
use App\Http\Controllers\Api\V1\OperatorController;
use App\Http\Controllers\Api\V1\RewardController;
use App\Http\Controllers\Api\V1\RouteNodeController;
use App\Http\Controllers\Api\V1\StaffAuthController;
use App\Http\Controllers\Api\V1\StateController;
use App\Http\Controllers\Api\V1\StationController;
use App\Http\Controllers\Api\V1\SuggestionController;
use App\Http\Controllers\Api\V1\TransitRouteController;
use App\Http\Controllers\Api\V1\TripController;
use App\Http\Controllers\Api\V1\TrustController;
use App\Http\Controllers\Api\V1\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // GUEST
    Route::apiResource('stations', StationController::class)->only(['index']);
    Route::get('trips/buses', [TripController::class, 'buses']);

    // User Authentication Routes (Mobile - Phone + PIN)
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserAuthController::class, 'register']);
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::post('/request-pin-reset-otp', [UserAuthController::class, 'requestPinResetOtp']);
        Route::post('/verify-otp', [UserAuthController::class, 'verifyOtp']);
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
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('states', StateController::class)->only(['index', 'show']);
        Route::apiResource('districts', DistrictController::class)->only(['index', 'show']);
        Route::apiResource('cities', CityController::class)->only(['index', 'show']);
        Route::apiResource('stations', StationController::class)->only(['show']);

        Route::apiResource('operators', OperatorController::class)->only(['index', 'show']);
        Route::apiResource('buses', BusController::class)->only(['index', 'show']);
        Route::apiResource('routes', TransitRouteController::class)->only(['index', 'show']);
        Route::get('routes/{route}/nodes', [RouteNodeController::class, 'index']);

        Route::get('trips', [TripController::class, 'index']);
        Route::get('trips/active', [TripController::class, 'active']);
        Route::get('trips/today', [TripController::class, 'today']);
        Route::get('trips/day/{dayIndex}', [TripController::class, 'byDay']);
        Route::get('trips/{id}', [TripController::class, 'show']);

        Route::apiResource('suggestions', SuggestionController::class)->only(['index', 'store', 'show', 'destroy']);

        Route::prefix('rewards')->group(function () {
            Route::get('points', [RewardController::class, 'getPoints']);
            Route::get('history', [RewardController::class, 'history']);
            Route::get('leaderboard', [RewardController::class, 'leaderboard']);
        });

        Route::prefix('trust')->group(function () {
            Route::get('profile', [TrustController::class, 'profile']);
            Route::get('leaderboard', [TrustController::class, 'leaderboard']);
        });
    });

    Route::middleware(['auth:sanctum', 'role:admin,staff'])->group(function () {
        Route::apiResource('states', StateController::class)->except(['index', 'show']);
        Route::apiResource('districts', DistrictController::class)->except(['index', 'show']);
        Route::apiResource('cities', CityController::class)->except(['index', 'show']);
        Route::apiResource('stations', StationController::class)->except(['index', 'show']);

        Route::apiResource('operators', OperatorController::class)->except(['index', 'show']);
        Route::apiResource('buses', BusController::class)->except(['index', 'show']);
        Route::apiResource('routes', TransitRouteController::class)->except(['index', 'show']);

        Route::post('routes/{route}/nodes', [RouteNodeController::class, 'store']);
        Route::put('routes/{route}/nodes/{id}', [RouteNodeController::class, 'update']);
        Route::delete('routes/{route}/nodes/{id}', [RouteNodeController::class, 'destroy']);

        Route::apiResource('trips', TripController::class)->only(['store', 'update', 'destroy']);

        Route::get('admin/suggestions', [AdminSuggestionController::class, 'index']);
        Route::get('admin/suggestions/{id}', [AdminSuggestionController::class, 'show']);
        Route::post('admin/suggestions/{id}/review', [AdminSuggestionController::class, 'review']);
    });
});
