<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * ポイント移動区分
 *
 * @access public
 * @packege Enums
 */
final class TransferTypeDefine extends Enum implements DefineInterface
{
    /** 注文による獲得 */
    const ORDER_ADD = '1';
    /** 注文による利用 */
    const ORDER_USE = '2';
    /** 仮付与 */
    const TEMPORARY_ADD = '3';
    /** 注文キャンセルによる獲得取り消し */
    const ORDER_CANCEL_ADD = '4';
    /** 注文キャンセルによる利用取り消し */
    const ORDER_CANCEL_USE = '5';
    /** 仮付与取り消し */
    const CANCEL_TEMPORARY_ADD = '6';
    /** 調整（加算） */
    const ADJUSTMENT_PLUS = '7';
    /** 調整（減算） */
    const ADJUSTMENT_MINUS = '8';
    /** 失効 */
    const EXPIRED = '9';

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
