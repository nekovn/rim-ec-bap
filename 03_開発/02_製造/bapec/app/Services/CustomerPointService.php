<?php

namespace App\Services;

use App\Aspect\Annotation\Transactional;
use App\Enums\FlagDefine;
use App\Enums\TransferTypeDefine;
use App\Exceptions\ApplicationException;
use App\Models\CustomerPoint;
use App\Models\CustomerPointLog;
use App\Repositories\CustomerPointLogsRepository;
use App\Repositories\CustomerPointsRepository;
use Carbon\Carbon;
use Lang;

/**
 * 顧客ポイント関連の処理をまとめたサービスクラス
 *
 * @package   App\Services\Util
 * @version   1.0
 */
class CustomerPointService
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param CustomerPointLogsRepository $customerPointLogsRepository 顧客ポイント履歴
     * @param CustomerPointsRepository $customerPointsRepository 顧客ポイント残高
     */
    public function __construct(
        CustomerPointLogsRepository $customerPointLogsRepository,
        CustomerPointsRepository $customerPointsRepository
    ) {
        $this->customerPointLogsRepository = $customerPointLogsRepository;
        $this->customerPointsRepository = $customerPointsRepository;
    }

    /**
     * ポイントの付与・使用を行う
     *
     * @access public
     * @param string $customerId 顧客ID
     * @param string $pointKind ポイント種類
     * @param string $transferType ポイント移動区分
     * @param int $point ポイント数
     * @param string $transactionId トランザクションID
     * @Transactional()
     */
    public function setAdjustPoint($customerId, $pointKind, $transferType, $point, $transactionId)
    {
        // 顧客ポイント残高を取得
        $customerPoint = CustomerPoint::target($customerId);
        if (!isset($customerPoint->point)) {
            // 該当なし
            if ($transferType == TransferTypeDefine::ORDER_ADD ||
                $transferType == TransferTypeDefine::ORDER_CANCEL_USE ) {
                // 顧客ポイント残高登録
                $customerPointParams = [
                    'customer_id'     => $customerId,
                    'point_kind'      => $pointKind,
                    'expiration_date' => null,
                    'point'           => 0
                ];
                $customerPoint = $this->insertCustomerPoints($customerPointParams);
            } else if($transferType == TransferTypeDefine::ORDER_CANCEL_ADD) {
                return;
            } else {
                // それ以外エラー
                throw new ApplicationException(Lang::get('messages.E.targetdata.notfound'));
            }
        }

        // 保有ポイントがマイナスになるかのチェック
        // （ポイント移動区分からチェックを行う）
        switch($transferType) {
            case TransferTypeDefine::ORDER_USE:        // 注文による利用
            case TransferTypeDefine::ORDER_CANCEL_ADD: // 注文キャンセルによる獲得取り消し
                $chkAfterPoint = intval($customerPoint->point) - $point;
                if($chkAfterPoint < 0) {
                    if($transferType == TransferTypeDefine::ORDER_CANCEL_ADD) {
                        $point = intval($customerPoint->point);
                    } else {
                        throw new ApplicationException(Lang::get('messages.E.insufficient.points'));
                    }
                }
                break;
            default:
                // 保有ポイントが加算される場合はチェック対象外
                break;
        }

        // ポイント残高更新
        $customerPoint = $this->customerPointsRepository->updatePointByCustomerId($customerId, $pointKind, $transferType, $point);

        // 顧客ポイント履歴登録
        $customerPointLogsParams = [
            'customer_id'          => $customerId,
            'trans_at'             => Carbon::now(),
            'point_kind'           => $pointKind,
            'transfer_type'        => $transferType,
            'transfer_reason'      => '',
            'point'                => $point,
            'expiration_date'      => null,
            'transaction_id'       => $transactionId,
            'adjust_reason'        => '',
            'after_point'          => $customerPoint->point,
            'is_fixed'             => FlagDefine::ON,
        ];
        $this->insertCustomerPointLogs($customerPointLogsParams);
    }

    /**
     * 顧客ポイント残高の登録
     */
    private function insertCustomerPoints($params)
    {
        $customerPoint = new CustomerPoint();

        $customerPoint->customer_id = $params['customer_id'];
        $customerPoint->point_kind = $params['point_kind'];
        $customerPoint->expiration_date = $params['expiration_date'];
        $customerPoint->point = $params['point'];

        $customerPoint->save();

        return CustomerPoint::target($params['customer_id']);
    }

    /**
     * 顧客ポイント履歴の登録
     */
    private function insertCustomerPointLogs($params)
    {
        $customerPointLog = new CustomerPointLog();

        $customerPointLog->customer_id = $params['customer_id'];
        $customerPointLog->trans_at = $params['trans_at'];
        $customerPointLog->point_kind = $params['point_kind'];
        $customerPointLog->transfer_type = $params['transfer_type'];
        $customerPointLog->transfer_reason= $params['transfer_reason'];
        $customerPointLog->point = $params['point'];
        $customerPointLog->expiration_date = $params['expiration_date'];
        $customerPointLog->transaction_id = $params['transaction_id'];
        $customerPointLog->adjust_reason = $params['adjust_reason'];
        $customerPointLog->after_point = $params['after_point'];
        $customerPointLog->is_fixed = $params['is_fixed'];

        $customerPointLog->save();
    }


}
