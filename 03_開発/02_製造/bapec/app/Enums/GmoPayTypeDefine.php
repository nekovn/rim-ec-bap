<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * GMO決済方法 
 *
 * @access public
 * @packege Enums
 */
final class GmoPayTypeDefine extends Enum implements DefineInterface
{
    /** クレジットカード */
    const CREDIT  = '0';
    /** キャリア決済（au） */
    const AU = '8';
    /** キャリア決済（docomo） */
    const DOCOMO = '9';
    /** キャリア決済（softbank） */
    const SOFTBANK = '11';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'CREDIT'            => self::CREDIT,
            'AU'                => self::AU,
            'DOCOMO'            => self::DOCOMO,
            'SOFTBANK'          => self::SOFTBANK
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
