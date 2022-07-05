<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\FlagDefine;

/**
 * メーカーマスタ　Model
 */
class Maker extends Model
{

    // relation
    public function goods()
    {
        return $this->hasMany('App\Models\Goods');
    }

    /**
     * 指定されたメーカーIDに該当するレコード
     *
     * @param $query
     * @param $makerId 商品ID
     * @return mixed
     */
    public function scopeTarget($query, $makerId)
    {
        return $query->where('id', $makerId)
                     ->where('is_deleted', FlagDefine::OFF)
                     ->first();
    }
}
