<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 端数処理区分
 *
 * @access public
 * @packege Enums
 */
final class RoundingTypeDefine extends Enum implements DefineInterface
{
    /** 四捨五入 */
    const HALFUP = '1';
    /** 切り上げ */
    const UP  = '2';
    /** 切り捨て */
    const DOWN = '3';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'HALFUP'    => self::HALFUP,
            'UP'        => self::UP,
            'DOWN'      => self::DOWN
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
