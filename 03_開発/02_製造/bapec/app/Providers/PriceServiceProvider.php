<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PriceServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('price', \App\Cart\Calculation\PriceUtils::class);
    }
}
