<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Formヘルパーを拡張するプロバイダークラス
 *
 */
class FormMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        require base_path() . '/resources/macros/Button.php';
        require base_path() . '/resources/macros/Calendar.php';
        require base_path() . '/resources/macros/BasicControl.php';
        require base_path() . '/resources/macros/DateFlatpicker.php';
        require base_path() . '/resources/macros/FormEx.php';
        require base_path() . '/resources/macros/Grid.php';
    }
}
