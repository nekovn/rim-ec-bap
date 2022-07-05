<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 性別
 *
 * @access public
 * @packege Enums
 */
final class GenderDefine extends Enum implements DefineInterface
{
    /** 男性 */
    const MAN= '1';
    /** 女性 */
    const WOMAN = '2';
    /** その他 */
    const OTHER = '3';
    /** 無回答 */
    const NOANSWER = '9';
    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'MAN'  => self::MAN,
            'WOMAN' => self::WOMAN,
            'OTHER' => self::OTHER,
            'NOANSWER' => self::NOANSWER
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
