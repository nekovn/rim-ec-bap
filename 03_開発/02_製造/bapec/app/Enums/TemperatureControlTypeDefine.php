<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 温度管理区分
 *
 * @access public
 * @packege Enums
 */
final class TemperatureControlTypeDefine extends Enum implements DefineInterface
{
    /** 通常 */
    const NORMAL = '1';
    /** 冷蔵 */
    const REFRIGERATE = '2';
    /** 冷凍 */
    const FROZEN = '3';
    /** 通常（冷蔵可） */
    const NORMAL_REF = '4';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'NORMAL'      => self::NORMAL,
            'REFRIGERATE' => self::REFRIGERATE,
            'FROZEN'      => self::FROZEN,
            'NORMAL_REF'  => self::NORMAL_REF
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
