<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminIpWhitelist;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
	    web: __DIR__.'/../routes/web.php',
	    api: __DIR__.'/../routes/api.php',
	    commands: __DIR__.'/../routes/console.php',
	    health: '/up',
	)

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'payment/callback/cashify',
            'payment/callback/tokopay',
        ]);

        $middleware->alias([
            'identity.verified' => \App\Http\Middleware\EnsureIdentityVerified::class,
            'admin.ip' => AdminIpWhitelist::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();