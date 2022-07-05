<?php

namespace App\Cart\Calculation;

use App\Enums\TaxTypeDefine;

/**
 * カート料金計算
 */
class CartPriceCalculator 
{
    private $content;
    
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * 計算を行う。
     *
     * @return void
     */
    public function calculate()
    {
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        // カート属性
        $cartAttribute = \Cart::cartAttribute();

        foreach ($this->content as $cartItem)
        {
            // 商品model
            $goods = $cartItem->model;
            $tax = $goods->tax;

            $taxType = $goods->tax_type;
            if (TaxTypeDefine::EXEMPT != $taxType) {
                // 非課税以外は外税で計算
                $taxType = TaxTypeDefine::EXCLUSIVE;
            }

            // 販売単価（税抜き）
            $salePrice = $goods->getSalePrice();

            // 販売単価（税抜き）に対する税
            $salePriceTax = \Price::tax($salePrice, $tax->tax_rate, $taxType, $cartAttribute->getTaxRoundingType());

            // 販売単価（税込み）
            $salePriceTaxIncluded = (float)bcadd($salePrice, $salePriceTax);

            // 小計（税抜き）
            $amount = bcmul($salePrice, $cartItem->qty);
            $subtotal = \Price::rounding($amount, $cartAttribute->getTaxRoundingType());

            // 小計の税
            $tax =bcmul($salePriceTax, $cartItem->qty);

            // 小計（税込み）
            $subtotalTaxIncluded = bcadd($subtotal, $tax);

            $cartItem->salePrice = $salePrice;
            $cartItem->salePriceTax = $salePriceTax;
            $cartItem->salePriceTaxIncluded = $salePriceTaxIncluded;
            $cartItem->subtotal = $subtotal;
            $cartItem->tax = $tax;
            $cartItem->subtotalTaxIncluded = $subtotalTaxIncluded;
        }

        return $this->content;
    }
}
