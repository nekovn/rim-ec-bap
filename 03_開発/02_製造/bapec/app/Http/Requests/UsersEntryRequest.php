<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Rules\PasswordPolicy;

/**
 * ユーザマスタ編集画面Requestクラス
 *
 * @category  管理サイト
 * @package   App\Http\Requests
 * @version   1.0
 */
class UsersEntryRequest extends FormRequest
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
        $_this = $this;
        return [
            'code' => [
                Rule::unique('users')
            ],
            'password' => [
                new PasswordPolicy()
            ]
        ];
    }
    /**
     * HTTPリクエスト内の項目の見出しを取得する。
     *
     * @return array HTTPリクエスト内の項目の見出し
     */
    public function attributes()
    {
        return [
            'code' => '社員番号'
        ];
    }
}
