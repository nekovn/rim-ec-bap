<?php
namespace App\Helpers\Util;

/**
 * 文字列ヘルパークラス
 *
 * @category  システム共通
 * @package   App\Helpers\Util
 * @version   1.0
 */
class StringHelper
{
    /**
     * 文字列のパラメータ部にバインドパラメータを設定する。
     *
     * @param string $source　パラメータ適用文字列
     * @param array $parameters　バインドパラメータ
     * @return バインドパラメータを適用した文字列
     */
    public static function bindParameter(string $source, array $parameters = []): string
    {
        $string = $source;
        if (empty($parameters)) {
            return $string;
        }
        foreach ($parameters as $key => $value) {
            $string = str_replace(":{$key}", $value, $string);
        }

        return $string;
    }
}
