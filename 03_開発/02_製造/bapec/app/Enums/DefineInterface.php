<?php

namespace App\Enums;

/**
 * 定数クラスのインターフェイス
 */
interface DefineInterface
{
    /**
     * クライアントに返すKey-Valueの定数情報を取得する。
     *
     * @return array
     */
    public static function getKeyValues(): array;
    /**
     * クライアントに返す定数固有のメソッドを取得する。
     *
     * @return array
     */
    public static function getMethods(): array;
}
