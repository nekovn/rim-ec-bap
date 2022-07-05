<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ユーザマスタ画面一覧Requestクラス
 *
 * @category  管理サイト
 * @package   App\Http\Requests
 * @version   1.0
 */
class UsersListRequest extends FormRequest
{
    //TODO:権限によってはリクエストを共有する？
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
        return [];
    }
}
