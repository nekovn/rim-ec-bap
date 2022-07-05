<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use Lang;

/**
 * 受注詳細画面Requestクラス（更新時）
 *
 * @category  管理サイト
 * @package   App\Http\Requests
 * @version   1.0
 */
class OrderDetailsUpdateRequest extends FormRequest
{
    /**
     * 【必須】
     * 認証設定
     *
     * 画面に参照権限を設ける場合に使用。
     * これを使用しない場合はtrueを固定で返してください
     *
     * @return void
     */
    public function authorize()
    {
        return true;
    }
    /**
     * 【必須】
     * バリデーションルール
     *
     * @return void
     */
    public function rules()
    {
        return [
        ];
    }
    public function withValidator($validator)
    {
        if (count($validator->errors()) > 0) {
            return;
        }

        $aryMessage = [];

        //既に出荷テーブルが存在する場合はエラー
        $shipData = Ship::getModel()->where('order_id', $this->id)->first();
        if ($shipData) {
            $aryMessage += ['id' 
            => Lang::get('messages.E.nocahnge')];
        }
        
        $validator->after(function ($validator) use ($aryMessage) {
            foreach ($aryMessage as $key => $message) {
                $validator->errors()->add($key, $message);
            }
        });
    }


    /**
     * HTTPリクエスト内の項目の見出しを取得する。
     *
     * @return array HTTPリクエスト内の項目の見出し
     */
    public function attributes()
    {
        return [
            'id' => 'パスワード'
        ];
    }
}
