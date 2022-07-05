<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRank extends Model
{
    use BaseTrait;
    
    /**
     * 初期ランク
     */
    const DEFAULT_RANK_ID = "2"; // 非会員

    // relation
    public function customerRankAssign()
    {
        return $this->hasMany(App\Models\CustomerRankAssign::class);
    }

    public function customers()
    {
        return $this->belongsToMany(App\Models\Customers::class, 'customer_rank_assign');
    }
}
