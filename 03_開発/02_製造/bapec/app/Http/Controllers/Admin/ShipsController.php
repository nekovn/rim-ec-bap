<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SimpleCrudControllerTrait;
use App\Services\ShipsService;
use App\Enums\FlagDefine;
use App\Models\Order;

/**
 * 出荷一覧・詳細
 */
class ShipsController extends Controller
{
    public static $SESSION_KEY = 'shipsCondition';

    use SimpleCrudControllerTrait ;
    

    /**
     * コンストラクタ
     *
     * @access public
     * @param ShipsService $shipsService ユーザサービス
     */
    public function __construct(ShipsService $shipsService)
    {
        $this->service = $shipsService;
    }

    public  function index(Request $request)
    {
        if (session()->hasOldInput()) { //戻ってきたとき
            $result['isBack'] = FlagDefine::ON;
        } else {
            $result['isBack'] = FlagDefine::OFF;
            //メニューからの時はセッション削除
            $request->session()->forget(ShipsController::$SESSION_KEY);
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
        return 'admin.ship-list';
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
        if (isset($formParams['ship_status'])) {
            $formParams['ship_status'] = explode(',', $formParams['ship_status']);
        }
        //検索条件保持　formのinput名に合わせておく
        $sessionVal = [];
        foreach ($formParams as $key => $value) {
            $sessionVal['search-' . $key] = $value;
        };
        $sessionVal += ['page' => $request->get('page')];
        $request->session()->put(ShipsController::$SESSION_KEY, $sessionVal);

        return $formParams;
    }

    /**
     * 詳細画面表示
     */
    public function detail($shipId = null) {
        if ($shipId) {
            // 出荷情報
            $ship = $this->service->getData($shipId);
            $order = Order::find($ship['data']->order_id);

        }
        return view('admin.ship-details', [
            'ship' => $ship['data'],
            'order' => $order
        ]);
    }

    /**
     * 出荷メール送信
     */
    public function send(Request $request, $shipId)
    {
        $result = $this->service->shipmentConfirmed($shipId, $request->all());
        return response()->json($result);
    }

    /**
     * 戻る
     *
     * @access public
     */
    public function back(Request $request)
    {
        return $this->backRedirect($request, ShipsController::$SESSION_KEY, 'ships.index');
    }

    /**
     * 返品
     */
    public function returns(Request $request, $shipId)
    {
        $result = $this->service->returns($shipId, $request->all());
        return response()->json($result);
    }
    /**
     * キャンセル
     */
    public function cancel(Request $request, $shipId)
    {
        $result = $this->service->cancel($shipId, $request->all());
        return response()->json($result);
    }

}