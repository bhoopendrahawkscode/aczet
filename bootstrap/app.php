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
        $middleware->redirectTo(
            guests: fn (\Illuminate\Http\Request $request) => $request->is('dealer*') ? route('dealer.login') : '/admin/login',
            users: '/admin/dashboard'
        );
        $middleware->validateCsrfTokens(except: [
            'leads/store',
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocaleAndRegion::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
