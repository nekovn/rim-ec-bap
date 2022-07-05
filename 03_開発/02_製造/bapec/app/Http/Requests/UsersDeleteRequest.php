<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Enums\FlagDefine;

/**
 * ユーザマスタ削除Requestクラス
 *
 * @category  管理サイト
 * @package   App\Http\Requests
 * @version   1.0
 */
class UsersDeleteRequest extends FormRequest
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
            'id' => 'not_in:'.Auth::id()
        ];
    }
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return array_merge($this->request->all(), [
            'id' => $this->route('id'),
        ]);
    }
    /**
     * HTTPリクエスト内の項目の見出しを取得する。
     *
     * @return array HTTPリクエスト内の項目の見出し
     */
    public function attributes()
    {
        return [];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.not_in' => 'ご自身のカウント削除は行えません。',
        ];
    }
}
