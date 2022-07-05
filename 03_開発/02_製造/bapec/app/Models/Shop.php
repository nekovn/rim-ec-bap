<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ショップ運用マスタModel
 */
class Shop extends Model
{
    use BaseTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
