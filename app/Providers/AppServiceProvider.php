<?php

namespace App\Providers;

use App\View\Composers\FrontLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer([
            'layouts.app',
            'front.*',
            'front.partials.header',
            'front.partials.menu',
            'front.partials.footer',
        ], FrontLayoutComposer::class);
    }
}
