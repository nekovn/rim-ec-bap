<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Enums\CodeDefine;
use App\Models\CodeValue;

use Log;

/**
 * お客様向け注文完了メール作成クラス
 *
 * @category  システム共通
 * @package   App\Mail
 * @copyright 2021 elseif.jp All Rights Reserved.
 * @version   1.0
 */
class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * コンストラクタ
     *
     * @access public
     * @param Order $order 受注
     * @param OrderDelivery $orderDelivery 受注配送先
     * @param OrderDetails $orderDetails 受注明細
     */
    //public function __construct($client, $order, $orderDetails)
    public function __construct($order, $orderDelivery, $orderDetails)
    {
        $this->order = $order;
        $this->orderDelivery = $orderDelivery;
        $this->orderDetails = $orderDetails;

        /*
        $this->client = $client;
        $this->order = $order;
        $this->orderDetails = $orderDetails;
        */
    }

    /**
     * お客様向け発注登録完了メール作成
     */
    public function build()
    {
        $mailfrom = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',1)->value('attr_2');
        $mailfromname = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',1)->value('attr_1');
        $mailsubject = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',3)->value('attr_1');
        $mailinquiry = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',2)->value('attr_1');

        //注文日時
        $ordered_at = date("Y/m/d H:i:s",strtotime($this->order->ordered_at));

        //割引額
        $discountamount = $this->order->discount + $this->order->promotion_discount_total + $this->order->coupon_discount_total;

        //手数料
        $commission = $this->order->payment_fee_total + $this->order->packing_charge_total + $this->order->other_fee_total;

        return $this->text('mail.orderMail')
                    ->to($this->order->email)
                    ->from($mailfrom,$mailfromname)
                    ->subject($mailsubject)
                    ->with(['order' => $this->order,
                            'orderdelivery' => $this->orderDelivery,
                            'orderdetail' => $this->orderDetails,
                            'ordered_at' => $ordered_at,
                            'discountamount' => $discountamount,
                            'commission' => $commission,
                            'mailinquiry' => $mailinquiry
                    ]);
    }
}
