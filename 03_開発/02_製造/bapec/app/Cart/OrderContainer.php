<?php

namespace App\Cart;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Session\SessionManager;
use Illuminate\Database\Eloquent\Collection;
use App\Cart\OrderWrapper;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderDetail;
use App\Models\Settlement;
use App\Cart\Exceptions\InsufficientPointsException;
use App\Cart\Exceptions\UnknownPaymentMethodException;
use App\Cart\Exceptions\UnusableRangePaymentException;
use App\Cart\Calculation\GoodsPriceCalculator;
use App\Cart\Calculation\OrderPriceCalculator;
use App\Cart\Calculation\PaymentFeeCalculator;
use App\Cart\Calculation\PostageCalculator;
use App\Cart\Calculation\PointCalculator;
use App\Cart\Contracts\InstanceIdentifier;
use App\Enums\PaymentMethodDefine;

/**
 * 注文コンテナ
 */
class OrderContainer
{
    const DEFAULT_INSTANCE = 'default';

    private $session;
    private $events;
    private $instance;

    /**
     * 注文コンテナ
     *
     * @param \Illuminate\Session\SessionManager      $session
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function __construct(SessionManager $session, Dispatcher $events)
    {
        $this->session = $session;
        $this->events = $events;

        $this->instance(self::DEFAULT_INSTANCE);
    }

    public function instance($instance = null)
    {
        $id = null;
        $instance = $instance ?: self::DEFAULT_INSTANCE;

        if ($instance instanceof InstanceIdentifier) {
            $id = $instance->getInstanceIdentifier();
            $this->discount = $instance->getInstanceGlobalDiscount();
            $instance = $instance->getInstanceIdentifier();

            // $this->session->remove($this->instance);
            if ($this->session->has('order.'.self::DEFAULT_INSTANCE)) {
                $this->session->remove('order.' . self::DEFAULT_INSTANCE);
            }
        }

        $this->instance = 'order.'.$instance;

        // コンテナがない場合は初期化する
        if (!$this->session->has($this->instance)) {
            $this->initialize($id);
        }

        return $this;
    }

    /**
     * コンテナ初期化
     *
     * @return void
     */
    public function initialize($customerId = null)
    {
        $container = $this->getContainer();
        $order = new Order();
        $orderDelivery = new OrderDelivery();
        $orderDetail = new OrderDetail();
        $orderDeliveries = new Collection();
        $orderDetails = new Collection();
        $orderDeliveries->push($orderDelivery);
        $orderDetails->push($orderDetail);

        if ($customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $order->customer_id = $customer->id;
                $order->surname = $customer->surname;
                $order->name = $customer->name;
                $order->surname_kana = $customer->surname_kana;
                $order->name_kana = $customer->name_kana;
                $order->zip = $customer->zip;
                $order->prefcode = $customer->prefcode;
                $order->addr_1 = $customer->addr_1;
                $order->addr_2 = $customer->addr_2;
                $order->addr_3 = $customer->addr_3;
                $order->addr = $customer->addr;
                $order->tel = $customer->tel;
                $order->email = $customer->email;
                $order->payment_method = PaymentMethodDefine::CREDIT;
                //orderdelivery
                $orderDelivery->carrier_id = 1;
                $orderDelivery->delivery_type = 1;

                $orderDelivery->client_surname = $customer->surname;
                $orderDelivery->client_name = $customer->name;
                $orderDelivery->client_surname_kana = $customer->surname_kana;
                $orderDelivery->client_name_kana = $customer->name_kana;
                $orderDelivery->client_zip = $customer->zip;
                $orderDelivery->client_prefcode = $customer->prefcode;
                $orderDelivery->client_addr_1 = $customer->addr_1;
                $orderDelivery->client_addr_2 = $customer->addr_2;
                $orderDelivery->client_addr_3 = $customer->addr_3;
                $orderDelivery->client_addr = $customer->addr;
                $orderDelivery->client_tel = $customer->tel;

                $orderDelivery->delivery_surname = $customer->surname;
                $orderDelivery->delivery_name = $customer->name;
                $orderDelivery->delivery_surname_kana = $customer->surname_kana;
                $orderDelivery->delivery_name_kana = $customer->name_kana;
                $orderDelivery->delivery_zip = $customer->zip;
                $orderDelivery->delivery_prefcode = $customer->prefcode;
                $orderDelivery->delivery_addr_1 = $customer->addr_1;
                $orderDelivery->delivery_addr_2 = $customer->addr_2;
                $orderDelivery->delivery_addr_3 = $customer->addr_3;
                $orderDelivery->delivery_addr = $customer->addr;
                $orderDelivery->delivery_tel = $customer->tel;
            }
        }

        $container->setOrder($order);
        $container->setOrderDeliveries($orderDeliveries);
        $container->setOrderDetails($orderDetails);
        $this->saveContainer($container);
    }

    public function currentInstance()
    {
        return str_replace('order.', '', $this->instance);
    }

    /**
     * 注文コンテナを破棄する
     *
     * @return void
     */
    public function destroy()
    {
        $this->session->remove($this->instance);
    }

    /**
     * 注文コンテナを取得する
     *
     * @return \App\Cart\OrderWrapper
     */
    public function getContainer()
    {
        if ($this->session->has($this->instance)) {
            return $this->session->get($this->instance);
        }

        return new OrderWrapper();
    }

    /**
     * 注文コンテナを保存する
     * 
     * @param \App\Cart\OrderWrapper
     */
    public function saveContainer($container = null)
    {
        $this->session->put($this->instance, $container);
    }

    /**
     * 注文エンティティを取得する
     *
     * @return \App\Models\Order 注文
     */
    public function order()
    {
        $container = $this->getContainer();
        return $container->getOrder();
    }

    /**
     * 注文エンティティをセットする
     * 
     * @param \App\Models\Order 注文
     */
    public function setOrder($order = null)
    {
        $container = $this->getContainer();
        $container->setOrder($order);
        $this->saveContainer($container);
    }

    /**
     * 注文届先エンティティリストを取得する
     *
     * @return \Illuminate\Database\Eloquent\Collection 注文届先リスト
     */
    public function orderDeliveries()
    {
        $container = $this->getContainer();
        return $container->getOrderDeliveries();
    }

    /**
     * 注文届先エンティティリストをセットする
     * 
     * @param \Illuminate\Database\Eloquent\Collection 注文届先リスト
     */
    public function setOrderDeliveries($orderDeliveries = null)
    {
        $container = $this->getContainer();
        $container->setOrderDeliveries($orderDeliveries);
        $this->saveContainer($container);
    }

    /**
     * 注文明細エンティティリストを取得する
     *
     * @return \Illuminate\Database\Eloquent\Collection 注文明細リスト
     */
    public function orderDetails()
    {
        $container = $this->getContainer();
        return $container->getOrderDetails();
    }

    /**
     * 注文明細エンティティリストをセットする
     * 
     * @param \Illuminate\Database\Eloquent\Collection 注文明細リスト
     */
    public function setOrderDetails($orderDetails = null)
    {
        $container = $this->getContainer();
        $container->setOrderDetails($orderDetails);
        $this->saveContainer($container);
    }

    /**
     * 決済方法をセット
     *
     * @param string $paymentMethod 決済方法
     * @return void
     * @throws UnknownPaymentMethodException|UnusableRangePaymentException
     */
    public function setPeymentMethod($paymentMethod)
    {
        $container = $this->getContainer();
        $order = $container->getOrder();

        // チェック
        $settlement = Settlement::where('code', $paymentMethod)->first();
        if (!$settlement) {
            // 未定義の決済方法
            throw new UnknownPaymentMethodException();
        }

        if ($settlement->lower_limit && $order->total < $settlement->lower_limit) {
            // 注文合計金額が、決済可能な下金額限を下回っている
            throw new UnusableRangePaymentException(\Lang::get('messages.E.amount.below.range'));
        }
        if ($settlement->upper_limit && $order->total > $settlement->upper_limit) {
            // 注文合計金額が、決済可能な上限額限を超えている
            throw new UnusableRangePaymentException(\Lang::get('messages.E.amount.out.of.range'));
        }

        // 決済方法セット
        $order->payment_method = $paymentMethod;
        $container->setOrder($order);

        $paymentFeeCalculator = new PaymentFeeCalculator($container);
        $container = $paymentFeeCalculator->calculate();
        $this->saveContainer($container);
    }

    /**
     * 使用ポイントを設定する
     *
     * @param string $point ポイント数
     * @return float 保有ポイントから使用ポイントを差し引いた数
     * @throws 
     */
    public function setUsePoints($points)
    {
        if (!\Auth::check()) {
            return;
        }
        // 保有ポイント
        $myPoints = \Auth::user()->remainingPoints();

        $container = $this->getContainer();
        $order = $container->getOrder();

        if ($points > 0) {
            if ($myPoints < $points) {
                throw new InsufficientPointsException();
            }
        }

        // ポイントをセット
        $order->used_point = $points;
        $container->setOrder($order);

        $pointCalculator = new PointCalculator($container);
        $container = $pointCalculator->calculate();
        $this->saveContainer($container);

        // 使用後残ポイントを返す
        return $myPoints - $points;
    }

    /**
     * カートから注文明細を生成する
     * 既にある場合は再構築
     *
     * @return void
     */
    public function cartToOrder()
    {
        $carts = \Cart::content();
        $cartAttr = \Cart::cartAttribute();

        $sorted = $carts->sortBy(function ($row, $key) {
            return $row->model->id;
        });

        $orderDetails = new Collection();
        $no = 1;
        foreach($sorted as $row) {

            $goods = $row->model;

            $orderDetail = new OrderDetail();
            $orderDetail->detail_no = $no;
            $orderDetail->goods_id = $goods->id;
            $orderDetail->goods_code = $goods->code;
            $orderDetail->goods_sku_code = $goods->sku_code;
            $orderDetail->name = $goods->name;
            $orderDetail->volume = $goods->volume;
            $orderDetail->jan_code = $goods->jan_code;
            $orderDetail->maker_id = $goods->maker_id;
            $orderDetail->warehouse_id = 1;
            $orderDetail->supplier_id = $goods->supplier_id;
            $orderDetail->tax_kind = $goods->tax_kind;
            $orderDetail->tax_type = $goods->tax_type;
            $orderDetail->tax_rate = $goods->getTaxRate();
            $orderDetail->tax_rounding_type = $cartAttr->getTaxRoundingType();
            $orderDetail->unit_price = $goods->getLimitedUnitPrice();
            $orderDetail->purchase_unit_price = $goods->purchase_unit_price;
            $orderDetail->purchase_tax_kind = $goods->purchase_tax_kind;
            $orderDetail->purchase_tax_type = $goods->purchase_tax_type;
            $orderDetail->quantity = $row->qty;
            $orderDetail->sale_price = $row->salePrice;
            $orderDetail->image = $row->image;
            
            // $orderDetail->sale_price_tax = $row->salePriceTax;
            // $orderDetail->sale_price_tax_included = $row->salePriceTaxIncluded;
            // $orderDetail->discount = 0;
            // $orderDetail->discount_tax = 0;
            // $orderDetail->subtotal = $row->subtotal;
            // $orderDetail->tax = $row->tax;
            // $orderDetail->subtotal_tax_included = $row->subtotalTaxIncluded;

            $orderDetails->push($orderDetail);

            $no++;
        }

        $this->setOrderDetails($orderDetails);
    }

    /**
     * カートから注文明細を生成する
     * 既にある場合は再構築
     *
     * @return void
     */
    public function sendHome()
    {
        $orderDeliveries = $this->orderDeliveries();
        $orderDelivery = $orderDeliveries[0];
        $orderDeliveries = new Collection();
        $orderDeliveries->push($orderDelivery);

        $customerId = \Auth::user()->id;
        if ($customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $orderDelivery->client_surname = $customer->surname;
                $orderDelivery->client_name = $customer->name;
                $orderDelivery->client_surname_kana = $customer->surname_kana;
                $orderDelivery->client_name_kana = $customer->name_kana;
                $orderDelivery->client_zip = $customer->zip;
                $orderDelivery->client_prefcode = $customer->prefcode;
                $orderDelivery->client_addr_1 = $customer->addr_1;
                $orderDelivery->client_addr_2 = $customer->addr_2;
                $orderDelivery->client_addr_3 = $customer->addr_3;
                $orderDelivery->client_addr = $customer->addr;
                $orderDelivery->client_tel = $customer->tel;

                $orderDelivery->delivery_surname = $customer->surname;
                $orderDelivery->delivery_name = $customer->name;
                $orderDelivery->delivery_surname_kana = $customer->surname_kana;
                $orderDelivery->delivery_name_kana = $customer->name_kana;
                $orderDelivery->delivery_zip = $customer->zip;
                $orderDelivery->delivery_prefcode = $customer->prefcode;
                $orderDelivery->delivery_addr_1 = $customer->addr_1;
                $orderDelivery->delivery_addr_2 = $customer->addr_2;
                $orderDelivery->delivery_addr_3 = $customer->addr_3;
                $orderDelivery->delivery_addr = $customer->addr;
                $orderDelivery->delivery_tel = $customer->tel;
            }
        }

        $this->setOrderDeliveries($orderDeliveries);
    }

    /**
     * 注文コンテナ全体の料金計算を行う
     *
     * @return void
     * @throws 
     */
    public function calculate()
    {
        $container = $this->getContainer();

        // 商品代金計算
        $goodsPriceCalculator = new GoodsPriceCalculator($container, \Cart::content());
        $container = $goodsPriceCalculator->calculate();

        // プロモーション値引計算
        // expectation

        // 調整値引計算
        // expectation

        // 送料、クール代計算
        $postageCalculator = new PostageCalculator($container);
        $container = $postageCalculator->calculate();

        // 決済手数料計算
        $paymentFeeCalculator = new PaymentFeeCalculator($container);
        $container = $paymentFeeCalculator->calculate();

        // ポイント計算
        $pointCalculator = new PointCalculator($container);
        $container = $pointCalculator->calculate();

        // 注文金額の集計・設定を行う
        $orderPriceCalculator = new OrderPriceCalculator($container);
        $container = $orderPriceCalculator->calculate();

        $this->saveContainer($container);
        
        return;
    }

    /**
     * 注文データの検証を行う
     *
     * @return boolean
     */
    public function validation()
    {
        if (!$this->checkDiffOrderAndCart()) {
            return false;
        }

        return true;
    }

    /**
     * カートと注文明細の差をチェックする
     *
     * @return boolean
     */
    private function checkDiffOrderAndCart()
    {
        // カート明細
        $carts = \Cart::content();
        $arr1 = [];
        foreach($carts as $row) {
            array_push($arr1, ['id'=>$row->model->id, 'qty'=>$row->qty]);
        }

        // 注文明細
        $orderDetails = $this->orderDetails();
        $arr2 = [];
        foreach($orderDetails as $row) {
            array_push($arr2, ['id'=>$row->goods_id, 'qty'=>$row->quantity]);
        }

        return $arr1 == $arr2;
    }

}
