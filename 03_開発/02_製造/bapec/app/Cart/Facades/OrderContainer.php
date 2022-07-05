<?php

namespace App\Cart\Facades;

use Illuminate\Support\Facades\Facade;

class OrderContainer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'orderContainer';
    }
}
