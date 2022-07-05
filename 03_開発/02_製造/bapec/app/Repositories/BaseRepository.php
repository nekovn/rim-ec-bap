<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Exceptions\database\NotFoundException;
use App\Exceptions\database\OptimisticLockException;

/**
 * リポジトリ親クラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
trait BaseRepository
{
    /**
     * 利用するModelクラスを取得する。
     */
    abstract protected function getModel();

    /**
     * 主キーを基にデータを取得する。
     *
     * @param array $where where句に指定する条件を定義。
     * @param boolean $includeInActive 無効なデータを対象とするか。未指定の場合、is_deleted = 0のデータを対象とする。
     * @param array $select select句に指定する項目を定義。
     * @return array 取得結果
     * @throws NotFoundException
     */
    public function findByPkey($where, $includeInActive = false, array $select = ['*'], $with = '')
    {
        $model = $this->getModel();
        // select句
        $model = $model->select($select);
        // where句
        $model = $model->where($where);
        // 無効データ取得
        if ($includeInActive) {
            $model = $model->withTrashed();
        }
        if ($with) {
            $result = $model->with($with)->first();
        } else {
            $result = $model->first();
        }
        if (!$result) {
            throw new NotFoundException();
        }

        return $result;
    }
    /**
     * 指定条件を基に対象データの件数を取得する。
     * 検索条件は全てANDとする。ORや副問い合わせ等が必要なクエリーは各Repositoryクラスに記載する。
     *
     * @param array $where where句に指定する条件を定義。未指定の場合、全件取得となる。
     * @param boolean $includeInActive 無効なデータを対象とするか。
     * @return number 取得件数
     */
    public function count(array $where = [], $includeInActive = false)
    {
        $model = $this->getModel();
        // where句
        if (count($where) > 0) {
            $model = $model::where([$where]);
        }
        // 無効データ取得
        if ($includeInActive) {
            $model = $model->withTrashed();
        }
        return $model->count();
    }
    /**
     * 指定条件を基にデータを取得する。
     * 検索条件は全てANDとする。ORや副問い合わせ等が必要なクエリーは各Repositoryクラスに記載する。
     *
     * @param array $conditions クエリーを発行する条件を指定する。
     *              ['where' => [['id',1], ...],       where句に指定する条件を定義。未指定の場合、全件取得となる。
     *               'includeInActive' => true,           無効なデータを対象とするか。
     *               'order' => [item => asc/desc, ...] ソート順を指定する。指定なしの場合、ソート順なしのクエリーを発行する。
     *               'offset' => 0,                       取得開始位置を指定する。未指定の場合、指定なし
     *               'limit' => 25,                       取得件数の制限を指定する。未指定の場合、指定なし
     *               'select' => ['id as id', ...]        取得項目を指定する。未指定の場合、全項目を取得する。
     *              ]
     * @return array 取得結果
     */
    public function find(array $conditions)
    {
        $model = $this->getModel();
        // select句
        if (isset($conditions['select'])) {
            $model = $model->select($conditions['select']);
        }
        // where句
        if (isset($conditions['where']) && count($conditions['where']) > 0) {
            $model = $model->where($conditions['where']);
        }
        // 無効データ取得
        if (isset($conditions['includeInActive']) && $conditions['includeInActive']) {
            $model = $model->withTrashed();
        }
        // ソート順指定
        if (isset($conditions['order'])) {
            foreach ($conditions['order'] as $key => $order) {
                $model = $model->orderBy($key, $order);
            }
        }
        // 取得開始位置
        if (isset($conditions['offset'])) {
            $model = $model->skip($conditions['offset']);
        }
        // 取得件数
        if (isset($conditions['limit'])) {
            $model = $model->take($conditions['limit']);
        }

        return $model->get();
    }
    /**
     * 登録する。
     *
     * @param array $values 登録対象のカラム
     * @return model
     */
    public function create(array $values)
    {
        $values['created_by'] = Auth::user()->id;
        $values['updated_by'] = Auth::user()->id;
        return $this->getModel()->create($values);
    }
    /**
     * 複数件登録する。
     *
     * @param array $values 登録対象のカラム
     * @return model
     */
    public function bulkInsert(array $values)
    {
        $model = $this->getModel();
        foreach ($values as &$value) {
            $value['updated_by'] = Auth::user()->id;
        }
        return $model->insert($values);
    }
    /**
     * 更新する。
     * @param array $values 更新カラムの情報。[[key => value], ・・・]
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function update(array $values, array $where = [], $checkOptimistickLock = true)
    {
        // 排他制御しない
        if (!$checkOptimistickLock) {
            unset($where['updated_at']);
        }
        $model = $this->findByPkey($where);
        if (!$model) {
            throw new OptimisticLockException();
        }

        $model->fill($values);
        $model->updated_by = Auth::user()==null ? 0 : Auth::user()->id;//未ログイン時（バッチ）は0
        $model->save();

        return $model;
    }
    /**
     * 論理削除を行う。
     *
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function logicalDelete(array $where = [])
    {
        $model = $this->findByPkey($where);
        $model->updated_by = Auth::user()->id;
        $model->save();
        $model = $this->getModel()->where($where)->delete();
    }
    /**
     * 物理削除を行う。
     *
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function delete(array $where = [], $checkOptimistickLock = true)
    {
        $model = $this->getModel();

        // 排他制御しない
        if (!$checkOptimistickLock) {
            unset($where['updated_at']);
        }
        $count = $model->where($where)->forceDelete();
        if ($checkOptimistickLock && $count === 0) {
            throw new OptimisticLockException();
        }
        return $count;
    }

    /**
     * Like検索用にパラメータをエスケープします.
     */
    public function escapeLikeQuery($value) {
        return str_replace(array('\\', '%', '_'), array('\\\\', '\%', '\_'), $value);
    }

    /**
     * whereQueryを構築します
     * $wheres key:likeb:前方一致  like:部分一致 eq:完全一致 value:[paramのkey=>db fieldnm]
     */
    public function createQueryByConditionsWhere($query, $params, $wheres) {
        foreach ($wheres as $kbn => $kbns) {
            foreach ($kbns as $pField => $dFields) {
                if (isset($params[$pField])) {

                    if (is_array($dFields)) {//複数のDB項目が対象
                        $query->where(function ($query1) use ($kbn, $params, $dFields, $pField) {
                        //複数件
                        foreach ($dFields as $index => $dField) {
                            
                                if ($index == 0) {
                                    $this->createQueryWhere($query1, $kbn, $dField, $params[$pField]);
                                } else {
                                    $this->createQueryWhere($query1, $kbn, $dField, $params[$pField]);
                                }
                            }
                        });
                    } else {
                        $this->createQueryWhere($query, $kbn, $dFields, $params[$pField]);
                    }
                }
            }
        }
        return $query;
    }
    private function createQueryWhere($query, $kbn, $dbField, $value) {
        switch ($kbn) {
            case 'likeb':
                $query->where($dbField, 'LIKE',$value . '%');
                break;
            case 'like':
                $query->where($dbField, 'LIKE', '%' .  $value . '%');
                break;
            case 'eq':
                if (is_array($value)) {
                    $query->where(function($query1) use ($dbField, $value) {
                        foreach ($value as $key => $val) {
                            if ($key == 0) {
                                $query1->where($dbField, '=', $val);
                            } else {
                                $query1->orWhere($dbField, '=', $val);
                            }
                        }
                    });
                } else {
                    $query->where($dbField, '=',$value);
                }
                break;
            case 'raw'://パラメータ1つ
                $query->whereRaw($dbField, [$value]);
                break;
        }
        return $query;
    }
}
