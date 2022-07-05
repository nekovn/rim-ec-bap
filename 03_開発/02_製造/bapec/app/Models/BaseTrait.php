<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

use App\Enums\FlagDefine;
use App\Helpers\Util\SystemHelper;

use Carbon\Carbon;

/**
 * 基底Traitクラス
 *
 * @category  システム共通
 * @package   App\Models
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */

use App\Models\DeleteFlagTrait;

trait BaseTrait
{
    // 削除データを日付で管理する場合、有効化
    // use Illuminate\Database\Eloquent\SoftDeletes;
    // 削除データをフラグで管理する場合、有効化
    use DeleteFlagTrait;

    /**
     * Event Hooks
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        /**
         * insert前処理
         */
        static::creating(function ($model) {
            if (SystemHelper::getAppSettingValue('entity.use_created_user_id')) {
                if (Auth::check()) {
                    $model->created_by = Auth::user()->id;
                }
            }
        });
        /**
         * update前処理
         */
        static::updating(function ($model) {
            if (SystemHelper::getAppSettingValue('entity.use_updated_user_id') && Auth::check()) {
                if (Auth::check()) {
                    $model->updated_by = Auth::user()->id;
                }
            }
        });
    }

    /**
     * 更新日時の書式変換を行う。
     *
     * @param date $value 更新日時
     * @return date(Y-m-d H:i:s)
     */
    public function getUpdatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        $datetime = new Carbon($value);
        $datetime->setTimezone(config('app.timezone'));
        return $datetime->format('Y-m-d H:i:s');
    }
}
