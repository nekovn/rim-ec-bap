<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CodeDefine;
use App\Helpers\Util\SystemHelper;

/**
 * 受注配送先テーブル Model
 */
class OrderDelivery extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

    /** 受注配送が更新された時は受注テーブルのupdated_atも更新する */
    protected $touches = ['order'];

    protected $dates = ['delivery_date'];

    /** 初期値 */
    protected $attributes = array(
        'postage' => 0,
        'payment_fee' => 0,
        'packing_charge' => 0,
        'other_fee' => 0
    );
    
    /**
     * 受注を取得
     */
    public function order() {
        return $this->belongsTo(Order::class);
    }

    /**
     * 受注明細を取得
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_delivery_id')->orderBy('detail_no');
    }

    /**
     * 出荷を取得
     */
    public function ship()
    {
        return $this->hasOne(Ship::class);
    }

    /**
     * 配送業者を取得
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    /**
     * 配送希望時間帯名
     */
    public function getDeliveryTimeNameAttribute()
    {
        if (!$this->delivery_time) {
            return '';
        }
        return SystemHelper::getCodes(CodeDefine::DELIVERY_TIME)["{$this->delivery_time}"];
    }
}
