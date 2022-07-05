<?php
namespace App\Helpers\Util;

use Lang;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

/**
 * 配列系ヘルパークラス
 *
 * @category  システム共通
 * @package   App\Helpers\Util
 * @version   1.0
 */
class ArrayHelper
{
    /**
     * ネストした連想配列のマージを行う。
     *
     * @param array $target マージ先
     * @param array $source マージ対象
     * @return array マージ結果
     */
    public static function array_merge_for_nest($target, $source): array
    {
        return self::array_merge($target, $source);
    }
    /**
     * ネストした連想配列のマージメイン（再起呼出し）
     *
     * @param array $target マージ先
     * @param array $source マージ対象
     * @return array マージ結果
     */
    private static function array_merge($target, $source): array
    {
        foreach ($source as $key => $value) {
            if (is_array($source[$key]) && isset($target[$key])) {
                $target[$key] = self::array_merge($target[$key], $source[$key]);
                continue;
            }
            $target[$key] = $source[$key];
        }
        return $target;
    }
}
