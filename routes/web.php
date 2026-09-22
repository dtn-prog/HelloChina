<?php

use Illuminate\Support\Facades\Route;
use App\Core\Dashboard\Http\Controllers\DashboardController;
use App\Core\Auth\Http\Controllers\AuthController;
use App\Core\User\Http\Controllers\UserController;
use App\Core\Role\Http\Controllers\RoleController;
use App\Core\Setting\Http\Controllers\SettingController;
use App\Core\Audit\Http\Controllers\AuditController;
use App\Core\Media\Http\Controllers\MediaController;
use App\Core\Developer\Http\Controllers\ApiKeyController;
use App\Core\Developer\Http\Controllers\WebhookController;
use App\Core\Developer\Http\Controllers\QueueMonitorController;
use App\Core\Developer\Http\Controllers\CronMonitorController;
use App\Core\Monitoring\Http\Controllers\ErrorLogController;
use App\Core\Monitoring\Http\Controllers\HealthCheckController;
use App\Core\Language\Http\Controllers\LanguageController;

// ====================================
// Language Switcher (Public)
// ====================================
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');
Route::get('/lang', [LanguageController::class, 'current'])->name('lang.current');

// ====================================
// Public Routes (Guest)
// ====================================
Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
Route::post('/signin', [AuthController::class, 'login'])->name('login.post');
Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ====================================
// Authenticated Routes
// ====================================
Route::middleware(['auth', 'admin', 'maintenance'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/widgets', [DashboardController::class, 'availableWidgets'])->name('dashboard.widgets');
    Route::post('/dashboard/widgets', [DashboardController::class, 'saveWidgets'])->name('dashboard.widgets.save');
    Route::get('/dashboard/widgets/{widgetId}/refresh', [DashboardController::class, 'refreshWidget'])->name('dashboard.widgets.refresh');

    // Profile & Auth
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout-all', [AuthController::class, 'logoutAll'])->name('logout.all');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/2fa/enable', [AuthController::class, 'enable2FA'])->name('2fa.enable');
    Route::post('/2fa/confirm', [AuthController::class, 'confirm2FA'])->name('2fa.confirm');
    Route::post('/2fa/disable', [AuthController::class, 'disable2FA'])->name('2fa.disable');
    Route::get('/sessions', [AuthController::class, 'sessions'])->name('sessions');
    Route::delete('/sessions/{sessionId}', [AuthController::class, 'revokeSession'])->name('sessions.revoke');

    // ====================================
    // User Management
    // ====================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
    });

    // ====================================
    // Role & Permission Management
    // ====================================
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::get('/permissions', [RoleController::class, 'permissions'])->name('permissions');
        Route::get('/{role}', [RoleController::class, 'show'])->name('show');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
    });

    // ====================================
    // Settings
    // ====================================
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update')->middleware('permission:settings.update');
    });

    // ====================================
    // Audit Logs
    // ====================================
    Route::prefix('audit')->name('audit.')->middleware('permission:audit_logs.view')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('index');
        Route::get('/export', [AuditController::class, 'export'])->name('export')->middleware('permission:audit_logs.export');
        Route::get('/user/{userId}', [AuditController::class, 'userHistory'])->name('user-history');
        Route::get('/{activity}', [AuditController::class, 'show'])->name('show');
    });

    // ====================================
    // Media Library
    // ====================================
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::get('/search', [MediaController::class, 'search'])->name('search');
        Route::get('/picker', [MediaController::class, 'picker'])->name('picker');
        Route::post('/folders', [MediaController::class, 'storeFolder'])->name('folders.store');
        Route::delete('/folders/{folder}', [MediaController::class, 'destroyFolder'])->name('folders.destroy');
        Route::get('/{media}', [MediaController::class, 'show'])->name('show');
        Route::put('/{media}', [MediaController::class, 'update'])->name('update');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
    });

    // ====================================
    // Products (Example CRUD Module)
    // ====================================
    Route::resource('products', \App\Core\Product\Http\Controllers\ProductController::class)
        ->middleware('permission:products.view');
    Route::post('/products/bulk-action', [\App\Core\Product\Http\Controllers\ProductController::class, 'bulkAction'])
        ->name('products.bulk-action');
    Route::get('/products/export', [\App\Core\Product\Http\Controllers\ProductController::class, 'export'])
        ->name('products.export')
        ->middleware('permission:products.export');

    // ====================================
    // Developer Tools
    // ====================================
    Route::prefix('developer')->name('developer.')->middleware('role:super_admin,developer')->group(function () {
        // API Keys
        Route::get('/api-keys', [ApiKeyController::class, 'index'])->name('api-keys.index');
        Route::post('/api-keys', [ApiKeyController::class, 'store'])->name('api-keys.store');
        Route::get('/api-keys/{apiKey}', [ApiKeyController::class, 'show'])->name('api-keys.show');
        Route::delete('/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
        Route::post('/api-keys/{apiKey}/regenerate', [ApiKeyController::class, 'regenerate'])->name('api-keys.regenerate');

        // Webhooks
        Route::get('/webhooks', [WebhookController::class, 'index'])->name('webhooks.index');
        Route::post('/webhooks', [WebhookController::class, 'store'])->name('webhooks.store');
        Route::get('/webhooks/{webhook}', [WebhookController::class, 'show'])->name('webhooks.show');
        Route::put('/webhooks/{webhook}', [WebhookController::class, 'update'])->name('webhooks.update');
        Route::delete('/webhooks/{webhook}', [WebhookController::class, 'destroy'])->name('webhooks.destroy');
        Route::post('/webhooks/{webhook}/test', [WebhookController::class, 'test'])->name('webhooks.test');
        Route::get('/webhooks/{webhook}/deliveries', [WebhookController::class, 'deliveries'])->name('webhooks.deliveries');

        // Queue Monitor
        Route::get('/queue', [QueueMonitorController::class, 'index'])->name('queue.index');
        Route::post('/queue/{jobId}/retry', [QueueMonitorController::class, 'retryJob'])->name('queue.retry');
        Route::delete('/queue/{jobId}', [QueueMonitorController::class, 'deleteJob'])->name('queue.delete');
        Route::post('/queue/retry-all', [QueueMonitorController::class, 'retryAll'])->name('queue.retry-all');
        Route::post('/queue/clear-all', [QueueMonitorController::class, 'clearAll'])->name('queue.clear-all');

        // Cron Monitor
        Route::get('/cron', [CronMonitorController::class, 'index'])->name('cron.index');
        Route::post('/cron/{command}/run', [CronMonitorController::class, 'runEvent'])->name('cron.run');
    });

    // ====================================
    // Monitoring
    // ====================================
    Route::prefix('monitoring')->name('monitoring.')->middleware('role:super_admin,admin,developer')->group(function () {
        // Health Check
        Route::get('/health', [HealthCheckController::class, 'index'])->name('health.index');
        Route::post('/health/check', [HealthCheckController::class, 'check'])->name('health.check');

        // Error Logs
        Route::get('/errors', [ErrorLogController::class, 'index'])->name('errors.index');
        Route::get('/errors/{errorLog}', [ErrorLogController::class, 'show'])->name('errors.show');
        Route::delete('/errors/{errorLog}', [ErrorLogController::class, 'destroy'])->name('errors.destroy');
        Route::post('/errors/clear', [ErrorLogController::class, 'clearAll'])->name('errors.clear');
    });

    // ====================================
    // UI Elements (Demo)
    // ====================================
    Route::get('/calendar', fn () => view('pages.calender', ['title' => 'Calendar']))->name('calendar');
    Route::get('/form-elements', fn () => view('pages.form.form-elements', ['title' => 'Form Elements']))->name('form-elements');
    Route::get('/basic-tables', fn () => view('pages.tables.basic-tables', ['title' => 'Basic Tables']))->name('basic-tables');
    Route::get('/blank', fn () => view('pages.blank', ['title' => 'Blank']))->name('blank');
    Route::get('/error-404', fn () => view('pages.errors.error-404', ['title' => 'Error 404']))->name('error-404');
    Route::get('/line-chart', fn () => view('pages.chart.line-chart', ['title' => 'Line Chart']))->name('line-chart');
    Route::get('/bar-chart', fn () => view('pages.chart.bar-chart', ['title' => 'Bar Chart']))->name('bar-chart');
    Route::get('/alerts', fn () => view('pages.ui-elements.alerts', ['title' => 'Alerts']))->name('alerts');
    Route::get('/avatars', fn () => view('pages.ui-elements.avatars', ['title' => 'Avatars']))->name('avatars');
    Route::get('/badge', fn () => view('pages.ui-elements.badges', ['title' => 'Badges']))->name('badges');
    Route::get('/buttons', fn () => view('pages.ui-elements.buttons', ['title' => 'Buttons']))->name('buttons');
    Route::get('/image', fn () => view('pages.ui-elements.images', ['title' => 'Images']))->name('images');
    Route::get('/videos', fn () => view('pages.ui-elements.videos', ['title' => 'Videos']))->name('videos');
});
