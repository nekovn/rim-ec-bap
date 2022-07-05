<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 顧客ポイント履歴　Model
 */
class CustomerPointLog extends Model
{
    use BaseTrait;

    protected $guarded = ['id', 'created_at'];

    protected $hidden = ['created_at'];

}
