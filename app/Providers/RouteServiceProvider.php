<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     */
    public const HOME = '/';

    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Define your route model bindings, pattern filters, and routes.
     */
    public function boot(): void
    {
        // โหลด routes/web.php
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // โหลด routes/api.php (ถ้ามี)
        if (file_exists(base_path('routes/api.php'))) {
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        }
    }
}
