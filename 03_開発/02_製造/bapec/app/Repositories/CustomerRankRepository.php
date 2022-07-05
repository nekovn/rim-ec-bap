<?php
namespace App\Repositories;

use App\Models\CustomerRank;

/**
 * 会員ランクリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CustomerRankRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return CustomerRank::where([]);
    }

    /**
     * 該当会員ランク情報取得
     *
     * @return query
     */
    public function getCustomerRank($apiMemberType)
    {
        return CustomerRank::select('id')
            ->where('app_member_type', '=', $apiMemberType)
            ->where('start_date','<=', now()->format('Y-m-d'))
            ->orderBy('start_date', 'desc')->first();
        
    }
}
