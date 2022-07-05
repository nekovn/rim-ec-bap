<?php
namespace App\Repositories;

use App\Enums\CodeDefine;
use App\Enums\FlagDefine;
use App\Enums\SagawaOutputDefine;
use App\Enums\StatusDefine;
use App\Enums\VerticalPackingTypeDefine;
use App\Models\Ship;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * 出荷管理関連の処理をまとめたリポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class ShipsRepository
{
    use BaseRepository;
    use SimpleCrudRepositoryTrait;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return Ship::where([]);
    }

    /**
     * クエリーを構築する。
     * @param array $conditions 検索条件
     * @return query
     */
    protected function getQueryByConditions(array $param)
    {

        $WHERE = [
            'likeb' => ['slip_no' => 'slip_no'],
            'raw' => ['client_name' =>
            "concat(client_name, client_surname, client_surname_kana,client_name_kana) LIKE concat('%', ?, '%')"],
            'eq' => ['ship_status' => 'ships.status']
        ];

        $query = $this->getModel()->select(
            'ships.*'
        )
        ->selectRaw('concat(orders.surname, orders.name) as name_full');
        // order
        $query->leftJoin('orders', function ($join) {
            $join->on('ships.order_id', '=', 'orders.id');
        });

        $query = $this->createQueryByConditionsWhere($query, $param, $WHERE);

        //出荷指示日
        if ((isset($param['ship_direct_date_from']))) {
            $updateFrom = (new Carbon($param['ship_direct_date_from']))->format('Ymd');
            $query->whereRaw("date_format(ships.ship_direct_date,  '%Y%m%d') >= ". $updateFrom);
        }
        if ((isset($param['ship_direct_date_to']))) {
            $updateTo = (new Carbon($param['ship_direct_date_to']))->format('Ymd');
            $query->whereRaw("date_format(ships.ship_direct_date,  '%Y%m%d') <= " . $updateTo);
        }

        return $query;
    }

    /**
     * 佐川連携ファイル出力用の出荷指示情報を取得する
     *
     * @return 取得結果
     */
    public function getShipsInstructionForSagawa()
    {
        $query = "
            select
                '" . SagawaOutputDefine::OUTPUT_VALUE_CUSTOMER_ID . "'  as customer_id
                , '" . SagawaOutputDefine::OUTPUT_VALUE_STORE_ID . "' as store_id
                , '" . SagawaOutputDefine::OUTPUT_VALUE_STORE_NAME . "' as store_name
                , ships.id as mall_accept_number
                , ships.order_id as slip_number
                , carriers.cooperation_code as transport_cd
                , replace(replace(replace(orders.comment, '\r\n', ''), '\r', ''), '\n', '') as delivery_list_remarks
                , '' as settlement_method
                , '' as shipment_plan_date
                , DATE_FORMAT(ships.desired_delivery_date, '%Y%m%d') as delivery_order_date
                , delivery_order_time.attr_1 as delivery_order_time
                , '' as article_1
                , '' as article_2
                , '' as article_3
                , '" . SagawaOutputDefine::OUTPUT_VALUE_DELIVERY_MONEY_PRINT_FLG . "' as delivery_money_print_flg
                , '' as process_div_1
                , '' as process_type_1
                , '' as process_message_1
                , '' as process_div_2
                , '' as process_type_2
                , '' as process_message_2
                , '' as process_div_3
                , '' as process_type_3
                , '' as process_message_3
                , '' as process_div_4
                , '' as process_type_4
                , '' as process_message_4
                , '' as process_div_5
                , '' as process_type_5
                , '' as process_message_5
                , REPLACE(FORMAT(ships.goods_tax_included - ships.goods_tax, 0), ',' ,'') as item_detail_total_money
                , REPLACE(FORMAT(ships.goods_tax, 0), ',' ,'') as tax
                , REPLACE(FORMAT(ships.postage, 0), ',' ,'') as carriage
                , REPLACE(FORMAT(ships.payment_fee + ships.packing_charge + ships.other_fee, 0), ',' ,'') as collect_on_delivery_fee
                , REPLACE(FORMAT(-(ships.point_amount), 0), ',' ,'') as use_point
                , REPLACE(FORMAT(-(ships.discount + ships.promotion_discount + ships.coupon_discount), 0), ',' ,'') as discount
                , REPLACE(FORMAT(ships.billing_amount, 0), ',' ,'') as total_money
                , ships.client_surname as purchaser_name_1
                , ships.client_name as purchaser_name_2
                , LEFT(ships.client_zip, 3) as purchaser_post_1
                , RIGHT(ships.client_zip, 4) as purchaser_post_2
                , purchaser_pref.value as purchaser_address_1
                , CONCAT(IFNULL(ships.client_addr_1, ''), IFNULL(ships.client_addr_2, ''),
                         IFNULL(ships.client_addr_3, '')) as purchaser_address_2
                , CONCAT(IFNULL(ships.client_addr_1, ''), IFNULL(ships.client_addr_2, ''),
                         IFNULL(ships.client_addr_3, '')) as purchaser_address_3
                , CONCAT(IFNULL(ships.client_addr_1, ''), IFNULL(ships.client_addr_2, ''),
                         IFNULL(ships.client_addr_3, '')) as purchaser_address_4
                , ships.client_tel as purchaser_tel
                , ships.ship_surname as ship_name_1
                , ships.ship_name as ship_name_2
                , LEFT (ships.ship_zip, 3) as ship_post_1
                , RIGHT (ships.ship_zip, 4) as ship_post_2
                , ship_pref.value as ship_address_1
                , CONCAT(IFNULL(ships.ship_addr_1, ''), IFNULL(ships.ship_addr_2, ''),
                         IFNULL(ships.ship_addr_3, '')) as ship_address_2
                , CONCAT(IFNULL(ships.ship_addr_1, ''), IFNULL(ships.ship_addr_2, ''),
                         IFNULL(ships.ship_addr_3, '')) as ship_address_3
                , CONCAT(IFNULL(ships.ship_addr_1, ''), IFNULL(ships.ship_addr_2, ''),
                         IFNULL(ships.ship_addr_3, '')) as ship_address_4
                , ships.ship_tel as ship_tel
                , '' as ship_opp_daytime_tel
                , ship_details.detail_no as line_number
                , ship_details.goods_code as product_code
                , ship_details.name as product_name
                , REPLACE(FORMAT(ship_details.sale_price_tax_included, 0), ',' ,'') as unit_price
                , REPLACE(FORMAT(ship_details.quantity, 0), ',' ,'') as quantity
                , REPLACE(FORMAT(ship_details.subtotal + ship_details.tax, 0), ',' ,'') as item_total_amount
                , CASE
                    WHEN ships.is_gift = '" . SagawaOutputDefine::SHIPS_GIFT_FLG . "'
                      THEN '" . SagawaOutputDefine::OUTPUT_VALUE_GIFT_FLG . "'
                      ELSE ''
                  END as gift_category
                , '' as shipping_code_1
                , '' as spare_3
                , '' as spare_4
                , '' as spare_5
                , '' as spare_6
                , '' as storage_location_code
                , CASE
                    WHEN ifnull(goods.is_vertical_packing,'" . VerticalPackingTypeDefine::UN_NEEDED . "') = '" . VerticalPackingTypeDefine::NEED . "'
                      THEN '" . SagawaOutputDefine::OUTPUT_VERTICAL_PACKING_FLG . "'
                      ELSE ''
                  END as spare_8
                , '' as spare_9
                , '' as spare_10
                , '' as spare_11
                , '' as spare_12
            from
                ships
                left join ship_details
                  on ship_details.ship_id = ships.id
                  and ship_details.is_deleted =  " . FlagDefine::OFF . "
                left join goods
                  on goods.code = ship_details.goods_code
                  -- and goods.is_deleted =  " . FlagDefine::OFF . "
                left join code_values purchaser_pref
                  on purchaser_pref.key = ships.client_prefcode
                  and purchaser_pref.code = '" . CodeDefine::PREF_CD . "'
                  and purchaser_pref.is_deleted = " . FlagDefine::OFF . "
                left join code_values ship_pref
                  on ship_pref.key = ships.ship_prefcode
                  and ship_pref.code = '" . CodeDefine::PREF_CD . "'
                  and ship_pref.is_deleted = " . FlagDefine::OFF . "
                left join orders
                  on orders.id = ships.order_id
                  and orders.is_deleted = " . FlagDefine::OFF . "
                left join code_values delivery_order_time
                  on delivery_order_time.key = ships.desired_delivery_time
                  and delivery_order_time.code = " . CodeDefine::DELIVERY_TIME . "
                left join carriers
                  on carriers.id = ships.carrier_id
                  and carriers.is_deleted = " . FlagDefine::OFF . "
            where
                ships.status = '" . StatusDefine::SHUKKA_MACHI . "'
                and ships.is_deleted = " . FlagDefine::OFF;

        return DB::select($query);
    }

    /**
     * 佐川連携の出荷指示ファイル出力対象データを更新する
     *
     * @param array $target 出力対象IDリスト
     */
    public function updateShipsInstructionForSagawa($target)
    {
      $params = [
          'status' => StatusDefine::SHUKKA_RENKEI,
          'ship_direct_date' => Carbon::now()->format('Y/m/d')
      ];

      $this->getModel()
           ->where('status', StatusDefine::SHUKKA_MACHI)
           ->whereIn('id', $target)
           ->update($params);
    }
}
