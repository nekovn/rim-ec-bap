<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * フラグ定義
 *
 * @access public
 * @packege Enums
 */
final class FlagDefine extends Enum implements DefineInterface
{
    const ON  = '1';
    const OFF = '0';

    /**
     * クライアントに返すKey-Valueの定数情報を取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'ON'  => self::ON,
            'OFF' => self::OFF
        ];
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return array
     */
    public static function getMethods(): array
    {
        $on  = self::ON;
        $off = self::OFF;
        return [
            // ここにはjavascriptの評価を記述する
            'isOn'  => "function(value) { return value == {$on} }",
            'isoff' => "function(value) { return value != {$off} }"
        ];
    }
}
