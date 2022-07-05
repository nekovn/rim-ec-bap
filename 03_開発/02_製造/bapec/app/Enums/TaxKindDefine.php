<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 税種類
 *
 * @access public
 * @packege Enums
 */
final class TaxKindDefine extends Enum implements DefineInterface
{
    /** 免税 */
    const FREE = '1';
    /** 非課税 */
    const EXEMPT = '2';
    /** 通常税率 */
    const NORMAL = '3';
    /** 軽減税率 */
    const REDUCED = '4';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'FREE'      => self::FREE,
            'EXEMPT'    => self::EXEMPT,
            'NORMAL'    => self::NORMAL,
            'REDUCED'   => self::REDUCED
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
