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

        // CSRF exception untuk Midtrans webhook
        $middleware->validateCsrfTokens(except: [
            'payment/notification',
            'midtrans/callback',
        ]);

        $middleware->alias([
            'otp'       => \App\Http\Middleware\CheckOtp::class,
            'isAdmin'   => \App\Http\Middleware\IsAdmin::class,
            'isPetugas' => \App\Http\Middleware\IsPetugas::class,
            'isUser'    => \App\Http\Middleware\IsUser::class,
            'isVendor'  => \App\Http\Middleware\IsVendor::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();