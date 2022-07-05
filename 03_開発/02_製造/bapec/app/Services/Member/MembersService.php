<?php
namespace App\Services\Member;

use App\Repositories\CustomerRankAssignRepository;
use App\Aspect\Annotation\Transactional;
use App\Services\SimpleCrudServiceTrait;
use App\Models\Customer;
use App\Models\CustomerRank;
use App\Models\CustomerRankAssign;

/**
 * 会員管理関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class MembersService
{
    use SimpleCrudServiceTrait {
        SimpleCrudServiceTrait::store as storeTrait;
    }

    public function __construct(CustomerRankAssignRepository $rankAssignRepository)
    {
        $this->rankAssignRepository = $rankAssignRepository;
    }

    /**
     * データを登録する。
     *
     * @param object $request リクエスト
     * @return Customer
     * @Transactional()
     */
    public function store($request)
    {
        $params = $request->all();

        // ゲートウェイ経由できたパラメータを取得
        if ($request->session()->has(\Consts::SES_GW_BCREWS_ID)) {
            $params['bcrews_customer_id'] = $request->session()->get(\Consts::SES_GW_BCREWS_ID);
        }
        if ($request->session()->has(\Consts::SES_GW_BCREW_ID)) {
            $params['bcrew_customer_id'] = $request->session()->get(\Consts::SES_GW_BCREW_ID);
        }
        $customerRankId = CustomerRank::DEFAULT_RANK_ID;
        if ($request->session()->has(\Consts::SES_GW_RANK_ID)) {
            $customerRankId = $request->session()->get(\Consts::SES_GW_RANK_ID);
        }

        // 顧客保存
        $customer = Customer::create($params);
        
        // 顧客ランク更新
        $this->reflectMemberType($customer->id, $customerRankId);

        return $customer;
    }

    /**
     * データを更新する。
     *
     * @param array $params パラメーター
     * @return Customer
     * @Transactional()
     */
    public function update($params)
    {
        $customer = Customer::find($params['id']);
        $customer = $customer->update($params);
        return $customer;
    }

    /**
     * 会員ランク登録・更新
     * 
     * @param string $customerId 顧客ID
     * @param string $rankId 会員ランクID
     */
    public function reflectMemberType($customerId, $rankId)
    {
        $rank = $this->rankAssignRepository->getCustomerAssign($customerId);
        if (!$rank || $rank->customer_rank_id != $rankId) {
            $UpdRank = CustomerRank::find($rankId);
            if ($UpdRank) {
                if ($rank) {
                    CustomerRankAssign::where('customer_id', $customerId)
                                      ->update(['customer_rank_id'=>$UpdRank->id]);
                } else {
                    CustomerRankAssign::create(['customer_rank_id' => $UpdRank->id,'customer_id' => $customerId]);
                }
            }
        }
    }
}
