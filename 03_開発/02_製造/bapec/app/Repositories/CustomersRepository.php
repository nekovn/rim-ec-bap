<?php
namespace App\Repositories;


use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Enums\FlagDefine;

/**
 * 顧客管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CustomersRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Customer::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $param 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        $query = Customer::select([
            'customers.*',
            'customer_ranks.rank_name'
        ]);

        // 会員ランクアサイン用JOIN
        $query->leftJoin('customer_rank_assigns', function ($join) {
            $join->on('customer_rank_assigns.customer_id', '=', 'customers.id')
            ->where('customer_rank_assigns.is_deleted', FlagDefine::OFF);
        });
        // 会員ランク用JOIN
        $query->leftJoin('customer_ranks', function ($join) {
            $join->on('customer_ranks.id', '=', 'customer_rank_assigns.customer_rank_id')
            ->where('customer_ranks.is_deleted', FlagDefine::OFF);
        });

        if (isset($param['id'])) {
            $query->where('customers.id', 'LIKE', $param['id'] . '%');
        }
        if (isset($param['full_name'])) {
            $query->where('customers.full_name', 'LIKE', '%'. $param['full_name'] . '%');
        }
        if (isset($param['full_name_kana'])) {
            $query->where('customers.full_name_kana', 'LIKE', '%'. $param['full_name_kana'] . '%');
        }
        if (isset($param['email'])) {
            $query->where('customers.email', 'LIKE', '%'. $param['email'] . '%');
        }

        return $query;
    }

    public function getCustomerList(): array
    {
        $ret = [];
        $resultCodes = [];
        $query = DB::table('customers')
        ->select('customers.id', 'customers.full_name')
        ->where('customers.is_deleted', '=', 0)
        ->orderby('customers.id');
        $resultCodes = $query->get();
        foreach ($resultCodes as $result) {
            $ret += array($result->id => $result->name);
        }
        return $ret;
   }
}
