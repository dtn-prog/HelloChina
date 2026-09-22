<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin\AdminMiddleware::class,
            'role' => \App\Http\Middleware\Admin\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\Admin\PermissionMiddleware::class,
            'audit' => \App\Http\Middleware\Admin\AuditMiddleware::class,
            'request-id' => \App\Http\Middleware\Admin\RequestIdMiddleware::class,
            'maintenance' => \App\Http\Middleware\Admin\MaintenanceModeMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\Admin\RequestIdMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
