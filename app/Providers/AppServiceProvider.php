<?php

namespace App\Providers;

use App\Services\Exchange\Nobitex\NobitexService;
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
        $this->app->bind('ExchangeService',function (){
            return new NobitexService();
        });
    }
}
