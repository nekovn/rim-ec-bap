<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 決済コード
 *
 * @access public
 * @packege Enums
 */
final class PaymentMethodDefine extends Enum implements DefineInterface
{
    /** 請求無し */
    const NO_CHARGE = '9';
    /** 代引き */
    const CASH_ON_DELIVERY = '13';
    /** クレジットカード */
    const CREDIT  = '14';
    /** キャリア決済（docomo） */
    const DOCOMO = '17';
    /** キャリア決済（au） */
    const AU = '18';
    /** キャリア決済（softbank） */
    const SOFTBANK = '19';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'NO_CHARGE'         => self::NO_CHARGE,
            'CASH_ON_DELIVERY'  => self::CASH_ON_DELIVERY,
            'CREDIT'            => self::CREDIT,
            'DOCOMO'            => self::DOCOMO,
            'AU'                => self::AU,
            'SOFTBANK'          => self::SOFTBANK
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
