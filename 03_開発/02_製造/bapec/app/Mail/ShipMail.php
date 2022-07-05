<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Helpers\Util\SystemHelper;
use App\Models\CodeValue;
use App\Models\Customer;
use App\Models\Settlement;
use App\Models\Ship;
use App\Enums\CodeDefine;
use App\Exceptions\ApplicationException;
use Lang;

/**
 * お客様向け出荷のご案内メール作成クラス
 *
 * @category  システム共通
 * @package   App\Mail
 * @copyright 2021 elseif.jp All Rights Reserved.
 * @version   1.0
 */
class ShipMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * コンストラクタ
     *
     * @access public
     * @param shipId  $shipId ships.id
     */
    public function __construct($shipId)
    {
        $this->shipId = $shipId;
    }

    /**
     * お客様向け発注登録完了メール作成
     */
    public function build()
    {

        //出荷テーブル取得
        $ship = Ship::with(['order','shipDetails', 'carrier'])->where('id', $this->shipId)->get()->first();

        //問合せ番号が無い場合はエラーを返す
        if (!$ship->slip_no) {
            throw new ApplicationException(Lang::get('messages.E.nosetting',['field'=>'問合せ番号']), 422);//validationエラー等
        }

        // 支払方法取得
        $ship->Order->payment_method_name = '';
        if ($ship->Order->payment_method) {
            $settlement = Settlement::paymentMethod($ship->Order->payment_method);
            if ($settlement) {
                $ship->Order->payment_method_name = $settlement->display_name;
            }
        }

        // メール送信用情報を取得
        $subject = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',6)->value('attr_1');
        $from = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',4)->value('attr_2');
        $fromname = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',4)->value('attr_1');
        // $signature = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',6)->value('attr_1');
        $mailinquiry = CodeValue::where('code',CodeDefine::MAIL_SETTINGS)->where('key',5)->value('attr_1');

        // メール送信先を取得
        $customer = Customer::find($ship->customer_id);

        return $this->text('mail.shipMail')
            ->to($customer->email)
            ->from($from,$fromname)
            ->subject($subject)
            ->with([
                'ships' => $ship,
                'mailinquiry' => $mailinquiry
            ]);
    }
}
