<?php
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Util\SystemHelper;

/*
|--------------------------------------------------------------------------
| HTMLコントロール向けフォームヘルパー
|--------------------------------------------------------------------------
| htmlのコントロールを定義する。
*/
/**
 * Labelタグ拡張
 */
Form::macro('labelEx', function ($name, $text, $attributes = []) {
    $labelAttr = SystemHelper::getAppSettingValue('page.html.label');
    $attributes['class'] = classAttrBuilder(data_get($labelAttr, 'class', ''), $attributes);
    return Form::label($name, $text, $attributes);
});
/**
 * テキストボックス拡張
 */
Form::macro('textEx', function ($name, $text, $attributes = []) {
    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $attributes['class'] = classAttrBuilder(data_get($inputAttr, 'class', ''), $attributes);
    return Form::text($name, $text, $attributes);
});
/**
 * 半角英数入力項目
 */
Form::macro('textAlphanum', function ($name, $text, $attributes = []) {
    $attributes['data-parsley-type'] = "alphanum";
    return Form::textEx($name, $text, $attributes);
});
/**
 * 文字列（半角数値（前ゼロ可））入力項目
 */
Form::macro('textDigits', function ($name, $text, $attributes = []) {
    $attributes['data-parsley-type'] = "digits";
    return Form::textEx($name, $text, $attributes);
});
/**
 * 文字列（半角数値（ゼロ埋め））入力項目
 */
Form::macro('textPaddingZero', function ($name, $text, $attributes = []) {
    $attributes['data-parsley-type'] = "digits";
    $attributes['class'] = classAttrBuilder('zero-padding', $attributes);
    $attributes['onChange'] = "fw.lib.zeroPadding(this)";
    return Form::textEx($name, $text, $attributes);
});
/**
 * パスワード拡張
 */
Form::macro('passwordEx', function ($name, $attributes = []) {
    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $attributes['class'] = classAttrBuilder(data_get($inputAttr, 'class', ''), $attributes);
    return Form::password($name, $attributes);
});
/**
 * URL拡張
 */
Form::macro('urlEx', function ($name, $text, $attributes = []) {
    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $attributes['class'] = classAttrBuilder(data_get($inputAttr, 'class', ''), $attributes);
    return Form::url($name, $text, $attributes);
});
/**
 * 文字列（電話番号）入力項目
 */
Form::macro('textTel', function ($name, $text, $attributes = []) {
    $attributes['maxlength'] = 15;
    $formatHyphen = SystemHelper::getAppSettingValue('page.support-parts.tel.format-hypen');

    $attributes['data-format-hyphen'] = intVal($formatHyphen);
    if ($formatHyphen) {
        $attributes['onChange'] = "fw.lib.formatTel(this);";
    }

    $attributes['data-parsley-phone-number'] = '';

    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $telClass = data_get($inputAttr, 'class', '')." phone-number";
    $attributes['class'] = classAttrBuilder($telClass, $attributes);

    return Form::tel($name, $text, $attributes);
});
/**
 * 文字列（メールアドレス）入力項目
 */
Form::macro('emailEx', function ($name, $text, $attributes = []) {
    $attributes['maxlength'] = 255;
    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $attributes['class'] = classAttrBuilder(data_get($inputAttr, 'class', ''), $attributes);
    return Form::email($name, $text, $attributes);
});
/**
 * 数値入力項目
 */
Form::macro('numberEx', function ($name, $text, $attributes = []) {
    $inputAttr = SystemHelper::getAppSettingValue('page.html.input.text');
    $attributes['class'] = classAttrBuilder(data_get($inputAttr, 'class', ''), $attributes);
    return Form::number($name, $text, $attributes);
});
/**
 * 数値：整数入力項目
 */
Form::macro('integer', function ($name, $text, $attributes = []) {
    $attributes['pattern'] = '[+-]?\d*';
    $attributes['step'] = 1;
    $attributes['data-pattern-message'] = Lang::get('messages.E.input.integer');
    return Form::numberEx($name, $text, $attributes);
});
/**
 * 数値：正の整数入力項目
 */
Form::macro('positiveInteger', function ($name, $text, $attributes = []) {
    $attributes['min'] = 1;
    $attributes['step'] = 1;
    $attributes['pattern'] = '\d*';
    $attributes['data-pattern-message'] = Lang::get('messages.E.input.positive.integer');
    return Form::numberEx($name, $text, $attributes);
});
/**
 * 日付項目
 */
Form::macro('dateEx', function ($name, $text, $attributes = [], $extra = []) {
    $dateAttr = SystemHelper::getAppSettingValue('page.html.input.date');
    $attributes['class'] = classAttrBuilder(data_get($dateAttr, 'class', ''), $attributes);
    return Form::date($name, $text, $attributes);
});
/**
 * テキストボックス拡張
 */
Form::macro('timeEx', function ($name, $text, $attributes = []) {
    $dateAttr = SystemHelper::getAppSettingValue('page.html.input.date');
    $attributes['class'] = classAttrBuilder(data_get($dateAttr, 'class', ''), $attributes);
    return Form::time($name, $text, $attributes);
});
/**
 * １列表示チェックボックスヘルパー定義
 *  idとname属性は同じだが、attributesに設定した場合は上書き
 * @param $name:checkbox name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $value
 * @param $checked チェック状態
 */
Form::macro('checkboxBase', function ($name, $text, $attributes = [], $value = 1, $checked = false) {
    $checkboxAttr = SystemHelper::getAppSettingValue('page.html.input.checkbox');
    $attributes['class'] = classAttrBuilder(data_get($checkboxAttr, 'class', ''), $attributes);
    if (!Arr::has($attributes, 'id')) {
        $attributes['id'] = $name;
    }
    $html = Form::checkbox($name, $value, $checked, $attributes);
    $html .= Form::label($attributes['id'], $text, ['class' => $checkboxAttr['label-class']]);
    return $html;
});
/**
 * １列表示チェックボックス(1つ）ヘルパー定義
 *
 * @param $name:checkbox name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $value
 * @param $checked チェック状態
 */
Form::macro('inlineCheckbox', function ($name, $text, $attributes = [], $value = 1, $checked = false) {
    $checkboxAttr = SystemHelper::getAppSettingValue('page.html.input.checkbox');

    $html = $checkboxAttr['wrapper-open'];
    $html .= Form::checkboxBase($name, $text, $attributes, $value, $checked);
    $html .= $checkboxAttr['wrapper-close'];
    return $html;
});
/**
 * １行表示チェックボックスヘルパー定義
 *
 * @param $name:checkbox name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $checkedValues checked状態にする値
 */
Form::macro('checkboxes', function ($name, $keyValues, $attributes = [], $checkedValues = []) {
    $id = data_get($attributes, 'id', $name);
    $checkboxAttr = SystemHelper::getAppSettingValue('page.html.input.checkbox');

    $html = $checkboxAttr['wrapper-open'];
    foreach ($keyValues as $key => $value) {
        $attributes['id'] = "{$id}-{$key}";
        $html .= Form::checkboxBase($name, $value, $attributes, $key, in_array($key,$checkedValues));
        // $html .= Form::inlineCheckbox($name, $value, $attributes, $key, $checkedValues);
    }
    $html .= $checkboxAttr['wrapper-close'];
    return $html;
});
/**
 * １列表示チェックボックスヘルパー定義
 *  idとname属性は同じだが、attributesに設定した場合は上書き
 * @param $name:checkbox name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $value
 * @param $checked チェック状態
 */
Form::macro('radioBase', function ($name, $text, $attributes = [], $value = 1, $checked = false) {
    $radioAttr = SystemHelper::getAppSettingValue('page.html.input.radio');
    $attributes['class'] = classAttrBuilder(data_get($radioAttr, 'class', ''), $attributes);
    if (!Arr::has($attributes, 'id')) {
        $attributes['id'] = $name;
    }
    $html = Form::radio($name, $value, $checked, $attributes);
    $html .= Form::label($attributes['id'], $text, ['class' => $radioAttr['label']]);

    return $html;
});
/**
 * １行表示ラジオボタンヘルパー定義
 *
 * @param $name:radio name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $checkedValue checked状態にする値
 */
Form::macro('radios', function ($name, $keyValues, $attributes = [], $checkedValue = null) {
    $id = data_get($attributes, 'id', $name);

    $radioAttr = SystemHelper::getAppSettingValue('page.html.input.radio');

    $elsementWrapperOpen  = $radioAttr["element-wrapper-open"];
    $elsementWrapperClose = $radioAttr["element-wrapper-close"];

    $html = '';
    foreach ($keyValues as $index => $keyValue) {
        $attributes['id'] = "{$id}-{$index}";
        $radio = Form::radioBase($name, $keyValue['label'], $attributes, $keyValue['value'], $keyValue['value'] === $checkedValue);
        $html .= "{$elsementWrapperOpen}{$radio}{$elsementWrapperClose}";
    }
    $radioGroupWrapperOpen  = $radioAttr["wrapper-open"];
    $radioGroupWrapperClose = $radioAttr["wrapper-close"];

    return "{$radioGroupWrapperOpen}{$html}{$radioGroupWrapperClose}";
});
/**
 * selectタグ拡張
 */
Form::macro('selectEx', function ($name, $options, $selectedValue = null, $attributes = []) {
    $selectAttr = SystemHelper::getAppSettingValue('page.html.input.select');
    $attributes['class'] = classAttrBuilder(data_get($selectAttr, 'class', ''), $attributes);
    return Form::select($name, $options, $selectedValue, $attributes);
});
/**
 * プルダウン入力項目
 */
Form::macro('dropdown', function ($name, $options, $selectedValue = null, $attributes = [], $extra=[]) {
    if (isset($extra['insert-empty'])) {
        $emptyLabel = data_get($extra, 'empty-label', '選択してください');
        $options = ['' => $emptyLabel] + $options;
    }
    return Form::selectEx($name, $options, $selectedValue, $attributes);
});
if (!function_exists('classAttrBuilder')) {
    /**
     * クラス属性にFormヘルパー独自クラスを挿入する。
     *
     * @param string $defaultClass デフォルトClass属性文字列
     * @param array $attributes Formヘルパー属性引数
     * @return 文字列
     */
    function classAttrBuilder($defaultClass, $attributes)
    {
        if (!isset($attributes['class'])) {
            return $defaultClass;
        }
        return "{$defaultClass} {$attributes['class']}";
    }
}
