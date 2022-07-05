<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 商品カテゴリテーブル Model
 */
class GoodsCategory extends Model
{
    
    protected $guarded = ['id', 'created_at'];

    protected $hidden = ['created_at'];

    /**
     * 紐づく商品
     */
    public function goods()
    {
        return $this->hasOne(\App\Models\Goods::class, 'id', 'goods_id');
    }
}
