<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Enums\CodeDefine;
use App\Enums\CodeValueDefine;
use App\Enums\OrderCancelMailSettingsDefine;
use App\Helpers\Util\SystemHelper;

/**
 * BAP様向け注文キャンセルメール作成クラス
 *
 * @category  システム共通
 * @package   App\Mail
 * @copyright 2021 elseif.jp All Rights Reserved.
 * @version   1.0
 */
class OrderCancelMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * コンストラクタ
     *
     * @access public
     * @param OrderPayments $orderPayments 受注決済
     */
    public function __construct($orderPayments)
    {
        $this->orderPayment = $orderPayments;
    }

    /**
     * BAP様向け注文キャンセルメール作成
     */
    public function build()
    {
        $mailsubject = SystemHelper::getCodeAttrs(CodeDefine::ORDER_CANCEL_MAIL_SETTING)[OrderCancelMailSettingsDefine::ORDER_CANCEL_MAIL_INFO][CodeValueDefine::ORDER_CANCEL_MAIL_SETTING_ATTR2];

        return $this->text('mail.orderCancelMail')
                    ->subject($mailsubject)
                    ->with([
                        'orderPayment' => $this->orderPayment
                    ]);
    }
}
