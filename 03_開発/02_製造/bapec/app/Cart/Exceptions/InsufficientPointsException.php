<?php

namespace App\Cart\Exceptions;

use App\Exceptions\ApplicationException;

/**
 * ポイント不足
 */
class InsufficientPointsException extends ApplicationException
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct(\Lang::get('messages.E.insufficient.points'), 400);
    }
}
