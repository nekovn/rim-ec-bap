<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * インポートログステータス定義
 *
 * @access public
 * @packege Enums
 */
final class ImportStatusDefine extends Enum implements DefineInterface
{
    /** 実行待ち */
    const WAIT_EXEC   = '0';
    /** 正常終了 */
    const SUCCESS  = '1';
    /** 異常終了 */
    const ABORT   = '9';
    /** 実行中 */
    const EXECUTING   = '10';
    /** 一部エラー */
    const SOME_ERROR = '11';
    /**  取消済み */
    // const CANCELED    = '20';

    /**
     * Key-Valueのリストを取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array
    {
        return [
            'SUCCESS'  => self::SUCCESS,
            'SOME_ERROR' => self::SOME_ERROR,
            'ABORT' => self::ABORT,
        ];
    }
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return array
     */
    public static function getMethods(): array
    {
        $success  = self::SUCCESS;
        $someError = self::SOME_ERROR;
        $abort = self::ABORT;
        return [
            // ここにはjavascriptの評価を記述する
            'success'  => "function(value) { return value == {$success} }",
            'someError' => "function(value) { return value != {$someError} }",
            'error' => "function(value) { return value != {$abort} }"
        ];
    }
}
