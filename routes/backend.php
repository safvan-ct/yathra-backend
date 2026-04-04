<?php

use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\StaffController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('login', [LoginController::class, 'create'])->name('login');

Route::prefix('backend')->name('backend.')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::get('/backend/dashboard', [DashboardController::class, 'index'])->middleware(['auth:staff'])->name('backend.dashboard');

Route::prefix('backend')->name('backend.')->middleware(['auth:staff'])->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [ProfileController::class, 'password'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permission Routes
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/datatable', [PermissionController::class, 'datatable'])->name('datatable');
        Route::get('/form/{id?}', [PermissionController::class, 'form'])->name('form');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    });

    // Role Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/datatable', [RoleController::class, 'datatable'])->name('datatable');
        Route::get('/form/{id?}', [RoleController::class, 'form'])->name('form');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // Staff Routes
    Route::prefix('staffs')->name('staffs.')->group(function () {
        Route::get('/', [StaffController::class, 'index'])->name('index');
        Route::get('/datatable', [StaffController::class, 'datatable'])->name('datatable');
        Route::get('/form/{id?}', [StaffController::class, 'form'])->name('form');
        Route::post('/', [StaffController::class, 'store'])->name('store');
        Route::put('/{id}', [StaffController::class, 'update'])->name('update');
        Route::patch('/toggle-status/{id}', [StaffController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [StaffController::class, 'destroy'])->name('destroy');
    });

    // Location Routes
    foreach (['states', 'districts', 'cities', 'stations'] as $type) {
        $controller = "App\\Http\\Controllers\\Backend\\" . ucfirst(Str::singular($type)) . "Controller";
        Route::prefix($type)->name($type . '.')->group(function () use ($controller) {
            Route::get('/', [$controller, 'index'])->name('index');
            Route::get('/datatable', [$controller, 'datatable'])->name('datatable');
            Route::get('/form/{id?}', [$controller, 'form'])->name('form');
            Route::post('/', [$controller, 'store'])->name('store');
            Route::put('/{id}', [$controller, 'update'])->name('update');
            Route::patch('/toggle-status/{id}', [$controller, 'toggleStatus'])->name('toggle-status');
            Route::delete('/{id}', [$controller, 'destroy'])->name('destroy');

            // Import Routes
            Route::post('/import', [$controller, 'import'])->name('import');
            Route::post('/import-preview', [$controller, 'importPreview'])->name('import.preview');
            Route::post('/import-commit', [$controller, 'importCommit'])->name('import.commit');
        });
    }
});
