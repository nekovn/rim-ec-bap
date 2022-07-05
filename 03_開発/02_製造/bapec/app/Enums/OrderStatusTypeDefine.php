<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 受注ステータス.コード定義
 */
final class OrderStatusTypeDefine extends Enum implements DefineInterface
{
    /** 決済待ち */
    const WAITING = 8;
    /** 保留 */
    const HOLD = 9;
    /** 受付 */
    const RECEPTION = 10;
    /** 出荷依頼済 */
    const REQUESTED = 11;
    /** 出荷済み */
    const SHIPPED = 20;
    /** キャンセル */
    const CANCEL = 31;
    /** 返品 */
    const RETURN = 32;
    
    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return self::getConstants();
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return void
     */
    public static function getMethods(): array
    {
        return [];
    }
}
