<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 配送業者都道府県別配送条件テーブル Model
 */
class DeliveryPrefConditions extends Model
{

    /**
     * 指定された配送業者、都道府県に該当するレコード
     *
     * @param Builder $query
     * @param string $carrierId 配送業者ID
     * @param string $prefCd 都道府県コード
     * @return void
     */
    public function scopeTarget($query, $carrierId, $prefCd)
    {
        return $query->where('carrier_id', $carrierId)->where('prefcode', $prefCd)->first();
    }
}
