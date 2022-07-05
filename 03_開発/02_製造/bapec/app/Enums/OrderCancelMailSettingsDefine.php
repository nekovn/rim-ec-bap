<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 注文キャンセルメール設定
 *
 * @access public
 * @packege Enums
 */
final class OrderCancelMailSettingsDefine extends Enum implements DefineInterface
{
    /** 注文キャンセルメール情報 */
    const ORDER_CANCEL_MAIL_INFO = '1';

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
