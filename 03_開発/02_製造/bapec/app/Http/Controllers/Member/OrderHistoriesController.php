<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Enums\OrderStatusTypeDefine;
use App\Enums\PaymentStatusDefine;
use App\Enums\PointKindDefine;
use App\Enums\TransferTypeDefine;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\BcrewsApiService;
use App\Services\CustomerPointService;
use Illuminate\Support\Facades\Auth;

/**
 * 購入履歴画面コントローラー
 */
class OrderHistoriesController extends Controller
{
    /**
     * コンストラクタ
     *
     * @access public
     * @param BcrewsApiService $bcrewsApiService
     * @param CustomerPointService $customerPointService
     */
    public function __construct(
        BcrewsApiService $bcrewsApiService,
        CustomerPointService $customerPointService
    ) {
        $this->bcrewsApiService = $bcrewsApiService;
        $this->customerPointService = $customerPointService;
    }

    /**
     * 購入履歴一覧
     */
    public function index()
    {
        $orderHistories = Order::with(['orderDetails.goods','orderPayment'])
                                ->where('customer_id', Auth::user()->id)
                                ->orderBy('id', 'desc')
                                ->paginate(10);

        return view('member.orderHistory',['orderHistories' => $orderHistories]);
    }

    /**
     * 購入履歴詳細
     */
    public function detail($id)
    {
        $order = Order::With(['orderDeliveries', 'orderDetails.goods', 'orderPayment'])
                        ->where('id', $id)
                        ->where('customer_id', Auth::user()->id)
                        ->first();

        if (!$order) {
            abort(404);
        }

        return view('member.orderHistoryDetail',['order' => $order]);
    }

    /**
     * 購入履歴詳細 注文キャンセル
     */
    public function cancel($id)
    {
        // 受注テーブルを更新する
        $order = Order::find($id);
        $order->status = OrderStatusTypeDefine::CANCEL;
        $order->save();

        // ----- ポイント利用取消
        //顧客情報
        $customer = Customer::find(\Auth::user()->id);

        // 店舗会員
        if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null && (int)$order->used_point > 0) {
            // ----- API通信
            $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 2, 12, (int)$order->used_point);
        }

        // アプリ会員
        if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null && (int)$order->used_point > 0) {
            // ----- EC側ポイント管理
            $this->customerPointService->setAdjustPoint(
                $customer->id,
                PointKindDefine::COMMON,
                TransferTypeDefine::ORDER_CANCEL_USE,
                (int)$order->used_point,
                $order->id
            );
        }

        return redirect(route('order.history.detail', [
            'id' => $id,
        ]));
    }
}
