<?php

namespace App\Models;

use App\Enums\FlagDefine;

trait LeftJoinCvalueContentScope
{
    /**
     * コード値内容を取得する為に、コード値マスタと結合するローカルスコープ
     * スコープを使いたいModelでuseし、'scope'を取り除いたメソッド名で呼び出す
     * 
     * コード値内容は、{結合カラム名}_nmの名称で取り出す
     * ※使う場合はselect(結合対象のテーブル.*)で取得項目を絞っておくこと
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $joinKey 結合するコード値のkey(table.column)
     * @param CodeDefine $code 結合するコード値のコード
     * @param string $nmColumn 名称として指定するコード値マスタの列名
     * @return \Illuminate\Database\Eloquent\Builder 指定コードのコード値内容を結合したクエリ
     */
    public function scopeLeftJoinCvalueContent($query, $joinKey, $code, $nmColumn='value')
    {
        // コード値のkeyからcolumnの方を取得
        $joinColmun = explode(".", $joinKey)[1];
        // コード値マスタの別名
        $cvName = "CV{$code}";

        return $query->leftJoin("code_values as ".$cvName, function($join) use ($joinKey, $code ,$cvName) {
            $join->on($joinKey, '=', $cvName . ".key")
            ->where($cvName . ".code", '=', $code)
            ->where($cvName . ".is_deleted", '<>', FlagDefine::ON);
        })
                ->addSelect("{$cvName}.{$nmColumn} as {$joinColmun}_nm");
    }
}