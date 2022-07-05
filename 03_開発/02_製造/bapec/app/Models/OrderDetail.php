<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 受注明細テーブル Model
 */
class OrderDetail extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

    /** 受注明細が更新された時は受注テーブルのupdated_atも更新する */
    protected $touches = ['order'];

    /** 初期値 */
    protected $attributes = array(
        'unit_price' => 0,
        'sale_price' => 0,
        'sale_price_tax' => 0,
        'sale_price_tax_included' => 0,
        'discount' => 0,
        'discount_tax' => 0,
        'subtotal' => 0,
        'tax' => 0,
        'subtotal_tax_included' => 0,
        'purchase_unit_price' => 0,
        'quantity' => 0
    );

    // relation
    /**
     * 受注を取得
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    /**
     * 商品を取得
     */
    public function goods()
    {
        return $this->belongsTo(\App\Models\Goods::class);
    }
}
