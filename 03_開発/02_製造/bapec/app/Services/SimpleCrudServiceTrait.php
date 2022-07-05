<?php

namespace App\Services;

use App\Helpers\Util\SystemHelper;

/**
 * シンプルなCRUDを処理を行うサービストレイト
 *
 * @package   App\Services\Template
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */
trait SimpleCrudServiceTrait
{
    /**
     * 画面の選択肢を取得する。
     *
     * @return array
     */
    public function getScreenSelections(): array
    {
        return [];
    }
    /**
     * データを取得する。
     *
     * @access public
     * @param array $searchParams 検索条件
     * @param array $pageParams 取得ページ
     * @return array
     */
    public function find(array $searchParams, $pageParams = [])
    {
        $message = '';
        $count = $this->repository->countByCondition($searchParams);
        if ($count === 0) {
            return ['total' => $count, 'data' => [], 'message' => $message];
        }
        $message = SystemHelper::getCountLimitOverMessage($count);
        $rows = $this->repository->findByConditions($searchParams, $pageParams);
        return ['total' => $count, 'data' => $rows, 'message' => $message];
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
        $rowData = $this->repository->findByPkey($where);

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
     */
    public function store(array $params)
    {
        return $this->repository->create($params);
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param number $id 主キー
     * @param array $params パラメーター
     * @return Model
     */
    public function update($id, array $params)
    {
        $where = ['id' => $id];
        $lockColumn = SystemHelper::getAppSettingValue('entity.optimistic-lock-column');
        if ($lockColumn) {
            $where[$lockColumn] = $params['ol_updated_at'];
        }

        $model = $this->repository->update($params, $where);
        return $model;
    }
    /**
     * データを削除する。
     *
     * @access public
     * @param number $id
     * @param array $params
     */
    public function delete($id, $params)
    {
        $where = ['id' => $id];
        $lockColumn = SystemHelper::getAppSettingValue('entity.optimistic-lock-column');
        if ($lockColumn) {
            $where[$lockColumn] = $params['ol_updated_at'];
        }
        $this->repository->logicalDelete($where);
    }
}
