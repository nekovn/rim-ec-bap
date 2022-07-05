<?php
namespace App\Services\Admin;

use App\Aspect\Annotation\Transactional;
use App\Repositories\CustomersRepository;
use App\Services\SimpleCrudServiceTrait;
use App\Services\Member\MembersService;
use App\Models\CustomerRank;

/**
 * 顧客管理関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class CustomersService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param CustomersRepository $customersRepository 顧客リポジトリ
     */
    public function __construct(
        CustomersRepository $customersRepository,
        MembersService $memberService) {
        $this->repository = $customersRepository;
        $this->memberService = $memberService;
    }

    /**
     * 画面の選択肢を取得する。
     *
     * @return array
     */
    public function getScreenSelections(): array
    {
        // 生年月日・年 現在年、100年前
        $fromYear = (int)date('Y');
        $toYear = $fromYear - 100;

        return [
            'fromYear' => $fromYear,
            'toYear' => $toYear,
        ];
    }

    /**
     * データを１件取得する。
     *
     * @access public
     * @param number $id 主キー
     * @return array
     */
    public function getData($id)
    {
        $where = [
            'id' => $id
        ];
        $rowData = $this->repository->findByPkey($where, false, ['*'], 'customerRank:rank_name');
        $rowData['point'] = \App\Models\Customer::find($id)->remainingPoints();
        $rowData['rank_name'] = count($rowData['customerRank']) == 0 ? '' : $rowData['customerRank'][0]['rank_name'];

        return [
            'data' => $rowData,
        ];
    }
    /**
     * データを登録する。
     *
     * @access public
     * @
     * @param array $params パラメーター
     * @return Model
     * @Transactional()
     */
    public function store(array $params)
    {
        $customner = $this->repository->create($params);
        $this->memberService->reflectMemberType($customner->id, CustomerRank::DEFAULT_RANK_ID);
        return $customner;
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param number $id 主キー
     * @param array $params パラメーター
     * @return Model
     * @Transactional()
     */
    public function update($id, array $params)
    {
        $where = ['id' => $id];
        $where['updated_at'] = $params['ol_updated_at'];

        //顧客テーブル更新
        $model = $this->repository->update($params, $where, true);

        $data = $this->getData($id);
        return $data['data'];
    }
}
