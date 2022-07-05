<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * 受注ステータス
 *
 * @access public
 * @packege Enums
 */
final class StatusDefine extends Enum implements DefineInterface
{
    /** 受付 */
    const UKETSUKE = 10;
    /** 保留 */
    const HORYU = 9;
    /** 決済待ち */
    const KESSAI_MACHI = 8;
    /** キャンセル */
    const CANCEL = 31;
    /** 出荷済み */
    const SHUKKA_ZUMI = 20;
    /** 返品 */
    const HENPIN = 32;
    /** 出荷依頼 */
    const SHUKKA_IRAI = 11;

    //--- 出荷ステータス-------------
    /** 出荷待ち */
    const SHUKKA_MACHI = 2;
    /** 出荷済み */
    const SHUKKA_SUMI = 1;
    /** 一部出荷 */
    const SHUKKA_ICHIBU = 3;
    /** 出荷連携 */
    const SHUKKA_RENKEI = 4;
    /** 返品 */
    const SHUKKA_HENPIN = 5;
    /** キャンセル */
    const SHUKKA_CANCEL = 6;

    //--- 公開状況ステータス-------------
    /** 非公開 */
    const KOKAI_OFF = 0;
    /** 公開 */
    const KOKAI_ON = 1;

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
