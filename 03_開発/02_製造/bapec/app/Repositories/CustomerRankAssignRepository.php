<?php
namespace App\Repositories;

use App\Models\CustomerRankAssign;
use Illuminate\Support\Carbon;

/**
 * 会員ランクアサインリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CustomerRankAssignRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return CustomerRankAssign::where([]);
    }

    /**
     * 会員ランクアサイン情報取得。
     *
     * @return query
     */
    public function getCustomerAssign($customerId)
    {
        $query = CustomerRankAssign::select(['customer_rank_id'])
        ->where('customer_id', '=', $customerId)
        // ->where(function($query1) {
        //     $query1->where('next_start_date','>=' ,Carbon::now())
        //     ->orWhereNull('next_start_date');
        // })
        ->with('customerRank:app_member_type')
        ->orderby('next_start_date','desc')->first();

        return $query;
    }
}
