<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 消費税マスタ　Model
 */
class Tax extends Model
{
    use BaseTrait;

    /**
     * アクティブな税率
     *
     * @param $query
     * @param $taxKind 消費税種類
     * @return mixed
     */
    public function scopeActive($query, $taxKind)
    {
        return $query->where('tax_kind', $taxKind)
                        ->whereDate('start_date', '<=', Carbon::today())
                        ->orderByDesc('start_date')
                        ->first();
    }
}
