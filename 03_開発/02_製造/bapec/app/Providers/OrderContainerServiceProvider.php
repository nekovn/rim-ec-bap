<?php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Session\SessionManager;
use Illuminate\Support\ServiceProvider;

class OrderContainerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('orderContainer', \App\Cart\OrderContainer::class);

        $this->app['events']->listen(Logout::class, function () {
            $this->app->make(SessionManager::class)->forget('order');
        });
    }
}
