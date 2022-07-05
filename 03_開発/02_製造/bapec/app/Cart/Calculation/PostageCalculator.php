<?php

namespace App\Cart\Calculation;

use App\Models\DeliveryPrefConditions;

/**
 * 送料計算
 */
class PostageCalculator 
{
    // 注文コンテナラッパー
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    /**
     * 送料計算を行う。
     * 
     * @todo 今は単一配送のみを考慮した実装とする
     *
     * @return void
     */
    public function calculate()
    {
        // 注文
        $order = $this->container->getOrder();
        // 配送先リスト
        $orderDeliveries = $this->container->getOrderDeliveries();
        // 受注明細リスト
        $orderDetails = $this->container->getOrderDetails();

        // 送料無料判定対象金額（値引きを入れた税込み金額）
        $total = 0;
        // 商品合計（税込）
        $goodsTotalTaxIncluded = 0;
        // プロモーション値引額合計
        $promotionDiscountTotal = 0;
        foreach ($orderDetails as $orderDetail) {
            $goodsTotalTaxIncluded += $orderDetail->subtotal_tax_included;
            $promotionDiscountTotal += ($orderDetail->discount + $orderDetail->discount_tax);
        }
        $total = \Price::sum(
            $goodsTotalTaxIncluded,
            -$promotionDiscountTotal,
            -$order->discount,
            -$order->coupon_discount_total,
            -$order->point_amount
        );

        // 送料合計
        $postageTotal = 0;

        // 配送先毎に送料計算
        foreach ($orderDeliveries as $delivery) {
            $delivCond = null;
            if ($delivery->carrier_id && $delivery->delivery_prefcode) {
                // 都道府県別配送条件を取得
                $delivCond = DeliveryPrefConditions::target($delivery->carrier_id, $delivery->delivery_prefcode);
            }
            if ($delivCond) {
                if (isset($delivCond->postage)) {
                    $delivery->postage = $delivCond->postage;
                }
                
                if ($delivCond->postage_free_amount && 
                    $total >= $delivCond->postage_free_amount) {
                    // 送料無料金額を上回っている場合
                    $delivery->postage = 0;
                }
            } else {
                $delivery->postage = 0;
            }
            $postageTotal += $delivery->postage;
        }
        $this->container->setOrderDeliveries($orderDeliveries);

        // 送料合計
        $order->postage_total = $postageTotal;
        $this->container->setOrder($order);

        return $this->container;
    }
}
