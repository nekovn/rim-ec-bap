<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\CategoryComposer;

class ComposerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //シングルトンに登録する
        $this->app->singleton(CategoryComposer::class);

    }
    public function boot()
    {
        View::composer('member/*', CategoryComposer::class);
    }
}
