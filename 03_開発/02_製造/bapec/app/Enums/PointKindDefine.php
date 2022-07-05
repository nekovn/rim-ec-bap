<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * ポイント種類
 *
 * @access public
 * @packege Enums
 */
final class PointKindDefine extends Enum implements DefineInterface
{
    /** 店舗共通ポイント */
    const COMMON= '1';
    /** EC専用ポイント */
    const EC = '2';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'COMMON' => self::COMMON,
            'EC'     => self::EC
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
