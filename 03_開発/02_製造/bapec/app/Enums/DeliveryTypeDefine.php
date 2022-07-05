<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 配送先区分
 *
 * @access public
 * @packege Enums
 */
final class DeliveryTypeDefine extends Enum implements DefineInterface
{
    /** 注文者宛 */
    const OWN= '1';
    /** 直接指定 */
    const EDIT = '2';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'OWN'  => self::OWN,
            'EDIT' => self::EDIT
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
