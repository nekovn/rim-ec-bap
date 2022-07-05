<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 出荷明細テーブルModel
 */
class ShipDetail extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];
}
