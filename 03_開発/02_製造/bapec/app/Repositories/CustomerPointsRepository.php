<?php
namespace App\Repositories;

use App\Enums\FlagDefine;
use App\Enums\TransferTypeDefine;
use App\Models\CustomerPoint;
use Illuminate\Support\Facades\DB;

/**
 * 顧客ポイント残高リポジトリクラス
 *
 * @package   App\Repositories
 * @version   1.0
 */
class CustomerPointsRepository
{
    use BaseRepository;

    /**
     * 利用するModelクラスを取得する。
     */
    protected function getModel()
    {
        return CustomerPoint::where([]);
    }

    /**
     * ポイント更新
     *
     * @access public
     * @param $customerId 顧客ID
     * @param $pointKind ポイント種類
     * @param $transferType ポイント移動区分
     * @param $point ポイント数
     * @return 更新後
     */
    public function updatePointByCustomerId($customerId, $pointKind, $transferType, $point)
    {
        $sql = '';
        switch($transferType) {
            case TransferTypeDefine::ORDER_ADD:        // 注文による獲得
            case TransferTypeDefine::ORDER_CANCEL_USE: // 注文キャンセルによる利用取り消し
                $sql = DB::raw('point + ' . $point);
                break;
            case TransferTypeDefine::ORDER_USE:        // 注文による利用
            case TransferTypeDefine::ORDER_CANCEL_ADD: // 注文キャンセルによる獲得取り消し
                $sql = DB::raw('point - ' . $point);
                break;
        }

        $updates = [
            // 'point_kind'      => $pointKind,
            // 'expiration_date' => null,
            'point'           => $sql,
        ];

        CustomerPoint::where('customer_id', $customerId)
            ->where('is_deleted', FlagDefine::OFF)
            ->limit(1)
            ->update($updates);

        return $this->findByPkey(['customer_id' => $customerId], false);
    }
}
