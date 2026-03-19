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
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
        ]);

        // Exclude cookie affiliate dari enkripsi agar JS bisa membacanya
        $middleware->encryptCookies(except: [
            'affiliate_ref_code',
        ]);

        // Exclude /wa-click dari CSRF — endpoint publik
        $middleware->validateCsrfTokens(except: [
            'wa-click',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
