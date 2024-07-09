<?php

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Application;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'Excel' => Excel::class,
            'role'  => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' =>RoleOrPermissionMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();