<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 配送業者テーブル Model
 */
class Carrier extends Model
{
    use BaseTrait;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['created_at'];

}
