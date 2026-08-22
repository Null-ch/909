<?php

namespace App\Providers;

use App\Repositories\EloquentProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\View\Composers\FrontLayoutComposer;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer([
            'layouts.app',
            'front.*',
            'front.partials.header',
            'front.partials.menu',
            'front.partials.footer',
        ], FrontLayoutComposer::class);

        Event::listen(function (Login $event) {
            Log::channel('security')->info('Successful login', [
                'user_id' => $event->user->getAuthIdentifier(),
                'guard' => $event->guard,
                'ip' => request()->ip(),
            ]);
        });

        Event::listen(function (Failed $event) {
            Log::channel('security')->warning('Failed login attempt', [
                'guard' => $event->guard,
                'email' => $event->credentials['email'] ?? null,
                'ip' => request()->ip(),
            ]);
        });
    }
}
