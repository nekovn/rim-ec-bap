<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 決済コード
 *
 * @access public
 * @packege Enums
 */
final class PaymentStatusDefine extends Enum implements DefineInterface
{
    /** 決済完了 */
    const COMPLETED = '1';
    /** 決済待ち */
    const SETTLEMENT_WAITING = '2';
    /** 決済手続中 */
    const PROCESS = '3';
    /** 入金待ち */
    const PAYMENT_WAITING = '4';
    /** 決済失敗 */
    const FAILURE = '5';
    /** 期限切れ */
    const EXPIRED = '6';
    /** キャンセル */
    const CANCEL = '7';
    /** 返品 */
    const RETURN = '8';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'COMPLETED' => self::COMPLETED,
            'SETTLEMENT_WAITING' => self::SETTLEMENT_WAITING,
            'PROCESS' => self::PROCESS,
            'PAYMENT_WAITING' => self::PAYMENT_WAITING,
            'FAILURE' => self::FAILURE,
            'EXPIRED' => self::EXPIRED,            
            'CANCEL' => self::CANCEL,
            'RETURN' => self::RETURN
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
