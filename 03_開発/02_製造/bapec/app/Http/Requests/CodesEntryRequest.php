<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Enums\FlagDefine;

/**
 * コードマスタ編集画面Requestクラス
 *
 * @category  管理サイト
 * @package   App\Http\Requests
 * @version   1.0
 */
class CodesEntryRequest extends FormRequest
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
    public function authorize() {
        return true;
    }
    /**
     * 【必須】
     * バリデーションルール
     *
     * @return void
     */
    public function rules() {
        $code = $this->input('code');

        // cvalueに関して、code_values.codeが同一かつ有効なデータで、valueが同一のデータがあればNGとする
        $rules = [
            'key' => [Rule::unique('code_values')->ignore($this->id)
                    ->where(function($query) use($code) {
                        return $query->where('code', $code)
                                    ->where('is_deleted', '<>', FlagDefine::ON);
                    })
            ],
        ];

        return $rules;
    }
    /**
     * HTTPリクエスト内の項目の見出しを取得する。
     *
     * @return array HTTPリクエスト内の項目の見出し
     */
    public function attributes() {
        return [
            'code_id' => 'コードid',
            'code' => 'コード',
            'value' => '値',
            'content' => '内容',
            'remark' => '備考',
            'attr_1_description' => '属性値1説明',
            'attr_1' => '属性1',
            'attr_2_description' => '属性値2説明',
            'attr_2' => '属性2',
            'attr_3_description' => '属性値3説明',
            'attr_3' => '属性3',
            'sequence' => '並び順',
        ];
    }
}
