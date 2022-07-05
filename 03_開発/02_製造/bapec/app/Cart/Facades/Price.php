<?php

namespace App\Cart\Facades;

use Illuminate\Support\Facades\Facade;

class Price extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'price';
    }
}
