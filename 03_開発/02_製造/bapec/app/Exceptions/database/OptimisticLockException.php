<?php

namespace App\Exceptions\database;

use App\Exceptions\ApplicationException;
use Lang;

/**
 * 排他例外
 *
 * @category  システム共通
 * @package   App\Exceptions\database
 * @version   1.0
 */
class OptimisticLockException extends ApplicationException
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct(Lang::get('messages.E.optimistic.exception'), 400);
    }
}
