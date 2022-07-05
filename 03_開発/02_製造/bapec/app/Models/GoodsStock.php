<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 在庫テーブルModel
 */
class GoodsStock extends Model
{
    // use BaseTrait;
    
    protected $primaryKey = ['goods_id', 'warehouse_id'];
    
    /**　increment無効化 */
    public $incrementing = false;

    protected $fillable = ['goods_id', 'warehouse_id'];

    // protected $hidden = ['created_at'];

    /**
     * 指定された商品ID、倉庫IDに該当するレコード
     *
     * @param $query
     * @param $goodsId 商品ID
     * @param $warehouseId 倉庫ID
     * @return mixed
     */
    public function scopeTarget($query, $goodsId, $warehouseId)
    {
        return $query->where('goods_id', $goodsId)
                     ->where('warehouse_id', $warehouseId)
                     ->where('is_deleted','=','0')
                     ->first();
    }
}
