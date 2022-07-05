<?php
namespace App\Repositories;


use App\Models\OrderDelivery;


/**
 * 受注配送管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class OrderDeliveriesRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return OrderDelivery::where([]);
    }

    /**
     * 受注配送とwithで指定されたリレーションを取得する
     */
    public function select($orderId, $select = ['*'], $with = '') {
        $query = $this->getModel()->select($select);
        $query->where('order_id', $orderId);
        $query->orderBy('delivery_no', 'asc');
        $result = $query->with($with)->get();

        return $result;
    }
    

}
