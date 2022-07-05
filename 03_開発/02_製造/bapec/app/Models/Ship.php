<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Util\SystemHelper;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;

/**
 * 出荷テーブルModel
 */
class Ship extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

    protected $appends = ['status_name', 'status_style'];

    /**
     * 出荷明細を取得
     */
    public function shipDetails()
    {
        return $this->hasMany(ShipDetail::class);
    }
    /**
     * 受注を取得
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    /**
     * 配送業者を取得
     */
    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }
    /**
     * ステータス名
     */
    public function getStatusNameAttribute()
    {
        if (!$this->status) {
            return '';
        }
        return SystemHelper::getCodes(CodeDefine::SHIP_STATUS)["{$this->status}"];
    }
    /**
     * ステータススタイル
     */
    public function getStatusStyleAttribute()
    {
        if (!$this->status) {
            return '';
        }
        return SystemHelper::getCodeAttrs(CodeDefine::SHIP_STATUS)["{$this->status}"][CodeValueDefine::STATUS_STYLE_ATTR];
    }
    /**
     * 配送希望時間帯名
     */
    public function getDesiredDeliveryTimeNameAttribute()
    {
        if (!$this->desired_delivery_time) {
            return '';
        }
        return SystemHelper::getCodes(CodeDefine::DELIVERY_TIME)["{$this->desired_delivery_time}"];
    }
}
