<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 税区分
 *
 * @access public
 * @packege Enums
 */
final class TaxTypeDefine extends Enum implements DefineInterface
{
    /** 内税 */
    const INCLUSIVE = '1';
    /** 外税 */
    const EXCLUSIVE  = '2';
    /** 非課税 */
    const EXEMPT = '3';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'INCLUSIVE' => self::INCLUSIVE,
            'EXCLUSIVE' => self::EXCLUSIVE,
            'EXEMPT'    => self::EXEMPT
        ];
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return array
     */
    public static function getMethods(): array
    {
        return [];
    }
}
