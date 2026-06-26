<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Abono;
use App\Observers\AbonoObserver;

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
        Abono::observe(AbonoObserver::class);
       
    }

    protected $policies = [
        \App\Models\Cotizacion::class => \App\Policies\CotizacionPolicy::class,
    ];
}
