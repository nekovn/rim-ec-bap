<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Carbon\Carbon;
use App\Enums\CodeDefine;
use App\Enums\FlagDefine;
use App\Enums\StatusDefine;
use App\Models\OrderPayment;

/**
 * 受注管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class OrdersRepository
{
    use BaseRepository {
        BaseRepository::update as baseUpdate;
    }
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Order::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {
        //受注条件
        $PETAIL_WHERE = [
            'eq' => ['payment_status' => 'payment_status'],
        ];
        $ORDERD_WHERE = [
            'likeb' => ['goods_code'=>'order_details.goods_code'],
            'like' => ['goods_name'=> 'order_details.name'],
        ];
        $ORDER_WHERE = [
            'likeb' => ['id' => 'orders.id', 'customer_id' => 'customer_id'],
            'raw' =>
            ['customer_name' =>
            "concat(orders.name, orders.surname, orders.surname_kana,orders.name_kana) LIKE concat('%', ?, '%')"],
            'like' => [ 'email' => 'email'],
            'eq' => ['order_status' => 'status']
        ];
        $query = $this->getModel()->select(
            'orders.id',
            'orders.ordered_at',
            'orders.customer_id',
            'orders.total',
            'orders.payment_method',
            'orders.status',
            'CV' . CodeDefine::PAYMENT_STATUS . '.attr_5 as payment_status_style',
            'settlements.display_name as payment_method_name'
        )->selectRaw('concat(orders.surname,orders.name) as customer_name_full');

        //--- 受注決済　order_id内でMaxidが有効
        $sql = 'select order_id, payment_status, payment_method from order_payments o1
                where
	            exists
                (select 1 from (select order_id ,max(id) as maxid
                                from order_payments
                                group by order_id) as maxpayments
				 where o1.id = maxpayments.maxid
                 )
                 and is_deleted = '. FlagDefine::OFF;

        $query->join(DB::raw("({$sql}) as order_payments"), function ($join) use ($param, $PETAIL_WHERE) {
            $join->on('orders.id', '=', "order_payments.order_id");
            $join = $this->createQueryByConditionsWhere($join, $param, $PETAIL_WHERE);
        });
        //--- 決済方法名
        $query->leftJoin('settlements', function ($join) {
            $join->on('order_payments.payment_method', '=', "settlements.code");
            $join->where('settlements.is_deleted', FlagDefine::OFF);
        });

        //--- コード系
        $query->leftJoinCvalueContent('order_payments.payment_status', CodeDefine::PAYMENT_STATUS);

        $query = $this->createQueryByConditionsWhere($query, $param, $ORDER_WHERE);

        // 注文日時
        if ((isset($param['ordered_at_from']))) {
            $updateFrom = (new Carbon($param['ordered_at_from']))->format('Ymd');
            $query->whereRaw("date_format(orders.ordered_at,  '%Y%m%d') >= ". $updateFrom);
        }
        if ((isset($param['ordered_at_to']))) {
            $updateTo = (new Carbon($param['ordered_at_to']))->format('Ymd');
            $query->whereRaw("date_format(orders.ordered_at,  '%Y%m%d') <= " . $updateTo);
        }
        //--- 受注明細(exists)
        $query->whereExists(function ($query1) use ($param, $ORDERD_WHERE) {
            $query1->select(DB::raw(1))->from('order_details')
            ->whereRaw('orders.id = order_details.order_id');
            $query1 = $this->createQueryByConditionsWhere($query1, $param, $ORDERD_WHERE);
        });

        return $query;
    }

    /**
     * 更新する。
     * @param array $values 更新カラムの情報。[[key => value], ・・・]
     * @param array $where 更新条件。未指定の場合、全件更新される。
     * @param boolean $checkOptimistickLock 排他制御を行うか
     * @return number 更新件数
     * @exception OptimisticLockException
     */
    public function update(array $values, array $where = [])
    {
        //受注テーブル
        $order = $this->baseUpdate($values['order'], $where, true);
        //受注配送テーブル
        foreach ($values['orderDelivery'] as $pdelivery) {
            $delivery = $order->orderDeliveries()->find($pdelivery['id']);
            $delivery->fill($pdelivery)->save();
            if ($values['order']['status'] == StatusDefine::SHUKKA_IRAI) {
                //出荷依頼に変更時は出荷テーブルを作成する
                //出荷テーブル
                $ship = $delivery->ship()->create(
                    [
                    'order_id' => $delivery['order_id'],
                    // 'order_delivery_id' => $delivery[''],
                    'order_delivery_no' => $delivery['delivery_no'],
                    'status' => StatusDefine::SHUKKA_MACHI,
                    'ship_direct_date' => Carbon::parse($order['updated_at'])->format('Y/m/d'),
                    // 'ship_date' => $delivery[''],
                    // 'ship_cancel_date' => $delivery[''],
                    'delivery_type' => $delivery['delivery_type'],
                    'customer_id'   => $order['customer_id'],
                    'client_surname' => $delivery['client_surname'],
                    'client_name' => $delivery['client_name'],
                    'client_surname_kana' => $delivery['client_surname_kana'],
                    'client_name_kana' => $delivery['client_name_kana'],
                    'client_zip' => $delivery['client_zip'],
                    'client_prefcode' => $delivery['client_prefcode'],
                    'client_addr_1' => $delivery['client_addr_1'],
                    'client_addr_2' => $delivery['client_addr_2'],
                    'client_addr_3' => $delivery['client_addr_3'],
                    'client_addr' => $delivery['client_addr'],
                    'client_tel' => $delivery['client_tel'],
                    'ship_surname' => $delivery['delivery_surname'],
                    'ship_name' => $delivery['delivery_name'],
                    'ship_surname_kana' => $delivery['delivery_surname_kana'],
                    'ship_name_kana' => $delivery['delivery_name_kana'],
                    'ship_zip' => $delivery['delivery_zip'],
                    'ship_prefcode' => $delivery['delivery_prefcode'],
                    'ship_addr_1' => $delivery['delivery_addr_1'],
                    'ship_addr_2' => $delivery['delivery_addr_2'],
                    'ship_addr_3' => $delivery['delivery_addr_3'],
                    'ship_addr' => $delivery['delivery_addr'],
                    'ship_tel' => $delivery['delivery_tel'],
                    'carrier_id' => 1,//TODO:仮
                    'desired_delivery_date' => $delivery['delivery_date'],
                    'desired_delivery_time' => $delivery['delivery_time'],
                    'goods_tax' => $order['goods_total_tax'],
                    'goods_tax_included' => $order['goods_total_tax_included'],
                    'postage' => $delivery['postage'],
                    'payment_fee' => $delivery['payment_fee'],
                    'packing_charge' => $delivery['packing_charge'],
                    'other_fee' => $delivery['other_fee'],
                    // TODO 複数配送が発生した際には設定金額を調整する必要あり
                    'billing_amount' => $order['total'],
                    'warehouse_id' => $delivery['warehouse_id'],
                    'warehouse_comment' => $delivery['warehouse_comment'],
                    'invoice_comment' => $delivery['invoice_comment']
                    ]
                );

                //出荷明細 受注明細分作成する
                $details = $delivery->orderDetails()->get();
                $detialNo = 1;
                foreach ($details as $detail) {
                    $ship->shipDetails()->create([
                        // 'ship_id' => $detail['ship_id'],
                        'detail_no' => $detialNo,
                        'order_id' => $detail['order_id'],
                        'order_delivery_id' => $detail['order_delivery_id'],
                        'order_delivery_no' => $detail['order_delivery_no'],
                        'order_detail_id' => $detail['id'],
                        'order_detail_no' => $detail['detail_no'],
                        'goods_id' => $detail['goods_id'],
                        'goods_code' => $detail['goods_code'],
                        'name' => $detail['name'],
                        'volume' => $detail['volume'],
                        'jan_code' => $detail['jan_code'],
                        'maker_id' => $detail['maker_id'],
                        'tax_kind' => $detail['tax_kind'],
                        'tax_type' => $detail['tax_type'],
                        'tax_rate' => $detail['tax_rate'],
                        'tax_rounding_type' => $detail['tax_rounding_type'],
                        'unit_price' => $detail['unit_price'],
                        'sale_price' => $detail['sale_price'],
                        'sale_price_tax' => $detail['sale_price_tax'],
                        'sale_price_tax_included' => $detail['sale_price_tax_included'],
                        'discount' => $detail['discount'],
                        'discount_tax' => $detail['discount_tax'],
                        'quantity' => $detail['quantity'],
                        'subtotal' => $detail['subtotal'],
                        'tax' => $detail['tax'],
                        'subtotal_tax_included' => $detail['subtotal_tax_included'],
                        'purchase_unit_price' => $detail['purchase_unit_price'],
                        'purchase_tax_kind' => $detail['purchase_tax_kind'],
                        'purchase_tax_type' => $detail['purchase_tax_type'],
                    ]);
                    $detialNo += 1;
                }
            }
        }


        return $order;
    }

}
