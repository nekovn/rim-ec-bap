<?php
namespace App\Repositories;

use App\Models\CodeValue;


/**
 * ユーザ管理関連の処理をまとめたリポジトリクラス
 *
 */
class CodeValuesRepository 
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return CodeValue::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        // ソートkey、コード値(数値)でソートを行う
        $query = CodeValue::where('code', $param['code']);
        $query->orderBy('code','asc')
            ->orderBy('sequence','asc')
            ->orderByRaw('CAST(value as SIGNED)');

        return $query;
    }

    /**
     * 指定されたコード値のデータ取得
     * 
     * @param codes array
     * @return 連想配列
     */
    public function getDataByCode($codes, $addCols=[]) {
        $selects = ['code', 'value', 'content'];
        $selects = array_merge($selects, $addCols);
        $query = CodeValue::select($selects);
        if (count($codes)==1) {
            $query->where('code', $codes[0]);
        } else {
            $query->whereIn('code', $codes);
        }
        // ソートkey、コード値(数値)でソートを行う
        $query->orderBy('code','asc')
            ->orderBy('sequence','asc')
            ->orderByRaw('CAST(value as SIGNED) asc');

        return $query->get();
    }

    /**
     * リストボックスに表示するデータを取得する
     * @param codes:CodeDefine
     * @return key:CodeDefine  -- ary(cvalue => content or else)
     */
    public function getListData($codes) {
        $ret = [];
        foreach ($codes as $code) {
            $ret[$code] = [];
        }
        //初期表示用データ取得
        $resultCodes = $this->getDataByCode($codes);
        foreach ($resultCodes as $result) {
            $ret[$result['code']] += array($result['value'] => $result['content']);
        }
        return $ret;
    }

    /**
     * コード値情報を取得する
     * @param codes:CodeDefine
     * @return key:CodeDefine  -- ary(cvalue => code)
     */
    public function getDataSelects($codes, $selects )
    {
        $ret = [];
        foreach ($codes as $code) {
            $ret[$code] = [];
        }
        //初期表示用データ取得
        $resultCodes = $this->getDataByCode($codes, $selects);
        foreach ($resultCodes as $result) {
            $ret[$result['code']] += array($result['value'] => $result->getAttributes());
        }
        return $ret;
    }
}
