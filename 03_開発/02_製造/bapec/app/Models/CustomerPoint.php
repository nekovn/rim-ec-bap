<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PointKindDefine;

/**
 * 顧客ポイント　Model
 */
class CustomerPoint extends Model
{
    use BaseTrait;

    protected $guarded = ['id', 'created_at'];

    protected $hidden = ['created_at'];

    /**
     * 顧客IDに該当するレコード
     *
     * @param $query
     * @param $customerId 顧客ID
     * @return mixed
     */
    public function scopeTarget($query, $customerId)
    {
        return $query->where('point_kind', PointKindDefine::COMMON)
                     ->where('customer_id', $customerId)
                     ->where('is_deleted','=','0')
                     ->first();
    }
}
