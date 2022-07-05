<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * ソート順定義
 *
 * @category  システム共通
 * @package   App\HEnums
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */
final class SortOrderDefine extends Enum implements DefineInterface
{
    /** 昇順 */
    const ASC  = 'asc';
    /** 降順 */
    const DESC = 'desc';
    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'ASC'  => self::ASC,
            'DESC' => self::DESC
        ];
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return void
     */
    public static function getMethods(): array
    {
        return [];
    }
}
