<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

use App\Exceptions\database\OptimisticLockException;

/**
 * 一覧詳細画面関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @copyright 2020 elseif.jp All Rights Reserved.
 * @version   1.0
 */
trait SimpleCrudRepositoryTrait
{
    /**
     * 検索条件に一致する件数を取得する。
     *
     * @param array $conditions 検索条件
     * @return number 該当件数
     */
    public function countByCondition(array $conditions)
    {
        return $this->getQueryByConditions($conditions)->count();
    }
    /**
     * 検索条件に該当するデータを取得する。
     *
     * @param arary $conditions 検索条件
     * @param array $pageParams 取得ケージ
     * @return array 該当データのリスト
     */
    public function findByConditions(array $conditions, $pageParams = [])
    {
        $query = $this->getQueryByConditions($conditions);
        // ソート条件追加
        if (Arr::has($pageParams, 'sortItem') && Arr::has($pageParams, 'sortOrder') && $pageParams['sortItem']) {
            //デフォルトで指定されているソートを解除して設定
            $query->reorder($pageParams['sortItem'], $pageParams['sortOrder']);
            // $query->orderBy($pageParams['sortItem'], $pageParams['sortOrder']);
        }
        // 取得開始位置
        if (Arr::has($pageParams, 'page') && Arr::has($pageParams, 'count')) {
            $query->skip(($pageParams['page'] - 1) * $pageParams['count']);
        }
        // 取得件数
        if (Arr::has($pageParams, 'count')) {
            $query->take($pageParams['count']);
        }

        return $query->get();
    }
    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    abstract protected function getQueryByConditions(array $param);
}
