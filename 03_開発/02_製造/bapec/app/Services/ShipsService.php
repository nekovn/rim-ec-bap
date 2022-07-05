<?php
namespace App\Services;

use Mail;
use App\Repositories\ShipsRepository;
use App\Services\SimpleCrudServiceTrait;
use App\Aspect\Annotation\Transactional;
use App\Mail\OrderCancelMail;
use App\Mail\ShipMail;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Enums\OrderCancelMailSettingsDefine;
use App\Enums\PaymentMethodDefine;
use App\Enums\PaymentStatusDefine;
use App\Enums\PointKindDefine;
use App\Enums\StatusDefine;
use App\Enums\TransferTypeDefine;
use App\Services\BcrewsApiService;
use App\Services\CustomerPointService;
use App\Models\Customer;
use App\Models\OrderPayment;
use App\Models\Ship;
use App\Helpers\Util\SystemHelper;
use Carbon\Carbon;

/**
 * 出荷関連の処理をまとめたサービスクラス
 *
 * @category  出荷管理
 * @package   App\Services
 * @version   1.0
 */
class ShipsService
{
    use SimpleCrudServiceTrait;

    /**
     * コンストラクタ
     *
     * @access public
     * @param ShipsRepository $shipsRepository
     * @param CustomerPointService $customerPointService
     */
    public function __construct(
        ShipsRepository $shipsRepository,
        BcrewsApiService $bcrewsApiService,
        CustomerPointService $customerPointService
    ){
        $this->repository = $shipsRepository;
        $this->bcrewsApiService = $bcrewsApiService;
        $this->customerPointService = $customerPointService;
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
        $rowData = $this->repository->findByPkey(
            $where,
            false,
            [
                'ships.*',
            ],
            ['shipDetails','order']
        );

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
     */
    public function update($id, array $params)
    {
        $where = ['id' => $id];
        if (isset($params['ol_updated_at'])) {
            $where['updated_at'] = $params['ol_updated_at'];
        }

        //出荷テーブル更新
        $model = $this->repository->update($params, $where, true);

        return $model;
    }

    /**
     * 出荷メール送信
     */
    public function sendShipMail($shipId) {
        Mail::send(new ShipMail($shipId));
    }

    /**
     * 出荷確定を行う
     *
     * @return Model
     * @Transactional()
     */
    public function shipmentConfirmed($shipId, array $params)
    {
        // ----- ステータス：出荷済み
        $params['status'] = StatusDefine::SHUKKA_SUMI;
        // ------ 出荷日
        $params['ship_date'] = isset($params['ship_date']) ? $params['ship_date'] : Carbon::now();

        // 出荷テーブル更新
        $ship = $this->update($shipId, $params);

        // 更新前の受注テーブルのステータス取得
        $beforeOrderStatus = $ship->order->status;
        // 受注テーブル更新
        $ship->order->status = StatusDefine::SHUKKA_ZUMI;
        $ship->order->save();

        // ----- 受注テーブルのステータスが出荷依頼であればポイント付与
        if ($beforeOrderStatus == StatusDefine::SHUKKA_IRAI) {
            // 顧客情報取得
            $customer = Customer::find($ship->customer_id);

            // 店舗会員
            if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null && (int)$ship->order->earned_points > 0) {
                // ----- API通信
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 1, 11, (int)$ship->order->earned_points);
            }

            // アプリ会員
            if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null && (int)$ship->order->earned_points > 0) {
                // ----- EC側ポイント管理
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_ADD,
                    (int)$ship->order->earned_points,
                    $ship->order->id
                );
            }
        }

        // ----- 出荷メール送信
        $this->sendShipMail($shipId);

        return $ship;
    }
    /**
     * キャンセル処理
     *
     * @return Model
     * @Transactional()
     */
    public function cancel($shipId, $params)
    {
        // 更新前の出荷テーブル取得
        $shipBefore = Ship::find($shipId);

        // ----- ステータス：キャンセル
        $params['status'] = StatusDefine::SHUKKA_CANCEL;

        // 出荷テーブル更新
        $ship = $this->update($shipId, $params);
        // 受注テーブル更新
        $ship->order->status = StatusDefine::CANCEL;
        $ship->order->save();

        //顧客情報取得
        $customer = Customer::find($ship->customer_id);

        // 店舗会員
        if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null) {
            // ----- API通信
            if ($shipBefore->status == StatusDefine::SHUKKA_SUMI && (int)$ship->order->earned_points > 0) {
                // ポイント付与取消
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 1, 12, (int)$ship->order->earned_points);
            }

            if ((int)$ship->order->used_point > 0) {
                // ポイント利用取消
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 2, 12, (int)$ship->order->used_point);
            }
        }

        // アプリ会員
        if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null) {
            // ----- EC側ポイント管理
            if ($shipBefore->status == StatusDefine::SHUKKA_SUMI && (int)$ship->order->earned_points > 0) {
                // ポイント付与取消
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_CANCEL_ADD,
                    (int)$ship->order->earned_points,
                    $ship->order->id
                );
            }

            if ((int)$ship->order->used_point > 0) {
                // ポイント利用取消
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_CANCEL_USE,
                    (int)$ship->order->used_point,
                    $ship->order->id
                );
            }
        }

        // 注文キャンセルメール(GMOキャンセル)送信
        $orderpayment = OrderPayment::where('order_id',$ship->order->id)->orderBy('id','desc')->first();
        if ($orderpayment->payment_method != PaymentMethodDefine::CASH_ON_DELIVERY &&
            $orderpayment->payment_method != PaymentMethodDefine::NO_CHARGE) {
            // 決済方法が代金引換以外の場合はメール送信する
            $mailto = SystemHelper::getCodeAttrs(CodeDefine::ORDER_CANCEL_MAIL_SETTING)[OrderCancelMailSettingsDefine::ORDER_CANCEL_MAIL_INFO][CodeValueDefine::ORDER_CANCEL_MAIL_SETTING_ATTR1];
            Mail::to($mailto)->send(new OrderCancelMail($orderpayment));
        }

        return $ship;
    }
    /**
     * 返品処理
     *
     * @return Model
     * @Transactional()
     */
    public function returns($shipId, $params)
    {
        // 更新前の出荷テーブル取得
        $shipBefore = Ship::find($shipId);

        // ----- ステータス：返品
        $params['status'] = StatusDefine::SHUKKA_HENPIN;

        // 出荷テーブル更新
        $ship = $this->update($shipId, $params);
        // 受注テーブル更新
        $ship->order->status = StatusDefine::HENPIN;
        $ship->order->save();

        //顧客情報取得
        $customer = Customer::find($ship->customer_id);

        // 店舗会員
        if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null) {
            // ----- API通信
            if ($shipBefore->status == StatusDefine::SHUKKA_SUMI && (int)$ship->order->earned_points > 0) {
                // ポイント付与取消
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 1, 12, (int)$ship->order->earned_points);
            }

            if ((int)$ship->order->used_point > 0) {
                // ポイント利用取消
                $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 2, 12, (int)$ship->order->used_point);
            }
        }

        // アプリ会員
        if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null) {
            // ----- EC側ポイント管理
            if ($shipBefore->status == StatusDefine::SHUKKA_SUMI && (int)$ship->order->earned_points > 0) {
                // ポイント付与取消
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_CANCEL_ADD,
                    (int)$ship->order->earned_points,
                    $ship->order->id
                );
            }

            if ((int)$ship->order->used_point > 0) {
                // ポイント利用取消
                $this->customerPointService->setAdjustPoint(
                    $customer->id,
                    PointKindDefine::COMMON,
                    TransferTypeDefine::ORDER_CANCEL_USE,
                    (int)$ship->order->used_point,
                    $ship->order->id
                );
            }
        }

        // 注文キャンセルメール(GMOキャンセル)送信
        $orderpayment = OrderPayment::where('order_id',$ship->order->id)->orderBy('id','desc')->first();
        if ($orderpayment->payment_method != PaymentMethodDefine::CASH_ON_DELIVERY &&
            $orderpayment->payment_method != PaymentMethodDefine::NO_CHARGE) {
            // 決済方法が代金引換以外の場合はメール送信する
            $mailto = SystemHelper::getCodeAttrs(CodeDefine::ORDER_CANCEL_MAIL_SETTING)[OrderCancelMailSettingsDefine::ORDER_CANCEL_MAIL_INFO][CodeValueDefine::ORDER_CANCEL_MAIL_SETTING_ATTR1];
            Mail::to($mailto)->send(new OrderCancelMail($orderpayment));
        }

        return $ship;
    }
}