<?php

namespace App\Cart\Exceptions;

use App\Exceptions\ApplicationException;

/**
 * 決済可能な金額範囲オーバー
 */
class UnusableRangePaymentException extends ApplicationException
{
}
