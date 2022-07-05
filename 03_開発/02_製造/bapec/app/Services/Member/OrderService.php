<?php
namespace App\Services\Member;

use ErrorException;
use Log;
use Mail;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Aspect\Annotation\Transactional;
use App\Enums\CodeDefine;
use App\Enums\OrderStatusTypeDefine;
use App\Enums\PaymentMethodDefine;
use App\Enums\PaymentStatusDefine;
use App\Enums\GmoPayTypeDefine;
use App\Exceptions\ApplicationException;
use App\Mail\OrderMail;
use App\Models\CodeValue;
use App\Models\Customer;
use App\Models\CustomerBehavior;
use App\Models\CustomerRankAssign;
use App\Models\MailSendLog;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderPayment;
use App\Models\Shop;

/**
 * 注文関連の処理をまとめたサービスクラス
 *
 * @package   App\Services
 * @version   1.0
 */
class OrderService
{
    /**
     * 受注登録処理
     * 
     * @access public
     * @param {} $order 受注
     * @param array $orderdelivery 受注配送先
     * @param array $orderdetail 受注明細
     * @return array $data
     * @Transactional
     */
    public function registerOrder($order,$orderdelivery,$orderdetail)
    {
        //受注を登録
        $neworder = $this->insertOrder($order);

        //$orderid = $neworder->id;
        //$ordercustomerid = $neworder->customer_id;

        //受注配送先を登録
        $neworderdelivery = $this->insertOrderDeliveries($orderdelivery,$neworder->id);

        //受注配送先のID,番号を取得
        //$orderdeliveryid = $neworderdelivery->id;
        //$orderdeliveryno = $neworderdelivery->delivery_no;

        //受注明細を登録
        $neworderdetail = $this->insertOrderDetails($orderdetail,$neworder->id,$neworderdelivery->id,$neworderdelivery->delivery_no);

        //支払方法 = 代引きの場合 または 支払方法 = 請求無しの場合
        if( $order->settlement->code == PaymentMethodDefine::CASH_ON_DELIVERY ||
            $order->settlement->code == PaymentMethodDefine::NO_CHARGE)
        {
            //受注決済の登録
            $gmoorderid = null;
            $this->insertOrderPayment($gmoorderid,$neworder->id,$order->settlement->code,$order);

            //顧客行動の登録
            $this->registerCustomerbehavior(Customer::find($neworder->customer_id)->id);

            //注文者宛のメール送信
            Mail::to($neworder->email)
                ->send(new OrderMail($neworder,$neworderdelivery,$neworderdetail));

            //メール送信ログの登録
            $this->insertMailsendlog($neworder,$neworderdelivery,$neworderdetail,$neworder->id,$neworder->customer_id);

            $data = ['order_id' => $neworder->id];
        }
        else //支払方法 = 代引き以外の場合
        {
            //GMO用のオーダーIDを採番する
            $gmoorderid = "ORD-".$neworder->id."-".time();

            //受注決済の取得
            $payCd = CodeValue::where('code',CodeDefine::PAYMENT_METHOD)
                        ->where('key', $order->settlement->code)->value('attr_1');

            // ショップ情報
            $shop = Shop::find(1);

            //パラメータ定義
            $param = [
                'geturlparam' => [
                    'ShopID' => config('app.gmo_shop_id'),
                    'ShopPass' => config('app.gmo_shop_password')
                ],
                'configid' => config('app.gmo_setting_id'),
                'transaction' => [
                    'OrderID' => $gmoorderid,
                    'Amount' => $order->total,
                    'Tax' => 0,
                    'RetUrl' => route('order.history.detail', ['id' => $neworder->id]),
                    'PayMethods' => ['credit', 'docomo', 'au', 'sb'],
                ],
                'credit'=> [
                    'JobCd' => 'CAPTURE',
                    'Method' => 1,
                    'MemberID' => ''
                ],
                'docomo'=> [
                    'JobCd' => 'CAPTURE'
                ],
                // 'au'=> [
                //     'JobCd' => 'CAPTURE',
                //     'Commodity' => '商品代金',
                //     'ServiceName' => $shop->shop_name,
                //     'ServiceTel' => $shop->company_tel
                // ],
                'au'=> [
                    'JobCd' => 'CAPTURE',
                    'Commodity' => '商品代金'
                ],
                'sb'=> [
                    'JobCd' => 'CAPTURE'
                ]
            ];

            //決済遷移用URLを取得
            $gmourl = $this->getGmoCheckoutUrl($param);

            //受注決済の登録
            $this->insertOrderPayment($gmoorderid,$neworder->id,$order->settlement->code,$order,$gmourl);

            $data = [
                'gmo_url' => $gmourl,
                'order_id' => $neworder->id
            ];
        }

        return $data;
    }

    /**
     * 注文情報更新
     * 
     * @access public
     * @param array $request 決済レスポンス
     * @param array $order 受注
     * @param array $orderdelivery 受注配送先
     * @param array $orderdetail 受注明細
     * @param array $customer 顧客
     * @Transactional
     */
    public function updateOrder($request,$order,$orderdelivery,$orderdetail,$customer)
    {
        // 支払い方法取得
        $paymentMethod = $order->payment_method;
        switch ($request->PayType) {
            case  GmoPayTypeDefine::CREDIT:
                $paymentMethod = PaymentMethodDefine::CREDIT;
                break;
            case  GmoPayTypeDefine::AU:
                $paymentMethod = PaymentMethodDefine::AU;
                break;
            case  GmoPayTypeDefine::DOCOMO:
                $paymentMethod = PaymentMethodDefine::DOCOMO;
                break;
            case  GmoPayTypeDefine::SOFTBANK:
                $paymentMethod = PaymentMethodDefine::SOFTBANK;
            break;
        }

        //パラメータ.Statusが「CAPTURE」「SALES」の場合
        if( ( $request->Status == 'CAPTURE' ) ||
            ( $request->Status == 'SALES'   ) )
        {
            //受注の更新
            $order->payment_method = $paymentMethod;
            $this->updateOrders($order);

            //受注決済の更新
            $this->updateOrderPayments($request,$paymentMethod);

            //顧客行動の登録
            $this->registerCustomerbehavior($customer->id);

            //注文者宛のメール送信
            Mail::to($order->email)
                ->send(new OrderMail($order,$orderdelivery,$orderdetail));

            //メール送信ログの登録
            $this->insertMailsendlog($order,$orderdelivery,$orderdetail,$order->id,$order->customer_id);
        }
        else //上記以外の場合
        {
            //受注決済の更新
            $this->updateOrderPayments($request,$paymentMethod);
        }
    }

    /**
     * 決済URLを取得
     *
     * @param array $param
     */
    public function getGmoCheckoutUrl($param)
    {
        try{
            $url = config('app.gmo_api_get_payment_link');
            $headers = [
                'Content-Type' => 'application/json;charset=UTF-8'
            ];

            $response = Http::withHeaders($headers)->post($url, $param);
            $response->throw();
            $data = $response->json();
            
            Log::debug($data);

            return $data['LinkUrl'];
            
        } catch (RequestException $e) {
            Log::debug($param);
            throw new ApplicationException('GetLinkplusUrlPayment.json API実行が失敗しました。 : ' . $response->status());
            return redirect()->route('order.input')->with('checkout_error', \Lang::get('E.paymenturl.get.fail'));
        } catch (ConnectionException $e) {
            throw new ApplicationException();
            return redirect()->route('order.input')->with('checkout_error', \Lang::get('E.paymenturl.get.fail'));
        } catch (ErrorException $e) {
            throw new ApplicationException();
            return redirect()->route('order.input')->with('checkout_error', \Lang::get('E.paymenturl.get.fail'));
        }
    }

    /**
     * 受注の登録
     */
    public function insertOrder($param)
    {
        $order = new Order();

        $order->ordered_at = Carbon::now()->format('Y/m/d H:i:s');
        //支払方法 = 代引きの場合 または 支払方法 = 請求無しの場合
        if( $param->settlement->code == PaymentMethodDefine::CASH_ON_DELIVERY ||
            $param->settlement->code == PaymentMethodDefine::NO_CHARGE)
        {
            $order->status = OrderStatusTypeDefine::RECEPTION;
        }
        else
        {
            $order->status = OrderStatusTypeDefine::WAITING;
        }
        $order->customer_id = $param->customer_id;
        $order->surname = $param->surname;
        $order->name = $param->name;
        $order->surname_kana = $param->surname_kana;
        $order->name_kana = $param->name_kana;
        $order->zip = $param->zip;
        $order->prefcode = $param->prefcode;
        $order->addr_1 = $param->addr_1;
        $order->addr_2 = $param->addr_2;
        $order->addr_3 = $param->addr_3;
        $order->addr = $param->addr;
        $order->tel = $param->tel;
        $order->email = $param->email;
        $order->payment_method = $param->payment_method;
        $order->comment = $param->comment;
        $order->goods_total_tax = $param->goods_total_tax;
        $order->goods_total_tax_included = $param->goods_total_tax_included;
        $order->postage_total = $param->postage_total;
        $order->payment_fee_total = $param->payment_fee_total;
        $order->packing_charge_total = $param->packing_charge_total;
        $order->other_fee_total = $param->other_fee_total;
        $order->discount = $param->discount;
        $order->promotion_discount_total = $param->promotion_discount_total;
        $order->coupon_discount_total = $param->coupon_discount_total;
        $order->earned_points = $param->earned_points;
        $order->used_point = $param->used_point;
        $order->point_amount = $param->point_amount;
        $order->point_conversion_rate = $param->point_conversion_rate;
        $order->total = $param->total;
        $order->customer_rank_id = CustomerRankAssign::find($param->customer_id)->customer_rank_id;
        $order->bcrews_salon_id = $param->bcrews_salon_id;
        $order->bcrews_salon_name = $param->bcrews_salon_name;
        $order->bcrews_staff_id = $param->bcrews_staff_id;
        $order->bcrews_staff_name = $param->bcrews_staff_name;
        
        $order->save();

        $neworder = $order->where('customer_id',$param->customer_id)
                          ->where('ordered_at',$order->ordered_at)
                          ->orderBy('id','desc')
                          ->first();
        
        return $neworder;
    }

    /**
     * 受注の更新
     */
    public function updateOrders($param)
    {
        $order = $param;

        $order->status = OrderStatusTypeDefine::RECEPTION;

        $order->save();
    }

    /**
     * 受注配送先の登録
     */
    public function insertOrderDeliveries($param,$orderid)
    {        
        $delivery_no = 0;

        $orderdelivery = [];

        foreach($param as $insertorderdelivery)
        {
            $orderdelivery[] = [
                'order_id' => $orderid,
                'delivery_no' => $delivery_no + 1,
                'delivery_type' => $insertorderdelivery->delivery_type,
                'client_surname' => $insertorderdelivery->client_surname,
                'client_name' => $insertorderdelivery->client_name,
                'client_surname_kana' => $insertorderdelivery->client_surname_kana,
                'client_name_kana' => $insertorderdelivery->client_name_kana,
                'client_zip' => $insertorderdelivery->client_zip,
                'client_prefcode' => $insertorderdelivery->client_prefcode,
                'client_addr_1' => $insertorderdelivery->client_addr_1,
                'client_addr_2' => $insertorderdelivery->client_addr_2,
                'client_addr_3' => $insertorderdelivery->client_addr_3,
                'client_addr' => $insertorderdelivery->client_addr,
                'client_tel' => $insertorderdelivery->client_tel,
                'delivery_surname' => $insertorderdelivery->delivery_surname,
                'delivery_name' => $insertorderdelivery->delivery_name,
                'delivery_surname_kana' => $insertorderdelivery->delivery_surname_kana,
                'delivery_name_kana' => $insertorderdelivery->delivery_name_kana,
                'delivery_zip' => $insertorderdelivery->delivery_zip,
                'delivery_prefcode' => $insertorderdelivery->delivery_prefcode,
                'delivery_addr_1' => $insertorderdelivery->delivery_addr_1,
                'delivery_addr_2' => $insertorderdelivery->delivery_addr_2,
                'delivery_addr_3' => $insertorderdelivery->delivery_addr_3,
                'delivery_addr' => $insertorderdelivery->delivery_addr,
                'delivery_tel' => $insertorderdelivery->delivery_tel,
                'carrier_id' => $insertorderdelivery->carrier_id,
                'delivery_date' => $insertorderdelivery->delivery_date,
                'delivery_time' => $insertorderdelivery->delivery_time,
                'is_gift' => $insertorderdelivery->is_gift ? $insertorderdelivery->is_gift : '0',
                'postage' => $insertorderdelivery->postage,
                'payment_fee' => $insertorderdelivery->payment_fee,
                'packing_charge' => $insertorderdelivery->packing_charge,
                'other_fee' => $insertorderdelivery->other_fee,
                'warehouse_comment' => $insertorderdelivery->warehouse_comment,
                'invoice_comment' => $insertorderdelivery->invoice_comment,
                'is_bundled_delivery_slip' => $insertorderdelivery->is_bundled_delivery_slip ? $insertorderdelivery->is_bundled_delivery_slip : '1',
                'is_print_amount_delivery_slip' => $insertorderdelivery->is_print_amount_delivery_slip ? $insertorderdelivery->is_print_amount_delivery_slip : '0',
                'receiving_method' => $insertorderdelivery->receiving_method
            ];
        }

        DB::table('order_deliveries')->insert($orderdelivery);

        $neworderdelivery = OrderDelivery::where('order_id','=',$orderid)
                                         ->orderBy('delivery_no','asc')
                                         ->first();

        return $neworderdelivery;
    }

    /**
     * 受注明細の登録
     */
    public function insertOrderDetails($param,$orderid,$orderdeliveryid,$orderdeliveryno)
    {
        $orderdetail = [];

        foreach($param as $insertorderdetail)
        {
            $orderdetail[] = [
                'order_id' => $orderid,
                'detail_no' => $insertorderdetail->detail_no,
                'order_delivery_id' => $orderdeliveryid,
                'order_delivery_no' => $orderdeliveryno,
                'goods_id' => $insertorderdetail->goods_id,
                'goods_code' => $insertorderdetail->goods_code,
                'goods_sku_code' => $insertorderdetail->goods_sku_code,
                'name' => $insertorderdetail->name,
                'volume' => $insertorderdetail->volume,
                'jan_code' => $insertorderdetail->jan_code,
                'maker_id' => $insertorderdetail->maker_id,
                'warehouse_id' => $insertorderdetail->warehouse_id,
                'supplier_id' => $insertorderdetail->supplier_id,
                'tax_kind' => $insertorderdetail->tax_kind,
                'tax_type' => $insertorderdetail->tax_type,
                'tax_rate' => $insertorderdetail->tax_rate,
                'tax_rounding_type' => $insertorderdetail->tax_rounding_type,
                'unit_price' => $insertorderdetail->unit_price,
                'sale_price' => $insertorderdetail->sale_price,
                'sale_price_tax' => $insertorderdetail->sale_price_tax,
                'sale_price_tax_included' => $insertorderdetail->sale_price_tax_included,
                'discount' => $insertorderdetail->discount,
                'discount_tax' => $insertorderdetail->discount_tax,
                'quantity' => $insertorderdetail->quantity,
                'subtotal' => $insertorderdetail->subtotal,
                'tax' => $insertorderdetail->tax,
                'subtotal_tax_included' => $insertorderdetail->subtotal_tax_included,
                'purchase_unit_price' => $insertorderdetail->purchase_unit_price ? $insertorderdetail->purchase_unit_price : '0.00',
                'purchase_tax_kind' => $insertorderdetail->purchase_tax_kind,
                'purchase_tax_type' => $insertorderdetail->purchase_tax_type
            ];
        }

        DB::table('order_details')->insert($orderdetail);

        $neworderdetail = DB::table('order_details')
                            ->select(['order_details.*',
                                      'makers.name as maker_name'])
                            ->leftjoin('makers','order_details.maker_id','=','makers.id')
                            ->where('order_id',$orderid)
                            ->where('order_delivery_id',$orderdeliveryid)
                            ->where('order_delivery_no',$orderdeliveryno)
                            ->get();

        return $neworderdetail;
    }

    /**
     * 受注決済の登録
     */
    public function insertOrderPayment($gmoorderid,$orderid,$paymentcode,$order,$gmourl = null)
    {
        $orderpayment = new OrderPayment();

        $orderpayment->transaction_id = $gmoorderid;
        $orderpayment->order_id = $orderid;
        $orderpayment->payment_method = $paymentcode;
        if( $paymentcode == PaymentMethodDefine::CASH_ON_DELIVERY ||
            $paymentcode == PaymentMethodDefine::NO_CHARGE)
        {
            $orderpayment->payment_status = PaymentStatusDefine::COMPLETED;
        }
        else
        {
            $orderpayment->payment_status = PaymentStatusDefine::SETTLEMENT_WAITING;
        }
        $orderpayment->payment_amount = $order->total;
        $orderpayment->payment_fee = $order->payment_fee_total;
        $orderpayment->payment_url = $gmourl;

        $orderpayment->save();

    }

    /**
     * 受注決済の更新
     */
    public function updateOrderPayments($request,$paymentMethod)
    {
        $orderpayments = OrderPayment::where('transaction_id',$request->OrderID)
                                     ->first();

        $orderpayments->payment_method = $paymentMethod;

        if( ( $request->Status == 'CAPTURE' ) ||
            ( $request->Status == 'SALES'   ) )
        {
            $orderpayments->payment_status = PaymentStatusDefine::COMPLETED;
        }
        else
        {
            switch($request->Status){
                case 'REQSUCCESS':
                case 'AUTHPROCESS':
                    $orderpayments->payment_status = PaymentStatusDefine::PROCESS;
                    break;
                
                case 'AUTH':
                    $orderpayments->payment_status = PaymentStatusDefine::PAYMENT_WAITING;
                    break;

                case 'PAYFAIL':
                    $orderpayments->payment_status = PaymentStatusDefine::FAILURE;
                    break;

                case 'EXPIRED':
                    $orderpayments->payment_status = PaymentStatusDefine::EXPIRED;
                    break;

                case 'VOID':
                case 'CANCEL':
                    $orderpayments->payment_status = PaymentStatusDefine::CANCEL;
                    break;

                case 'RETURN':
                case 'RETURNX':
                    $orderpayments->payment_status = PaymentStatusDefine::RETURN;
                    break;

                default :
                    $orderpayments->payment_status = PaymentStatusDefine::SETTLEMENT_WAITING;
                    break;                
            }
        }

        $orderpayments->save();
    }

    /**
     * 顧客行動の登録
     */
    public function registerCustomerbehavior($customerid)
    {
        //レコードがある場合
        if(DB::table('customer_behaviors')->where('customer_id',$customerid)->exists())
        {
            $customerbehavior = CustomerBehavior::where('customer_id',$customerid)
                                                ->first();            
        }
        else //レコードがない場合
        {
            $customerbehavior = new CustomerBehavior();
            $customerbehavior->customer_id = $customerid;
            $customerbehavior->purchases_count = 0;
        }

        $customerbehavior->last_orderd_at = Carbon::now()->format('Y/m/d H:i:s');
        $customerbehavior->purchases_count = $customerbehavior->purchases_count + 1;
        $customerbehavior->timestamps = false;

        $customerbehavior->save();
    }

    /**
     * メール送信ログの登録
     */
    public function insertMailsendlog($order,$orderdelivery,$orderdetail,$orderid,$ordercustomerid)
    {
        $mailsendlogs = new MailSendLog();

        $mailsendlogs->mail_type = 1;
        $mailsendlogs->customer_id = $ordercustomerid;
        $mailsendlogs->transation_id = $orderid;
        $mailsendlogs->send_at = Carbon::now()->format('Y/m/d H:i:s');
        $mailsendlogs->to = Customer::find($ordercustomerid)->email;
        $mailsendlogs->from = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)
                                       ->where('key',1)
                                       ->value('attr_2');
        $mailsendlogs->title = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)
                                        ->where('key',3)
                                        ->value('attr_1');
        $mailsendlogs->content = view('mail.orderMail',
                                      ['order' => $order,
                                       'orderdelivery' => $orderdelivery,
                                       'orderdetail' => $orderdetail,
                                       'ordered_at' => date("Y/m/d H:i:s",strtotime($order->ordered_at)),
                                       'discountamount' => $order->discount + $order->promotion_discount_total + $order->coupon_discount_total,
                                       'commission' => $order->payment_fee_total + $order->packing_charge_total + $order->other_fee_total,
                                       'mailinquiry' => CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',2)->value('attr_1')])
                                 ->render();
        $mailsendlogs->timestamps = false;

        $mailsendlogs->save();
    }
}