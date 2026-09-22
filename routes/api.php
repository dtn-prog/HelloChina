<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Core\Auth\Http\Controllers\AuthController;
use App\Core\User\Http\Controllers\UserController;
use App\Core\Setting\Http\Controllers\SettingController;
use App\Core\Audit\Http\Controllers\AuditController;
use App\Core\Media\Http\Controllers\MediaController;

// ====================================
// Public API Routes
// ====================================
Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');

// ====================================
// Authenticated API Routes
// ====================================
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll'])->name('api.auth.logout-all');
    Route::get('/auth/profile', [AuthController::class, 'profile'])->name('api.auth.profile');
    Route::post('/auth/change-password', [AuthController::class, 'changePassword'])->name('api.auth.change-password');

    // Users
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);
    Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('api.users.bulk-action');
    Route::get('/users/export', [UserController::class, 'export'])->name('api.users.export');

    // Settings
    Route::get('/settings', [SettingController::class, 'getPublicSettings'])->name('api.settings.public');

    // Media
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('api.media.upload');
    Route::get('/media/search', [MediaController::class, 'search'])->name('api.media.search');
    Route::get('/media/picker', [MediaController::class, 'picker'])->name('api.media.picker');
});
