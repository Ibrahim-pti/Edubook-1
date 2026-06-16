<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$basePath = realpath(dirname(__DIR__)) ?: dirname(__DIR__);

return Application::configure(basePath: $basePath)
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust Cloudflare (and any reverse proxy) to pass X-Forwarded-Proto: https
        // Without this Laravel ignores the header and generates http:// URLs/redirects
        $middleware->trustProxies(
            at: '*',
            headers: \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO
                   | \Illuminate\Http\Request::HEADER_X_FORWARDED_PREFIX,
        );

        $middleware->redirectGuestsTo(fn () => route('portal.login'));
        $middleware->alias([
            'approved'       => \App\Http\Middleware\ApprovedUser::class,
            'redirect_admin' => \App\Http\Middleware\RedirectAdmin::class,
            'no-cache'       => \App\Http\Middleware\PreventCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
