<?php

namespace App\Cart\Calculation;

use App\Models\Settlement;

/**
 * 決済手数料計算
 */
class PaymentFeeCalculator 
{
    // 注文コンテナラッパー
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    /**
     * 決済手数料計算を行う。
     *
     * @return void
     */
    public function calculate()
    {
        // 注文
        $order = $this->container->getOrder();
        // 配送先リスト
        $orderDeliveries = $this->container->getOrderDeliveries();
        
        $paymentFee = 0;

        // 決済マスタ取得
        $settlement = null;
        if ($order->payment_method) {
            $settlement = Settlement::paymentMethod($order->payment_method);
        }
        if ($settlement) {
            $paymentFee = $settlement->payment_fee;
        }

        // 決済手数料合計
        $paymentFeeTotal = 0;
        foreach ($orderDeliveries as $orderDelivry) {
            $orderDelivry->payment_fee = $paymentFee;
            $paymentFeeTotal = \Price::sum($paymentFeeTotal,  $paymentFee);
        }

        $order->payment_fee_total = $paymentFeeTotal;
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
        $this->container->setOrderDeliveries($orderDeliveries);

        return $this->container;
    }
}
