<?php

namespace App\Cart\Calculation;

use App\Enums\TaxTypeDefine;
use App\Enums\RoundingTypeDefine;

/**
 * 金額計算ユーティリティ
 */
class PriceUtils 
{
    /**
     * 税込み金額
     *
     * @param $amount 金額
     * @param $tax_rate 税率
     * @param $tax_type 税区分
     * @param $roundingType 端数処理区分
     * @return float
     */
    public static function includingTax($amount, $tax_rate, $tax_type, $roundingType)
    {
        if (!$amount) {
            return 0;
        }

        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        if (TaxTypeDefine::EXCLUSIVE === $tax_type) {
            // 外税
            $tax = self::tax($amount, $tax_rate, $tax_type, $roundingType);
            return bcadd($amount, $tax);
        } else if (TaxTypeDefine::INCLUSIVE === $tax_type) {
            // 内税
            return $amount;
        } else if (TaxTypeDefine::EXEMPT === $tax_type) {
            // 非課税
            return $amount;
        }
    }
    /**
     * 税抜き金額
     *
     * @param $amount 金額
     * @param $tax_rate 税率
     * @param $tax_type 税区分
     * @param $roundingType 端数処理区分
     * @return float
     */
    public static function excludingTax($amount, $tax_rate, $tax_type, $roundingType)
    {
        if (!$amount) {
            return 0;
        }

        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        $rate = bcdiv($tax_rate, 100);

        if (TaxTypeDefine::EXCLUSIVE === $tax_type) {
            // 外税
            return $amount;
        } else if (TaxTypeDefine::INCLUSIVE === $tax_type) {
            // 内税
            // return bcdiv($amount, bcadd(1, $rate));
            $tax = self::tax($amount, $tax_rate, $tax_type, $roundingType);
            return bcsub($amount, $tax);
        } else if (TaxTypeDefine::EXEMPT === $tax_type) {
            // 非課税
            return $amount;
        }
    }

    /**
     * 税額
     *
     * @param $amount 金額
     * @param $tax_rate 税率
     * @param $tax_type 税区分
     * @param $roundingType 端数処理区分
     * @return float
     */
    public static function tax($amount, $tax_rate, $tax_type, $roundingType)
    {
        if (!$amount) {
            return 0;
        }

        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        $rate = bcdiv($tax_rate, 100);
        $tax = 0;
        if (TaxTypeDefine::EXCLUSIVE === $tax_type) {
            // 外税
            $tax = bcmul($amount, $rate);
        } else if (TaxTypeDefine::INCLUSIVE === $tax_type) {
            // 内税
            $without = bcdiv($amount, bcadd(1, $rate));
            $tax = bcsub($amount, $without);
        } else if (TaxTypeDefine::EXEMPT === $tax_type) {
            // 非課税
            $tax = 0;
        }

        // 端数処理を行って返す
        return self::rounding($tax, $roundingType);
    }

    /**
     * 金額に対する割引額を取得
     * （税抜き）
     *
     * @param float $amount 対象金額
     * @param float $discount_rate 割引率
     * @param string $roundingType端数処理区分
     * @return void
     */
    public static function discount($amount, $discount_rate, $roundingType)
    {
        if (!$amount) {
            return 0;
        }
        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        $amt = bcdiv(bcmul($amount, $discount_rate), 100);

        // 端数処理を行って返す
        return self::rounding($amt, $roundingType);
    }

    /**
     * 端数処理を行う
     *
     * @param float $amount 金額
     * @param string $roundingType 端数処理区分
     * @return 端数処理を行った金額
     */
    public static function rounding($amount, $roundingType)
    {
        if (RoundingTypeDefine::HALFUP === $roundingType) {
            // 四捨五入
            return round($amount);
        } else if (RoundingTypeDefine::UP === $roundingType) {
            // 切り上げ
            return ceil($amount);
        } else if (RoundingTypeDefine::DOWN === $roundingType) {
            // 切り捨て
            return floor($amount);
        } else {
            // その他の場合、四捨五入とする
            return round($amount);
        }
    }

    /**
     * 表示用金額フォーマット
     *
     * @param float $amount 金額
     * @return string フォーマットされた金額
     */
    public static function format($amount)
    {
        $decimals_output = config('cart.format.decimals_output', 0);
        return number_format($amount, $decimals_output, '.', ',');
    }

    /**
     * 引数の値を全て加算する
     *
     * @param float ...$data 加算対象
     * @return float 加算結果
     */
    public function sum(float ...$data)
    {
        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        $ret = 0;
        $count = count($data);
    	for ($i=0; $i<$count; $i++) {
            $ret = bcadd($ret, $data[$i]);
        }

        return (float)$ret;
    }

    /**
     * targetから引数の値を全て減算する
     *
     * @param float $target 元の数
     * @param float ...$data 減算対象
     * @return float 減算結果
     */
    public function sub(float $target, float ...$data)
    {
        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        $ret = $target;
        $count = count($data);
    	for ($i=0; $i<$count; $i++) {
            $ret = bcsub($ret, $data[$i]);
        }

        return (float)$ret;
    }
}
