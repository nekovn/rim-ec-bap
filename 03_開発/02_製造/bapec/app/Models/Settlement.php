<?php

namespace App\Models;

use Faker\Provider\Payment;
use Illuminate\Database\Eloquent\Model;

/**
 * 決済マスタ　Model
 */
class Settlement extends Model
{
    use BaseTrait;

    /**
     * 決済方法コードに対応する決済を取得
     *
     * @param $query
     * @param $paymentMethod 決済方法コード
     * @return Settlement
     */

    public function scopePaymentMethod($query, $paymentMethod)
    {
        return $query->where('code', $paymentMethod)->first();
    }

    /**
     * 全ての支払方法を取得
     * @param $query
     * @return Settlement
     */

    public function scopeAllPaymentMethod($query)
    {
        return $query->orderBy('sequence')->get(); //orderBy('sequence', 'asc');
    }

    /**
     * 指定された支払IDの決済方法を取得
     * @param $query
     * @param $payment_id 決済方法ID
     * @return Settlement
     */

    public function scopePaymentMethodById($query, $payment_id)
    {
        return $query->where('id', $payment_id)->first();
    }


}
