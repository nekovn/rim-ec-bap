<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * コード値マスタ.value定義
 *
 */
final class CodeValueDefine extends Enum implements DefineInterface
{
    /** 受注ステータス */
    const ORDER_STATUS_CHANGEABLE_ATTR = 'attr1'; //SystemHelperのキーで設定
    const ORDER_STATUS_CHANGEABLE_ATTR2 = 'attr2'; //SystemHelperのキーで設定

    /** ステータススタイル */
    const STATUS_STYLE_ATTR = 'attr5'; //SystemHelperのキーで設定

    /** 注文キャンセルメール設定 */
    const ORDER_CANCEL_MAIL_SETTING_ATTR1 = 'attr1'; //SystemHelperのキーで設定
    const ORDER_CANCEL_MAIL_SETTING_ATTR2 = 'attr2'; //SystemHelperのキーで設定

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
