<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 販売ステータス.コード定義
 */
final class SaleStatusDefine extends Enum implements DefineInterface
{
    /** 販売中 */
    const SALE = '1';
    /** 準備中 */
    const PREPARATION = '2';
    /** 販売停止 */
    const STOP_SELLING = '3';
    /** 販売終了 */
    const END_OF_SALE = '4';
    /** 廃番 */
    const DISCONTINUED = '9';
    
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
