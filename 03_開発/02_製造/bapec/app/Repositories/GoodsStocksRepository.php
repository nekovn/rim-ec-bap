<?php
namespace App\Repositories;

use App\Models\GoodsStock;
use Illuminate\Support\Arr;

/**
 * 商品在庫管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class GoodsStocksRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return GoodsStock::where([]);
    }

    /**
     * 在庫　upsert
     */
    public function upsert($value)
    {
        //複合キーはupsertできない（Laravelエラーがでる）ので地道に
        $new = GoodsStock::where(['goods_id'=> $value['goods_id'], 'warehouse_id'=>$value['warehouse_id']]);
        if ($new->count()===0) {
            // $this->create($value);
            $new = new GoodsStock(['goods_id'=> $value['goods_id'], 'warehouse_id'=>$value['warehouse_id']]);
            $new->assigned_quantity = $value['assigned_quantity'];
            if (isset($value['quantity'])) {
                $new->quantity = $value['quantity'];
            }
            if (isset($value['b_grade_quantity'])) {
                $new->b_grade_quantity = $value['b_grade_quantity'];
            }
            $new->save();
        } else {
            $new->update($value);
        }

        // return GoodsStock::updateOrCreate(
        //     Arr::only($value, ['goods_id', 'warehouse_id']),
        //     $value
        // );
    }
}