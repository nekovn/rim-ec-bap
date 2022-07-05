<?php

namespace App\Cart\Calculation;

use App\Models\Point;

/**
 * ポイント計算
 */
class PointCalculator 
{
    // 注文コンテナラッパー
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
        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        // 注文
        $order = $this->container->getOrder();
    
        // ポイント利用額
        $pointAmount = 0;
        // ポイント運用
        $point = Point::commonPoint();
        
        // ポイントに相当する金額を計算
        $amount = bcmul($order->used_point, $point->conversion_rate);
        $pointAmount = \Price::rounding($amount, $point->rounding_type);
        
        $order->point_conversion_rate = $point->conversion_rate;
        $order->point_amount = $pointAmount;

        $order->total = \Price::sub(
            \Price::sum(
                $order->goods_total_tax_included,
                $order->postage_total,
                $order->payment_fee_total,
                $order->packing_charge_total,
                $order->other_fee_total
            ),
            $order->discount,
            $order->promotion_discount_total,
            $order->coupon_discount_total,
            $order->point_amount
        );

        $this->container->setOrder($order);
        return $this->container;
    }
}
