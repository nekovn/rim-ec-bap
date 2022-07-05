<?php
namespace App\Services;

use App\Repositories\OrdersRepository;
use App\Repositories\OrderDeliveriesRepository;
use App\Services\BcrewsApiService;
use App\Services\CustomerPointService;
use App\Services\SimpleCrudServiceTrait;
use App\Aspect\Annotation\Transactional;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Enums\OrderCancelMailSettingsDefine;
use App\Enums\OrderStatusTypeDefine;
use App\Enums\PaymentMethodDefine;
use App\Enums\PaymentStatusDefine;
use App\Enums\PointKindDefine;
use App\Enums\TransferTypeDefine;
use App\Mail\OrderCancelMail;
use App\Models\Customer;
use App\Models\OrderPayment;
use App\Helpers\Util\SystemHelper;
use Mail;

/**
 * 受注管理関連の処理をまとめたサービスクラス
 *
 * @category  受注管理
 * @package   App\Services
 * @version   1.0
 */
class OrdersService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param OrdersRepository $ordersRepository
     * @param BcrewsApiService $bcrewsApiService
     * @param CustomerPointService $customerPointService
     */
    public function __construct(OrdersRepository $ordersRepository
        , OrderDeliveriesRepository $orderDeliveriesRepository
        , BcrewsApiService $bcrewsApiService
        , CustomerPointService $customerPointService){
        $this->repository = $ordersRepository;
        $this->orderDeliveriesRepository = $orderDeliveriesRepository;
        $this->bcrewsApiService = $bcrewsApiService;
        $this->customerPointService = $customerPointService;
    }
    /**
     * 受注配送を取得する。
     *
     * @access public
     * @param string $orderId
     * @return array
     */
    public function selectDeriveries($orderId)
    {
        $select = [
            'id', 'order_id',
            'delivery_surname',
            'delivery_name',
            'delivery_surname_kana',
            'delivery_name_kana',
            'delivery_zip',
            'delivery_addr',
            'delivery_tel',
            'delivery_date',
            'delivery_time',
            'invoice_comment',
            'warehouse_comment'
        ];
        $with = 'orderDetails:order_id,detail_no,goods_code,goods_sku_code,name,volume,jan_code,sale_price_tax_included,quantity,subtotal_tax_included';

        return $this->orderDeliveriesRepository->select($orderId, $select, $with);
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
        $rowData = $this->repository->findByPkey($where, false,
            [
            'orders.id',
            'orders.status',
            'orders.ordered_at',
            'orders.customer_id',
            'orders.zip',
            'orders.tel',
            'orders.email',
            'orders.addr',
            'orders.surname',
            'orders.name',
            'orders.surname_kana',
            'orders.name_kana',
            'orders.customer_rank_id',
            'orders.comment',
            'orders.goods_total_tax_included',
            'orders.postage_total',
            'orders.payment_fee_total',
            'orders.goods_total_tax_included',
            'orders.packing_charge_total',
            'orders.other_fee_total',
            'orders.discount',
            'orders.packing_charge_total',
            'orders.promotion_discount_total',
            'orders.promotion_discount_total',
            'orders.coupon_discount_total',
            'orders.earned_points',
            'orders.used_point',
            'orders.total',
            'orders.remark',
            'orders.payment_method',
            'orders.updated_at',
        ]);
        //withで取得するがorder->settlementではその値が取れない。
        //order->Settlementでは取れるがその都度SQLが走るためここでは保留。何故取れないか不明。
        // ,
        //     ['orderPayment:id,payment_status',
        //     'settlement:display_name']);

        return [
            'data' => $rowData,
        ];
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
        // $lockColumn = SystemHelper::getAppSettingValue('entity.optimistic-lock-column');
        // if ($lockColumn) {
            $where['updated_at'] = $params['ol_updated_at'];
        // }

        //受注テーブル更新
        $model = $this->repository->update($params, $where);

        if ($model->status == OrderStatusTypeDefine::CANCEL) {
            //顧客情報
            $customer = Customer::find($model->customer_id);

            // 店舗会員
            if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null && (int)$model->used_point > 0) {
                // ----- API通信
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 2, 12, (int)$model->used_point);
            }

            // アプリ会員
            if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null && (int)$model->used_point > 0) {
                // ----- EC側ポイント管理
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_CANCEL_USE,
                    (int)$model->used_point,
                    $id
                );
            }
        }

        // 注文キャンセルメール(GMOキャンセル)送信
        $orderpayment = OrderPayment::where('order_id',$model->id)->orderBy('id','desc')->first();
        if ($model->status == OrderStatusTypeDefine::CANCEL &&
            $orderpayment->payment_status == PaymentStatusDefine::COMPLETED &&
            ( $orderpayment->payment_method != PaymentMethodDefine::CASH_ON_DELIVERY &&
              $orderpayment->payment_method != PaymentMethodDefine::NO_CHARGE) ) {
            // 受注ステータスがキャンセル かつ 決済ステータスが決済済み かつ 決済方法が代金引換以外の場合はメール送信する
            $mailto = SystemHelper::getCodeAttrs(CodeDefine::ORDER_CANCEL_MAIL_SETTING)[OrderCancelMailSettingsDefine::ORDER_CANCEL_MAIL_INFO][CodeValueDefine::ORDER_CANCEL_MAIL_SETTING_ATTR1];
            Mail::to($mailto)->send(new OrderCancelMail($orderpayment));
        }

        return $model;
    }
}