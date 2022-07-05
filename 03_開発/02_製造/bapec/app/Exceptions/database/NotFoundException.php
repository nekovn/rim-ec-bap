<?php

namespace App\Exceptions\database;

use App\Exceptions\ApplicationException;
use Lang;

/**
 * 該当データなし例外
 *
 * @category  システム共通
 * @package   App\Exceptions\database
 * @version   1.0
 */
class NotFoundException extends ApplicationException
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct(Lang::get('messages.E.targetdata.notfound'), 400);
    }
}
