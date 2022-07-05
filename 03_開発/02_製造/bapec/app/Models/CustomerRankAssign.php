<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRankAssign extends Model
{

    use BaseTrait;

    protected $guarded = ['created_at'];

    protected $primaryKey = 'customer_id';
    
    // relation
    public function customerRank()
    {
        return $this->hasOne(CustomerRank::class,'id', 'customer_rank_id');
    }
}
