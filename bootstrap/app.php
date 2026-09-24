<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateDigiflazzWebhook;
use App\Http\Middleware\ValidateTripayWebhookSignature;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Kecualikan CSRF untuk webhook payment gateway eksternal
        $middleware->validateCsrfTokens(except: [
            'api/webhook/*',
        ]);

        // Set locale secara otomatis untuk grup web
        $middleware->web(append: [
            SetLocale::class,
        ]);

        // Daftarkan alias middleware admin guard & webhook validation
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'webhook.tripay' => ValidateTripayWebhookSignature::class,
            'webhook.digiflazz' => ValidateDigiflazzWebhook::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
