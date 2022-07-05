<?php declare(strict_types=1);

namespace App\Http\Controllers\Member;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cart\Facades\Cart;
use App\Cart\Facades\OrderContainer;
use App\Enums\CodeDefine;
use App\Enums\DeliveryTypeDefine;
use App\Enums\PaymentMethodDefine;
use App\Enums\PointKindDefine;
use App\Enums\StockManagementTypeDefine;
use App\Enums\TransferTypeDefine;
use App\Exceptions\ApplicationException;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CodeValue;
use App\Models\CustomerOwnerStaff;
use App\Models\DeliveryPrefConditions;
use App\Models\Goods;
use App\Models\GoodsStock;
use App\Models\Settlement;
use App\Models\WarehouseHolidays;
use App\Services\BcrewsApiService;
use App\Services\CustomerPointService;
use App\Services\Member\MembersService;
use App\Services\Member\OrderService;
use App\Http\Requests\OrderConfirmRequest;

/**
 * FR注文フロー
 */
class OrderController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct(
        MembersService $memberService,
        BcrewsApiService $bcrewsApiService,
        OrderService $orderService,
        CustomerPointService $customerPointService)
    {
        $this->memberService = $memberService;
        $this->bcrewsApiService = $bcrewsApiService;
        $this->orderService = $orderService;
        $this->customerPointService = $customerPointService;
    }

    /**
     * 注文入力情報画面を表示
     */
    public function input()
    {
        if (Auth::check()) {
            OrderContainer::instance(Auth::user());
        } else {
            OrderContainer::instance();
        }
        
        if (Cart::countItems() == 0) {
            return redirect('cart');
        }

        // コンテナ取得
        $container = OrderContainer::getContainer();

        // 注文データ取得
        $order = $container->getOrder();

        // 注文届け先データ取得（1件の前提）
        $orderDelivery = $container->getOrderDeliveries()[0];

        //　全てお支払い方法を取得
        $getAllPayments = Settlement::allPaymentMethod();

        // 配送業者都道府県別配送条件を取得する
        $deliveryPrefCond = DeliveryPrefConditions::target($orderDelivery->carrier_id, $orderDelivery->delivery_prefcode);

        // お届け予定日画面表示フラグ（false:非表示、 true:表示)
        $params['deliveryDateDisplayFlg'] = true;

        // 配送業者都道府県別配送条件の日付指定可否チェック
        if ($deliveryPrefCond->can_date_designation == 0) {
            // お届け予定日表示しない
            $params['deliveryDateDisplayFlg'] = false;
        }

        // 倉庫ID
        $warehouseId = 1;
        // カート内商品の最長の出荷リードタイム
        $goodsMaxDeliveryLeadtime = 0;

        if($params['deliveryDateDisplayFlg']) {
            // カート内商品チェック
            $orderDetails = $container->getOrderDetails();
            foreach ($orderDetails as $orderDetail) {
                $goodsId = $orderDetail->goods_id;

                // 在庫管理区分
                $goods = Goods::find($goodsId);
                if (!is_null($goods)) {
                    switch($goods->stock_management_type) {
                        case StockManagementTypeDefine::STOCK: // 在庫品
                            // 在庫数チェック
                            $goodsStock = GoodsStock::target($goodsId, $warehouseId);
                            $quantity = isset($goodsStock->quantity) ? $goodsStock->quantity : 0;
                            if ($quantity == 0) {
                                // お届け予定日表示しない
                                $params['deliveryDateDisplayFlg'] = false;
                            }
                            break;
                        case StockManagementTypeDefine::ORDER: // 受発注品
                        case StockManagementTypeDefine::PLAN:  // 販売計画品
                            // お届け予定日表示しない
                            $params['deliveryDateDisplayFlg'] = false;
                            break;
                    }

                    // お届け予定日画面表示フラグチェック
                    if(!$params['deliveryDateDisplayFlg']) {
                        // 非表示であれば以降のチェックはしない
                        break;
                    }

                    // カート内商品の最長の出荷リードタイム取得
                    if ($goods->delivery_leadtime > $goodsMaxDeliveryLeadtime) {
                        $goodsMaxDeliveryLeadtime = $goods->delivery_leadtime;
                    }
                }
            }
        }

        // お届け予定日の選択肢
        $params['deliveryDate'] = [];
        if($params['deliveryDateDisplayFlg']) {
            // お届け予定日の指定ができる場合はお届け予定日の選択肢を設定

            //--- 開始日設定
            // 祝日取得
            $holidays = $this->getHolidays();
            // 日付取得
            $startDate = Carbon::now();

            // 現在日＋１日（※１）
            $startDate->addDays(1);
            // （※１）その日が、日、月曜、祝日の場合、さらに翌日に繰り上げる（休日でなくなるまで繰り返し）
            for ($i = 0; true; $i++) {
                // 日、月曜、祝日チェック
                if ($startDate->isSunday() ||
                    $startDate->isMonday() ||
                    array_key_exists($startDate->format('Y/n/j'), $holidays)) {
                    // 日、月曜、祝日の場合は翌日に繰り上げる
                    $startDate->addDays(1);
                    continue;
                }

                // 日、月曜、祝日でない場合ループを抜ける
                break;
            }

            // 倉庫休日取得
            $warehouseHolidays = WarehouseHolidays::WarehouseHolidays($warehouseId);
            $warehouseHolidays = json_decode(json_encode($warehouseHolidays), true);
            $warehouseHolidays = array_column($warehouseHolidays, 'holiday');

            // ＋カート内商品の最長の出荷リードタイム（※２）
            $startDate->addDays($goodsMaxDeliveryLeadtime);
            // （※２）その日が、土、日、祝日、倉庫休日の場合、さらに翌日繰り上げる（休日でなくなるまで繰り返し）
            for ($i = 0; true; $i++) {
                // 土、日、祝日、倉庫休日チェック
                if ($startDate->isSaturday() ||
                    $startDate->isSunday() ||
                    array_key_exists($startDate->format('Y/n/j'), $holidays) ||
                    array_search($startDate->format('Y-m-d'), $warehouseHolidays) !== FALSE) {
                    // 土、日、祝日、倉庫休日の場合は翌日に繰り上げる
                    $startDate->addDays(1);
                    continue;
                }

                // 土、日、祝日、倉庫休日でない場合ループを抜ける
                break;
            }

            // ＋配送業者都道府県別配送条件．配送リードタイム日数
            $startDate->addDays($deliveryPrefCond->delivery_leadtime);

            //--- 終了日設定
            // 開始日＋コード値マスタのお届け予定日指定可能範囲日数
            $codeValue = new CodeValue();
            $shippingTerms = $codeValue->getCodeValue(CodeDefine::SHIPPING_TERMS);

            $endDate = Carbon::parse($startDate->format('Y-m-d'));
            $endDate->addDays($shippingTerms[0]->value);

            //--- 選択肢設定
            $params['deliveryDate'][''] = '指定しない';
            $period = CarbonPeriod::since($startDate->format('Y-m-d'))->days(1)->until($endDate->format('Y-m-d'));
            foreach ($period as $key => $date) {
                $params['deliveryDate'][$date->format('Y-m-d')] = $date->format('Y年m月d日');
            }
        }

        // お届け時間画面表示フラグ（false:非表示、 true:表示)
        $params['deliveryTimeDisplayFlg'] = true;
        if ($deliveryPrefCond->can_time_designation == 0) {
            // お届け時間表示しない
            $params['deliveryTimeDisplayFlg'] = false;
        }

        // サロン・スタッフ選択肢は非表示にする
        // //　サロンAPIのデータ
        // $apiData = $this->bcrewsApiService->getSalonstaffs(Auth::user()->bcrews_customer_id);

        // $params['salon'] = isset($apiData['shops']) ? $apiData['shops'] : null;
        $params['salon'] = null;

        return view('member.orderInput', 
        [
            'items' => $params ,
            'order' => $order,
            'orderDelivery' => $orderDelivery,
            'settlements' => $getAllPayments,

        ]);
    }

    /**
     * API 決済方法変更
     */
    public function changePayment(Request $request)
    {
        if ($request->has('payment_id')) {
            $payment_id = $request->input('payment_id');
            $settlement = Settlement::find($payment_id);
            if (!$settlement) {
                return response()->json(['data' => [], 'error' => \Lang::get('messages.E.cant.use.payment.method')]);
            }

            if (Auth::check()) {
                OrderContainer::instance(Auth::user());
            } else {
                OrderContainer::instance();
            }
            try {
                OrderContainer::setPeymentMethod($settlement->code);
            } catch (ApplicationException $e) {
                return response()->json(['data' => [], 'error' => $e->getMessage()]);
            }

            // 注文データ取得
            $order = OrderContainer::order();

            //　金額部のデータ
            $amountInfo = [];
            $amountInfo['paymentMethod'] = $order->payment_method;
            $amountInfo['productTotal']  = $order->goods_total_tax_included; //商品合計（税込）
            $amountInfo['postage'] = $order->postage_total; // 送料
            $amountInfo['paymentFee'] = $order->payment_fee_total; //代引手数料
            $amountInfo['totalTaxIncluded'] = $order->total; // 総合計
            
            //金額情報をJSONレスポンスで返す
            return response()
                ->json(['data' => $amountInfo, 'error' => '']);

        }
    }

    /**
     * API ポイント 変更
     */
    public function changePoint(Request $request)
    {
        if ($request->has('point')) {
            $points = $request->input('point'); //ポイント取得

            if (Auth::check()) {
                OrderContainer::instance(Auth::user());
            } else {
                OrderContainer::instance();
            }

            $remaining = 0;
            try {
                // ポイントをコンテナに反映させる
                $remaining = OrderContainer::setUsePoints($points);
            } catch (ApplicationException $e) {
                return response()->json(['data' => [], 'error' => $e->getMessage()]);
            }

            // 注文データ取得
            $order = OrderContainer::order();

            //　金額部のデータ
            $amountInfo = [];
            $amountInfo['ownedPoint'] = $remaining;// 使用ポイントを差し引いた残ポイント
            $amountInfo['paymentMethod'] = $order->payment_method; // 決済方法
            $amountInfo['productTotal']  = $order->goods_total_tax_included; //商品合計（税込）
            $amountInfo['postage'] = $order->postage_total; // 送料
            $amountInfo['paymentFee'] = $order->payment_fee_total; //代引手数料
            $amountInfo['totalTaxIncluded'] = $order->total; // 総合計
            
            //金額情報をJSONレスポンスで返す
            return response()
                ->json(['data' => $amountInfo, 'error' => '']);
        }
    }

    /**
     * 注文確認画面
     */
    public function confirm(OrderConfirmRequest $request)
    {
        $params = $request->all();

        if (Auth::check()) {
            OrderContainer::instance(Auth::user());
        } else {
            OrderContainer::instance();
        }
        
        OrderContainer::calculate();
        $container = OrderContainer::getContainer();
        $order = $container->getOrder();
        $orderDeliveries = $container->getOrderDeliveries();
        $orderDetails = $container->getOrderDetails();

        $order->settlement = Settlement::find($params['payment']);

        $order->bcrews_salon_id = isset($params['bcrews_salon_id']) ? $params['bcrews_salon_id'] : null;
        $order->bcrews_salon_name = isset($params['bcrews_salon_name']) ? $params['bcrews_salon_name'] : null;
        //$order->bcrews_salon_short_name = $params['bcrews_salon_short_name'];
        $order->bcrews_staff_id = isset($params['bcrews_staff_id']) ? $params['bcrews_staff_id'] : null;
        $order->bcrews_staff_name = isset($params['bcrews_staff_name']) ? $params['bcrews_staff_name'] : null;
        $order->comment = $params['comment'];
        
        $deliveryType = $params['delivery_type'];
        if (DeliveryTypeDefine::OWN == $deliveryType) {
            // 自分宛

        } else {
            // 他人宛は今は無し
        }

        $orderDeliveries[0]->delivery_date = isset($params['delivery_date']) ? $params['delivery_date'] : null;
        $orderDeliveries[0]->delivery_time = isset($params['delivery_time']) ? $params['delivery_time'] : null;

        // 一旦保存
        $container->setOrder($order);
        $container->setOrderDeliveries($orderDeliveries);
        OrderContainer::saveContainer($container);

        return view('member.orderConfirm', [
            'order' => $order,
            'orderDeliveries' => $orderDeliveries,
            'orderDetails' => $orderDetails
        ]);
    }

    /**
     * 受注登録処理
     */
    public function checkout()
    {       
        if (Auth::check()) {
            OrderContainer::instance(Auth::user());
        } else {
            OrderContainer::instance();
        }

        //注文コンテナから注文入力情報を取得する。
        $container = OrderContainer::getContainer();

        //受注を取得
        $order = $container->getOrder();

        //受注配送先を取得
        $orderdelivery = $container->getOrderDeliveries();

        //受注明細を取得
        $orderdetail = $container->getOrderDetails();

        //受注登録処理
        $data = $this->orderService->registerOrder($order,$orderdelivery,$orderdetail);

        //顧客情報
        $customer = Customer::find($order->customer_id);

        // ----- 顧客主担当スタッフ情報取得
        if ($customer->bcrew_customer_id != null) {
            // ----- API通信
            $staffs = $this->bcrewsApiService->getMainStaff($customer->bcrew_customer_id);
            if ($staffs) {
                // 顧客主担当スタッフテーブルに保存
                foreach($staffs as $staff) {
                    $this->insertCustomerOwnerStaffs($data['order_id'], $staff);
                }
            }
        }

        // ----- ポイント利用
        // 店舗会員
        if ($customer->bcrews_customer_id != null && $customer->bcrew_customer_id != null && (int)$order->used_point > 0) {
            // ----- API通信
            $this->bcrewsApiService->setAdjustPoint($customer->bcrew_customer_id, 2, 11, (int)$order->used_point);
        }

        // アプリ会員
        if ($customer->bcrews_customer_id == null && $customer->bcrew_customer_id != null && (int)$order->used_point > 0) {
            // ----- EC側ポイント管理
            $this->customerPointService->setAdjustPoint(
                $customer->id,
                PointKindDefine::COMMON,
                TransferTypeDefine::ORDER_USE,
                (int)$order->used_point,
                $data['order_id']
            );
        }

        //カートセッションを削除
        Cart::destroy();

        //コンテナセッションを削除
        OrderContainer::destroy();


        //支払方法 = 代引きの場合 または 支払方法 = 請求無しの場合
        if( $order->settlement->code == PaymentMethodDefine::CASH_ON_DELIVERY ||
            $order->settlement->code == PaymentMethodDefine::NO_CHARGE)
        {
            //注文完了画面へリダイレクト            
            return redirect('/order/complete')->with(['order_id' => $data['order_id']]);
        }
        else //支払方法 = 代引き以外の場合
        {
            //GMOサイトへ遷移
            return redirect($data['gmo_url']);
        }

        /*
        //ダミーデータ
        $email     = 'sukivietnam19092811@gmail.com';
        $data  = [
            'client'     => (object)[
                'name' => '株式会社B・A・P Beauty Artist Planning',
                'code' => '10',
            ],
            'order'       => (object)[
                'charge_name' => '太郎',
                'email' => 'aa@bb.com',
                'created_at' => DateTime::createFromFormat('d-m-Y H:i:s', '13-09-2021 23:45:52')
            ],
            'sales_in_chage_name' => 'bap'
        ];


        $subject    ='発注先情報';

        Mail::send('mail.orderMail',$data, function($message) use($email,$subject){
            $message->to($email)->subject($subject);
        });

        return view('member.orderCheckout', []);
        */
    }

    /**
     * 顧客主担当スタッフの登録
     */
    public function insertCustomerOwnerStaffs($orderId, $staff)
    {
        $customerOwnerStaff = new CustomerOwnerStaff();

        $customerOwnerStaff->order_id = $orderId;
        $customerOwnerStaff->bcrews_salon_id = $staff['salon_id'];
        $customerOwnerStaff->bcrews_salon_name =  $staff['salon_name'];
        $customerOwnerStaff->bcrews_staff_id =  $staff['staff_id'];
        $customerOwnerStaff->bcrews_staff_name =  $staff['staff_name'];
        $customerOwnerStaff->bcrews_staff_display_order =  $staff['staff_display_order'];

        $customerOwnerStaff->save();
    }

    /**
     * 注文完了
     */
    public function complete(Request $request)
    {
        //注文番号を取得
        $orderid = $request->session()->get('order_id');

        return view('member.orderComplete',['orderid' => $orderid]);
    }

    /**
     * 祝日一覧を取得
     */
    protected function getHolidays()
    {
        $file_path  = storage_path('csv/syukujitsu.csv');

        // ファイルが存在しているかチェックする
        if (($handle = fopen($file_path, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $csv[$data[0]] = $data[1];
            }
            // ヘッダーを削除
            array_shift($csv);
            // 文字コードSJIS→UTF-8変換
            mb_convert_variables('UTF-8','SJIS-win',$csv);
            return $csv;
        }else{
            return [];
        }
    }
}
