<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  ユーザ権限マスタ　Model
 */
class UserAuth extends Model
{
    use BaseTrait;

    public static function bootDeleteFlagTrait()
    {
        // 削除フラグ無効
    }

    protected $guarded = ['created_at', 'updated_at'];

}
