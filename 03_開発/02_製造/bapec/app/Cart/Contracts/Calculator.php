<?php

namespace App\Cart\Contracts;

use App\Cart\CartItem;

interface Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem);
}
