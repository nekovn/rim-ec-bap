<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\FlagDefine;
use App\Rules\PasswordPolicy;

/**
 * 会員登録Requestクラス
 *
 * @package   App\Http\Requests
 * @version   1.0
 */
class MembersRegisterRequest extends FormRequest
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
            'surname' => 'required|string|max:30',
            'name' => 'required|string|max:30',
            'surname_kana' => 'required|string|max:60',
            'name_kana' => 'required|string|max:60',
            'email' => [
                Rule::unique('customers')->where('is_deleted', FlagDefine::OFF)
            ],
            'password' => [
                new PasswordPolicy()
            ],
            'zip' => 'required|regex:/\d{7}/',
            'tel' => 'required|regex:/\d{2,4}-?\d{2,4}-?\d{3,4}/',
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
            'surname'  => '姓（氏名）',
            'name'  => '名（氏名）',
            'surname_kana'  => '姓（フリガナ）',
            'name_kana'  => '名（フリガナ）',
            'email' => 'メールアドレス',
            'zip' => '郵便番号',
            'tel' => '電話番号',
        ];
    }
}
