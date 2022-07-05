<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * コードマスタ.コード定義
 */
final class CodeDefine extends Enum implements DefineInterface
{
    /** メール設定 */
    const MAIL_SETTING = 100;
    /** 都道府県コード */
    const PREF_CD = 101;
    /** 消費税種類 */
    const TAX_KIND = 102;
    /** 消費税区分 */
    const TAX_TYPE = 103;
    /** 端数処理区分 */
    const ROUNDING_TYPE = 104;
    /** 決済処理区分 */
    const PAYMENT_PROCESS_TYPE = 105;
    /** メール種別 */
    const MAIL_TYPE = 106;
    /** ポイント種類 */
    const POINT_KIND = 201;
    /** ポイント移動区分 */
    const POINT_TRANSFER_TYPE = 202;
    /** ポイント移動理由 */
    const POINT_TRANSFER_REASON = 203;
    /** ポイント有効期限区分 */
    const POINT_EXPIRATION_TYPE = 204;
    /** 販売ステータス */
    const SALE_STATUS = 301;
    /** 在庫管理区分 */
    const STOCK_MANAGEMENT_TYPE = 302;
    /** 温度管理区分 */
    const TEMPERATURE_CONTROL_TYPE = 303;
    /** 取引先種類 */
    const SUPPLIER_KIND = 304;
    /** 性別 */
    const GENDER = 401;
    /** 受注ステータス */
    const ORDER_STATUS = 501;
    /** 決済ステータス */
    const PAYMENT_STATUS = 502;
    /** 決済方法 */
    const PAYMENT_METHOD = 503;
    /** 配送時間帯 */
    const DELIVERY_TIME = 504;
    /** 出荷ステータス */
    const SHIP_STATUS = 505;
    /** 配送先区分 */
    const DELIVERY_TYPE = 506;
    /** 注文キャンセルメール設定 */
    const ORDER_CANCEL_MAIL_SETTING = 508;
    /** メール設定 */
    const MAIL_SETTINGS = 1001;
    /** 配送条件 */
    const SHIPPING_TERMS = 1002;
    /** 納期目安 */
    const ESTIMATED_DELIVERY_DATE = 1003;
    

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
