<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use App\Providers\EloquentUserExProvider;
use App\Providers\EloquentMemberProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //カスタムProvider
        Auth::provider('eloquentUserExUser', function($app, array $config) {
            return new EloquentUserExUserProvider($app['hash'], $config['model']);
        });
        Auth::provider('eloquentUserExMember', function ($app, array $config) {
            return new EloquentUserExMemberProvider($app['hash'], $config['model']);
        });
    }
}
