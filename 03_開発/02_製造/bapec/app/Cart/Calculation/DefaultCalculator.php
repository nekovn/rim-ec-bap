<?php

namespace App\Cart\Calculation;

use App\Cart\CartItem;
use App\Cart\Contracts\Calculator;

class DefaultCalculator implements Calculator
{
    public static function getAttribute(string $attribute, CartItem $cartItem)
    {
/*
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);
        
        $cartAttribute = \Cart::cartAttribute();

        switch ($attribute) {
            case 'unitPrice':
                // オリジナルの単価
                return $cartItem->model->unit_price;

            case 'salePrice':
                // 販売単価（税抜き）割引済み
                return $cartItem->model->getSalePrice();

            case 'salePriceTax':
                // 販売単価（税抜き）に対する税
                return $cartItem->model->getSalePriceTax();

            case 'salePriceTaxIncluded':
                // 販売単価（税込）
                return $cartItem->model->getSalePriceTaxIncluded();

            case 'subtotal':
                // 小計（税抜）
                $salePrice = $cartItem->model->getSalePrice();
                $amount = bcmul($salePrice, $cartItem->qty);
                return \Price::rounding($amount, $cartAttribute->getTaxRoundingType());

            case 'tax':
                // 税
                $taxType = $cartItem->model->tax_type;
                if (TaxTypeDefine::EXEMPT != $taxType) {
                    // 非課税以外は外税で計算
                    $taxType = TaxTypeDefine::EXCLUSIVE;
                }
                return \Price::tax($cartItem->subtotal, $cartItem->model->tax->tax_rate, $taxType, $cartAttribute->getTaxRoundingType());

            case 'subtotalTaxIncluded':
                // 小計（税込）
                $amount = bcadd($cartItem->subtotal, $cartItem->tax);
                return \Price::rounding($amount, $cartAttribute->getTaxRoundingType());


            case 'discount':
                // 単体・税抜き・割引額
                return $cartItem->price * ($cartItem->getDiscountRate() / 100);
            case 'tax':
                // 単体・税
                return round($cartItem->priceTarget * ($cartItem->taxRate / 100), $decimals);
            case 'priceTax':
                // 単体・税込み
                return round($cartItem->priceTarget + $cartItem->tax, $decimals);
            case 'discountTotal':
                // 全数・税抜き・割引額
                return round($cartItem->discount * $cartItem->qty, $decimals);
            case 'priceTotal':
                // 全数・税抜き・単価合計
                return round($cartItem->price * $cartItem->qty, $decimals);
            case 'subtotal':
                // 全数・税抜き・割引後単価合計
                return max(round($cartItem->priceTotal - $cartItem->discountTotal, $decimals), 0);
            case 'priceTarget':
                // 単数・税抜き・割引後単価
                return round(($cartItem->priceTotal - $cartItem->discountTotal) / $cartItem->qty, $decimals);
            case 'taxTotal':
                // 全数・税抜き・割引後単価合計・税
                return round($cartItem->subtotal * ($cartItem->taxRate / 100), $decimals);
            case 'total':
                // 割引後合計
                return round($cartItem->subtotal + $cartItem->taxTotal, $decimals);
            default:
                return;
        }
*/
    }
}
