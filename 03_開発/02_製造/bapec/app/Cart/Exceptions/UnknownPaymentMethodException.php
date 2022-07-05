<?php

namespace App\Cart\Exceptions;

use App\Exceptions\ApplicationException;

/**
 * 未定義の決済方法
 */
class UnknownPaymentMethodException extends ApplicationException
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct(\Lang::get('messages.E.cant.use.payment.method'), 400);
    }
}
