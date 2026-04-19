<?php

use App\Http\Middleware\ApiPerformanceLogger;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            \App\Http\Middleware\ApiPerformanceLogger::class,
        ]);

        $middleware->alias([
            'role'                => \App\Http\Middleware\CheckRole::class,
            'verified_freelancer' => \App\Http\Middleware\EnsureFreelancerIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
