<?php

namespace App\Models;

use App\Enums\FlagDefine;
use Illuminate\Database\Eloquent\Model;

/**
 * 倉庫休日テーブル Model
 */
class WarehouseHolidays extends Model
{
    use BaseTrait;

    /**
     * 指定された倉庫IDに該当するレコード
     *
     * @param $query
     * @param $warehouseId 倉庫ID
     * @return mixed
     */
    public function scopeWarehouseHolidays($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId)
                     ->where('is_deleted', FlagDefine::OFF)
                     ->get();
    }
}
