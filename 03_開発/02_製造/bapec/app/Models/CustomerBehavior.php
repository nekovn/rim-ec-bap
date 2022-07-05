<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBehavior extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['customer_id','useragent','last_logined_at','last_orderd_at','purchases_count','purchases_amount'];

    protected $primaryKey = 'customer_id';

    /**
     * 顧客行動を登録する
     *
     * @param array $param パラメータ
     * @return void
     */
    public static function putBehavior($param)
    {
        $b = CustomerBehavior::where('customer_id', $param['customer_id']);
        if ($b) {
            $b->update($param);
        } else {
            CustomerBehavior::create($param);
        }
    }
}
