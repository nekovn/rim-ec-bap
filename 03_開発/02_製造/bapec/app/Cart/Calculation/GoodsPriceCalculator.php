<?php

namespace App\Cart\Calculation;

use App\Enums\TaxTypeDefine;

/**
 * 商品料金計算
 */
class GoodsPriceCalculator 
{
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * 計算を行う。
     *
     * @return void
     */
    public function calculate()
    {
        // 受注明細リスト
        $orderDetails = $this->container->getOrderDetails();

        foreach ($orderDetails as $orderDetail)
        {
            $taxType = $orderDetail->tax_type;
            if (TaxTypeDefine::EXEMPT != $taxType) {
                // 非課税以外は外税で計算
                $taxType = TaxTypeDefine::EXCLUSIVE;
            }

            // 販売単価（税抜き）
            $salePrice = $orderDetail->sale_price;

            // 販売単価（税抜き）に対する税
            $salePriceTax = \Price::tax($salePrice, $orderDetail->tax_rate, $taxType, $orderDetail->tax_rounding_type);

            // 販売単価（税込み）
            $salePriceTaxIncluded = bcadd($salePrice, $salePriceTax);

            // 小計（税抜き）
            $amount = bcmul($salePrice, $orderDetail->quantity);
            $subtotal = \Price::rounding($amount, $orderDetail->tax_rounding_type);

            // 小計の税
            $tax =bcmul($salePriceTax, $orderDetail->quantity);

            // 小計（税込み）
            $subtotalTaxIncluded = bcadd($subtotal, $tax);
            
            $orderDetail->sale_price = $salePrice;
            $orderDetail->sale_price_tax = $salePriceTax;
            $orderDetail->sale_price_tax_included = $salePriceTaxIncluded;
            $orderDetail->discount = 0;
            $orderDetail->discount_tax = 0;
            $orderDetail->subtotal = $subtotal;
            $orderDetail->tax = $tax;
            $orderDetail->subtotal_tax_included = $subtotalTaxIncluded;
        }
        
        $this->container->setOrderDetails($orderDetails);

        return $this->container;
    }
}
