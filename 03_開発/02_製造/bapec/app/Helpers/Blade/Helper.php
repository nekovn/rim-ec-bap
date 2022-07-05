<?php
namespace App\Helpers\Blade;

/**
 * Blade向けヘルパークラス
 *
 * @category  システム共通
 * @package   App\Helpers\Blade\Helper
 * @version   1.0
 */

class Helper
{
    public static function assetEx($resource)
    {
        return asset(sprintf('%s?%s', $resource, filemtime($_SERVER['DOCUMENT_ROOT']."/".$resource)));
    }

    /**
     * 郵便番号をハイフン区切りに変換
     *
     * @param string $zip 郵便番号
     * @param bool $withSymbol 郵便マークを付与するか
     * @return string ハイフン区切りの郵便番号
     */
    public static function zipFormat($zip, $withSymbol = false)
    {
        // ハイフンを取り除く
        $zipcode = str_replace("-", "", $zip);
        // ハイフンありのフォーマットに変換
        $zipcode = substr($zipcode ,0,3) . "-" . substr($zipcode ,3);

        if ($withSymbol) {
            return '〒' . $zipcode;
        }
        return $zipcode;
    }
    
    /**
     * 郵便番号をハイフン区切りに変換
     *
     * @param string $zip 郵便番号
     * @return string ハイフン区切りの郵便番号
     */
    public static function zipFormatWithSymbol($zip)
    {
        return self::zipFormat($zip, true);
    }

    /**
     * 金額フォーマット
     *
     * @param float $val 金額
     * @param bool $withSymbol 円マークを付与するか
     * @return string フォーマットされた金額
     */
    public static function price($val, $withSymbol = false)
    {
        $ret = \Price::format($val);
        if ($withSymbol) {
            return '￥' . $ret;
        }
        return $ret;
    }

    /**
     * 金額フォーマット（円マーク付き）
     *
     * @param float $val
     * @return string フォーマットされた円マーク付き金額
     */
    public static function priceWithSymbol($val)
    {
        return self::price($val, true);
    }
}
