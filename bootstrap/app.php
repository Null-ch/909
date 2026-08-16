<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'admin.guard' => \App\Http\Middleware\UseAdminGuard::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin') || $request->is('admin/*')
            ? route('admin.login')
            : route('login'));

        $middleware->redirectUsersTo(fn (Request $request) => $request->is('admin/login')
            ? route('admin.dashboard')
            : route('account.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
