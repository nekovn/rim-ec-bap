<?php

namespace App\Models;

use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Helpers\Util\SystemHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * 受注テーブル Model
 */
class Order extends Model
{
    use BaseTrait;
    use LeftJoinCvalueContentScope;

    protected $guarded = ['id', 'created_at'];

    protected $hidden = ['created_at'];


    /** 初期値 */
    protected $attributes = array(
        'goods_total_tax' => 0,
        'goods_total_tax_included' => 0,
        'postage_total' => 0,
        'payment_fee_total' => 0,
        'packing_charge_total' => 0,
        'other_fee_total' => 0,
        'discount' => 0,
        'promotion_discount_total' => 0,
        'coupon_discount_total' => 0,
        'earned_points' => 0,
        'used_point' => 0,
        'point_amount' => 0,
        'point_conversion_rate' => 0,
        'total' => 0
      );

    protected $appends = ['status_name','status_style'];

    /**
     * 受注明細を取得
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * 受注配送先を取得
     */
    public function orderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class);
    }

    /**
     * 有効な受注決済を取得（最新受注決済）
     */
    public function orderPayment()
    {
        return $this->hasOne(OrderPayment::class)->orderBy('id', 'desc');//idでソートして最新を取得
    }

    // /**
    //  * 決済方法名
    //  */
    // public function getPaymentMethodNameAttribute() {
    //     if (!$this->payment_method) {
    //         return '';
    //     }
    //     return SystemHelper::getCodes(CodeDefine::PAYMENT_METHOD)["{$this->payment_method}"];
    // }

    /**
     * 決済テーブル
     */
    public function settlement()
    {
        return $this->hasOne(Settlement::class, 'code', 'payment_method');
    }

    /**
     * ステータス名
     */
    public function getStatusNameAttribute()
    {
        if (!$this->status) {
            return '';
        }
        return SystemHelper::getCodes(CodeDefine::ORDER_STATUS)["{$this->status}"];
    }
    /**
     * ステータススタイル
     */
    public function getStatusStyleAttribute()
    {
        if (!$this->status) {
            return '';
        }
        return SystemHelper::getCodeAttrs(CodeDefine::ORDER_STATUS)["{$this->status}"][CodeValueDefine::STATUS_STYLE_ATTR];
    }

    /**
     * 受注ステータスの名称を返す
     *
     * @return string 受注ステータスの名称
     */
    public function getOrderStatusValueAttribute()
    {
        return SystemHelper::getCodes(CodeDefine::ORDER_STATUS)[$this->status];
    }

    /**
     * 支払方法の名称を返す
     *
     * @return string 受注ステータスの名称
     */
    public function getPaymentMethodValueAttribute()
    {
        return SystemHelper::getCodes(CodeDefine::PAYMENT_METHOD)[$this->payment_method];
    }
}
