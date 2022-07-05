<?php

namespace App\Cart\Calculation;

use App\Enums\RoundingTypeDefine;
use App\Models\Order;
use App\Models\Point;

/**
 * 注文料金計算
 */
class OrderPriceCalculator 
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
        // 注文
        $order = $this->container->getOrder();
        // 配送先リスト
        $orderDeliveries = $this->container->getOrderDeliveries();
        // 受注明細リスト
        $orderDetails = $this->container->getOrderDetails();

        // 送料合計
        $postageTotal = 0;
        // 決済手数料合計
        $paymentFeeTotal = 0;
        // 包装量合計
        $packingChargeTotal = 0;
        // その他手数料合計
        $otherFeeTotal = 0;
        foreach ($orderDeliveries as $orderDelivery) {
            $postageTotal += $orderDelivery->postage;
            $paymentFeeTotal += $orderDelivery->payment_fee;
            $packingChargeTotal += $orderDelivery->packing_charge;
            $otherFeeTotal += $orderDelivery->other_fee;
        }
        $order->postage_total = $postageTotal;
        $order->payment_fee_total = $paymentFeeTotal;
        $order->packing_charge_total = $packingChargeTotal;
        $order->other_fee_total = $otherFeeTotal;

        // 商品合計（税抜き）
        $salePrice = 0;
        // 商品合計（消費税）
        $goodsTotalTax = 0;
        // 商品合計（税込）
        $goodsTotalTaxIncluded = 0;
        // プロモーション値引額合計
        $promotionDiscountTotal = 0;
        foreach ($orderDetails as $orderDetail) {
            $salePrice += $orderDetail->sale_price;
            $goodsTotalTax += $orderDetail->tax;
            $goodsTotalTaxIncluded += $orderDetail->subtotal_tax_included;
            $promotionDiscountTotal += ($orderDetail->discount + $orderDetail->discount_tax);
        }
        $order->goods_total_tax = $goodsTotalTax;
        $order->goods_total_tax_included = $goodsTotalTaxIncluded;
        $order->promotion_discount_total = $promotionDiscountTotal;

        $total = \Price::sum(
            $order->goods_total_tax_included,
            $order->postage_total,
            $order->payment_fee_total,
            $order->packing_charge_total,
            $order->other_fee_total,
            -$order->discount,
            -$order->promotion_discount_total,
            -$order->coupon_discount_total,
            -$order->point_amount
        );
        $order->total = $total;

        // 付与ポイント計算
        $order = $this->calculateEarnedPoints($order);
        
        $this->container->setOrder($order);

        return $this->container;
    }

    /**
     * 付与ポイントを計算する
     * 
     * ポイント計算対象は、商品税抜き金額 - 割引額 - 使用ポイントとする
     * ※ポイント計算対象が0以下の場合は付与ポイントは0とする
     *
     * @param Order $order 注文データ
     * @return Order 注文データ
     */
    private function calculateEarnedPoints(Order $order)
    {
        $order->earned_points = 0;

        // 少数精度
        $decimals = config('cart.format.decimals', 2);
        bcscale($decimals);

        // ポイント運用
        $point = Point::commonPoint();
        
        // 付与率        
        $rate = $point->acquisition_rate;
        if (\Auth::check()) {
            $rank = \Auth::user()->currentRank();
            if ($rank->point_rate && $rank->point_rate > $rate) {
                $rate = $rank->point_rate;
            } else if ($rank->point_rate == 0) {
                $rate = 0;
            }
        }

        // ポイント計算対象（商品税抜き）
        // 使用ポイントも考慮する
        $target = \Price::sub($order->goods_total_tax_included, $order->goods_total_tax, $order->discount, $order->point_amount);
        if ($target <= 0) {
            // ポイント計算対象が0以下の場合
            $target = 0;
        }

        if ($point->acquisition_unit) {
            // ポイント付与単位がある場合
            $t1 = \Price::rounding(bcdiv($target, $point->acquisition_unit), RoundingTypeDefine::DOWN);
            $target = \Price::rounding(bcmul($t1, $point->acquisition_unit), RoundingTypeDefine::DOWN);
        }

        $point = \Price::rounding(bcdiv(bcmul($target, $rate), 100), RoundingTypeDefine::DOWN);

        $order->earned_points = $point;
        return $order;
    }
}
