<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 取引先テーブル Model
 */
class Supplier extends Model
{
    use BaseTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

}
