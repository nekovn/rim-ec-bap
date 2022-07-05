<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CodeDefine;
use App\Helpers\Util\SystemHelper;

/**
 * 受注決済テーブル Model
 */
class OrderPayment extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

    /** 受注明細が更新された時は受注テーブルのupdated_atも更新する */
    protected $touches = ['order'];

    protected $append = ['payment_status_name', 'payment_status_style'];
    /**
     * 決済ステータス名
     */
    public function getPaymentStatusNameAttribute()
    {
        if (!$this->payment_status) {
            return '';
        }
        return SystemHelper::getCodes(CodeDefine::PAYMENT_STATUS)["{$this->payment_status}"];
    }
    /**
     * 決済ステータススタイル
     */
    public function getPaymentStatusStyleAttribute()
    {
        if (!$this->payment_status) {
            return '';
        }
        return SystemHelper::getCodeAttrs(CodeDefine::PAYMENT_STATUS)["{$this->payment_status}"]['attr5'];
    }
    /**
     * 受注を取得
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
