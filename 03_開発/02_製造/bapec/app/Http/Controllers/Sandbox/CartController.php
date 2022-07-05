<?php

namespace App\Http\Controllers\Sandbox;

use App\Http\Controllers\Controller;
use App\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use ErrorException;

class CartController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $cartitems = [
            'items' => \Cart::content(), // カートの中身
            'subtotal' => \Cart::subtotal(), // 全体の小計
            'tax' => \Cart::tax(), // 全体の税
            'total' => \Cart::total() // 全合計
        ];

        if (\Auth::check()) {
            \OrderContainer::instance(\Auth::user());
        }
        // \OrderContainer::initialize();

        // \OrderContainer::cartToOrder();

        // $deliv = \OrderContainer::orderDeliveries();

        // $deliv[0]->delivery_prefcode = '40';
        // $deliv[0]->carrier_id = '1';
        // \OrderContainer::setOrderDeliveries($deliv);

        // \OrderContainer::setPeymentMethod('14');

        // \OrderContainer::setUsePoints(20);

        // \OrderContainer::calculate();

        return view('sandbox.cart', ['cartitems' => $cartitems]);
    }


    /**
     * チェックアウトのサンプル
     *
     * @return void
     */
    public function checkout()
    {
        $orderId = 'ORD' . date('YmdHis') . rand();
        $amount = 10000;
        $tax = 10;
        $customerId = '1234';
        $paymentMethods = ['credit']; // ['credit', 'docomo', 'au', 'sb']

        // パラメータ定義
        $param = [
            'geturlparam' => [
                'ShopID' => config('app.gmo_shop_id'),
                'ShopPass' => config('app.gmo_shop_password')
            ],
            'configid' => config('app.gmo_setting_id'),
            'transaction' => [
                'OrderID' => $orderId,
                'Amount' => $amount,
                'Tax' => $tax,
                'PayMethods' => $paymentMethods
            ],
            'credit'=> [
                'JobCd'=> 'CAPTURE',
                'Method' => 1,
                'MemberID' => $customerId
            ],
            'docomo'=> [
                'JobCd' => 'CAPTURE'
            ],
            'au'=> [
                'JobCd' => 'CAPTURE',
                'Commodity' => '商品代金'
            ],
            'sb'=> [
                'JobCd' => 'CAPTURE'
            ]
        ];
        
        // 決済URL取得
        $checkOutUrl = $this->getGmoCheckoutUrl($param);

        return redirect($checkOutUrl);
    }

    /**
     * 決済URLを取得
     *
     * @param array $param
     * @return void
     */
    function getGmoCheckoutUrl($param)
    {
        try{
            $url = config('app.gmo_api_get_payment_link');
            $headers = [
                'Content-Type' => 'application/json;charset=UTF-8'
            ];

            $response = Http::withHeaders($headers)->post($url, $param);
            $response->throw();
            $data = $response->json();
            
            \Log::debug($data);

            return $data['LinkUrl'];
            
        } catch (RequestException $e) {
            throw new ApplicationException('GetLinkplusUrlPayment.json API実行が失敗しました。 : ' . $response->status());
        } catch (ConnectionException $e) {
            throw new ApplicationException();
        } catch (ErrorException $e) {
            throw new ApplicationException();
        }
    }

    /**
     * 決済URLを取得（どちらの書き方でもOKです）
     *
     * @param array $param
     * @return void
     */
    function getGmoCheckoutUrl2($param) {

        $param = json_encode($param);

        // リクエストコネクションの設定
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLOPT_URL, config('app.gmo_api_get_payment_link'));

        // リクエスト送信
        $response = curl_exec($curl);
        $curlinfo = curl_getinfo($curl);
        curl_close($curl);

        // レスポンスチェック
        if($curlinfo['http_code'] != 200){
            throw new ApplicationException('GetLinkplusUrlPayment.json API実行が失敗しました。 : ' . $curlinfo['http_code']);
        }

        // レスポンスのエラーチェック
        parse_str($response, $data);
        if(array_key_exists('ErrCode', $data)){
            throw new ApplicationException('GetLinkplusUrlPayment.json API実行が失敗しました。 : ' . $data['ErrCode']);
        }

        $resJson = json_decode($response, true);

        \Log::debug($resJson);

        return $resJson['LinkUrl'];
    }
}
