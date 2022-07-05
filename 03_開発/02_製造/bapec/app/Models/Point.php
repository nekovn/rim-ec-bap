<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\PointKindDefine;

/**
 * ポイント運用　Model
 */
class Point extends Model
{
    use BaseTrait;

    /**
     * アクティブな店舗共通ポイント
     *
     * @param $query
     * @return mixed
     */
    public function scopeCommonPoint($query)
    {
        return $query->where('kind', PointKindDefine::COMMON)
                        ->where('is_valid', '1')
                        ->first();
    }
}
