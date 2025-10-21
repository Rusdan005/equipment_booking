<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ✅ ลงทะเบียน middleware 'role'
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // ถ้ามี middleware อื่นๆ เพิ่มได้ตรงนี้เช่น
        // 'admin' => \App\Http\Middleware\AdminMiddleware::class,
        // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    // 👇 โหลด service providers ของแอปทั้งหมด
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
    ])
    ->create();
