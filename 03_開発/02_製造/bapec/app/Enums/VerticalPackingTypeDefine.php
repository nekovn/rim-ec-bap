<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 縦梱包区分
 *
 * @access public
 * @packege Enums
 */
final class VerticalPackingTypeDefine extends Enum implements DefineInterface
{
    /** 不要 */
    const UN_NEEDED = 0;
    /** 要 */
    const NEED = 1;

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return StatusDefine::getConstants();
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return array
     */
    public static function getMethods(): array
    {
        return [
            // ここにはjavascriptの評価を記述する
        ];
    }
}
