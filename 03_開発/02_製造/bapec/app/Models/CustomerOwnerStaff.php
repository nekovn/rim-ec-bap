<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 顧客主担当スタッフ　Model
 */
class CustomerOwnerStaff extends Model
{
    use BaseTrait;

    // テーブル名
    protected $table = 'customer_owner_staffs';

    protected $guarded = ['id', 'created_at'];
}
