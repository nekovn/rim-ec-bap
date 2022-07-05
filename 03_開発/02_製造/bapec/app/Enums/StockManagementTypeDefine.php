<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 在庫管理区分
 *
 * @access public
 * @packege Enums
 */
final class StockManagementTypeDefine extends Enum implements DefineInterface
{
    /** 受発注品 */
    const ORDER= '1';
    /** 在庫品 */
    const STOCK = '2';
    /** 販売計画品 */
    const PLAN = '3';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'ORDER'  => self::ORDER,
            'STOCK' => self::STOCK,
            'PLAN' => self::PLAN
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
