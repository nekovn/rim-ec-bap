<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Services\OrdersService;
use App\Enums\FlagDefine;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Enums\OrderStatusTypeDefine;
use App\Helpers\Util\SystemHelper;
use App\Http\Requests\OrderDetailsUpdateRequest;

/**
 * 受注一覧・詳細
 */
class OrdersController extends Controller
{
    public static $SESSION_KEY = 'ordersCondition';

    use SimpleCrudControllerTrait {
        SimpleCrudControllerTrait::update as updateTrait;
    }

    /**
     * コンストラクタ
     *store
     * @access public
     * @param OrderService $orderService ユーザサービス
     */
    public function __construct(OrdersService $ordersService)
    {
        $this->service = $ordersService;
    }

    public  function index(Request $request)
    {
        if (session()->hasOldInput()) { //戻ってきたとき
            $result['isBack'] = FlagDefine::ON;
        } else {
            $result['isBack'] = FlagDefine::OFF;
            //メニューからの時はセッション削除
            $request->session()->forget(OrdersController::$SESSION_KEY);
        }

        $result['selections'] = $this->service->getScreenSelections();

        
        return view($this->getIndexViewFile(), $result);

    }

    /**
     * トップページ表示
     * @return 一覧ページのビューファイル
     */
    protected function getIndexViewFile()
    {
        return 'admin.order-list';
    }
    /**
     * 検索処理のパラメーターを返す。
     *
     * @param Request $request
     * @return void
     */
    protected function getSearchParameter(Request $request)
    {
        $formParams = $request->get('form');
        //受注ステータスは複数指定なので配列にする。clientからは,区切り文字列
        if (isset($formParams['order_status'])) {
            $formParams['order_status'] = explode(',', $formParams['order_status']);
        }
        //検索条件保持　formのinput名に合わせておく
        $sessionVal = [];
        foreach ($formParams as $key => $value) {
            $sessionVal['search-' . $key] = $value;
        };
        $sessionVal += ['page' => $request->get('page')]; 
        $request->session()->put(OrdersController::$SESSION_KEY,$sessionVal);

        return $formParams;
    }

    /**
     * 詳細画面表示
     */
    public function detail($orderId = null) {
        if ($orderId) {
            // 受注・配送情報
            $order = $this->service->getData($orderId);

            //変更可能ステータス
            $attrs = SystemHelper::getCodeAttrs(CodeDefine::ORDER_STATUS);
            $codes = SystemHelper::getCodes(CodeDefine::ORDER_STATUS);
            $attr_selects = [];
            foreach ($attrs as $codev => $attr) {
                if ($order['data']->status == OrderStatusTypeDefine::WAITING) {
                    //  受注ステータスが決済待ち
                    if ($attr[CodeValueDefine::ORDER_STATUS_CHANGEABLE_ATTR2] == FlagDefine::ON
                        || $order['data']->status == $codev) {
                        $attr_selects[$codev] = $codes[$codev];
                    }
                } else {
                    if ($attr[CodeValueDefine::ORDER_STATUS_CHANGEABLE_ATTR] == FlagDefine::ON
                        || $order['data']->status == $codev) {
                        $attr_selects[$codev] = $codes[$codev];
                    }
                }
            }
            // 受注明細取得
            $details = $this->service->selectDeriveries($orderId);
        }
        return view('admin.order-details', [
            'payment_status_list' => $attr_selects,
            'order' => $order['data'],
            'details' => $details,
        ]);
    }
    /**
     * データを更新する。
     *
     * @access public
     * @param OrderDetailsUpdateRequest $request リクエスト
     * @param number $id 主キー
     * @return json
     */
    public function update(OrderDetailsUpdateRequest $request, $id)
    {
        return $this->updateTrait($request, $id);
    }
    /**
     * 戻る
     *
     * @access public
     */
    public function back(Request $request)
    {
        return $this->backRedirect($request, OrdersController::$SESSION_KEY, 'orders.index');
    }
}