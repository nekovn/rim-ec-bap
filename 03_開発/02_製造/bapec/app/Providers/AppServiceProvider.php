<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $service_dirs = ['/Services/*.php','/Services/**/*.php','/Services/**/**/*.php'];
        foreach ($service_dirs as $dir) {
            foreach (glob(app_path().$dir) as $filename) {
                require_once($filename);
            }
        }
        // foreach (glob(app_path() . '/Services/*.php') as $filename) {
        //     require_once($filename);
        // }
        foreach (glob(app_path() . '/Repositories/*.php') as $filename) {
            require_once($filename);
        }
        $this->app->singleton('AppConfigService', function () {
            return new \App\Services\AppConfigService(new \App\Repositories\CodesRepository);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @param UrlGenerator $url
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (app()->isProduction()) {
            $url->forceScheme('https');
        }

        // 商用環境以外だった場合、SQLログを出力する
        if (config('app.debug')) {
            DB::listen(function ($query) {
                $sql = $query->sql;
                for ($i = 0; $i < count($query->bindings); $i++) {
                    $sql = preg_replace("/\?/", "'" . $query->bindings[$i] . "'", $sql, 1);
                }
                Log::channel('sql')->debug($sql);
            });
        }
        
        Schema::defaultStringLength(191);
    }
}
