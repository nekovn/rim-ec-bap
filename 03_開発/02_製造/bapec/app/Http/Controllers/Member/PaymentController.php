<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderPayment;
use App\Models\PaymentLog;
use App\Services\Member\OrderService;
use App\Enums\GmoPayTypeDefine;

class PaymentController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 決済通知受付
     */
    public function receive(Request $request)
    {
        // ECの決済通知のみ通す
        // （オーダーIDにORDが含まれていない場合は対象外とする）
        if ( strpos($request->OrderID, 'ORD') === false ) {
            return response()->json(0);
        }

        //決済レスポンス.OrderIDから受注決済テーブルを取得
        $orderpayment = OrderPayment::where('transaction_id',$request->OrderID)
                                    ->orderBy('id','desc')
                                    ->first();

        //データチェック
        //正常の場合
        if( ( ( OrderPayment::where('transaction_id',$request->OrderID)->exists() ) &&
              ( Order::where('id',$orderpayment->order_id)->exists()              )    ) &&
            ( ( $request->PayType == GmoPayTypeDefine::CREDIT                     ) ||
              ( $request->PayType == GmoPayTypeDefine::AU                         ) ||
              ( $request->PayType == GmoPayTypeDefine::DOCOMO                     ) ||
              ( $request->PayType == GmoPayTypeDefine::SOFTBANK                   )    )    )
        {
            //受注決済テーブル.受注IDから受注テーブルを取得
            $order = Order::find($orderpayment->order_id);

            //受注配送先テーブルを取得
            $orderdelivery = OrderDelivery::where('order_id',$order->id)
                                          ->orderBy('delivery_no','asc')
                                          ->first();

            //受注明細テーブルを取得
            $orderdetail = DB::table('order_details')
                             ->select(['order_details.*',
                                       'makers.name as maker_name'])
                             ->leftjoin('makers','order_details.maker_id','=','makers.id')
                             ->where('order_id',$order->id)
                             ->where('order_delivery_id',$orderdelivery->id)
                             ->where('order_delivery_no',$orderdelivery->delivery_no)
                             ->get();

            //顧客テーブルを取得
            $customer = Customer::find($order->customer_id);

            //決済ログの登録
            $this->insertPaymentLogs($request,$order,$customer);

            //注文情報の更新
            $this->orderService->updateOrder($request,$order,$orderdelivery,$orderdetail,$customer);

            return response()->json(0);
        }
        else //異常の場合
        {
            return response()->json(1);
        }
    }

    /**
     * 決済ログの登録
     */
    public function insertPaymentLogs($request,$order,$customer)
    {
        $paymentlog = new PaymentLog();

        $paymentlog->payment_process_type = 1;
        $paymentlog->customer_id = $order->customer_id;
        $paymentlog->payment_customer_id = $customer->payment_member_id;
        $paymentlog->order_id = $order->id;
        $paymentlog->transation_id = $request->OrderID;
        $paymentlog->access_id = $request->AccessID;
        $paymentlog->result = $request->Status;
        $paymentlog->process_date = $request->TranDate;
        $paymentlog->err_code = $request->ErrCode;
        $paymentlog->err_info = $request->ErrInfo;
        $paymentlog->response = json_encode($request->all());

        $paymentlog->timestamps = false;

        $paymentlog->save();
    }

    /**
     * GMOからの戻り
     *
     * @return void
     */
    public function back(Request $request) {
        return redirect(route('order.history'));
    }
}
