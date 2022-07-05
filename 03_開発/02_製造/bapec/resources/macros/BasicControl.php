<?php

use App\Enums\CodeDefine;
use App\Helpers\Util\SystemHelper;
use App\Models\Code;

/*
|--------------------------------------------------------------------------
| 標準部品フォームヘルパー
|--------------------------------------------------------------------------
| システムに依存しない標準部品を定義する。
*/
/**
 * 都道府県プルダウン
 */
Form::macro('pref', function ($name, $selectedValue = null, $attributes = []) {
    $prefs = SystemHelper::getCodes(CodeDefine::PREF_CD);
    $prefs = array_replace(['' => '都道府県'], $prefs);
    $attributes['class'] = classAttrBuilder('pref_cd', $attributes);
    return Form::selectEx($name, $prefs, $selectedValue, $attributes);
});
/**
 * 文字列（郵便番号）入力項目
 */
Form::macro('postCode', function ($name, $text, $attributes = [], $extra = []) {
    $formatHyphen = SystemHelper::getAppSettingValue("page.support-parts.postcode.format-hypen");

    $attributes['class'] = classAttrBuilder('post-code', $attributes);
    $attributes['pattern'] = $formatHyphen ? '\d{3}-?\d{4}' : '\d{7}';
    $attributes['data-format-hyphen'] = intval($formatHyphen);
    if ($formatHyphen) {
        $attributes['onChange'] = "fw.lib.formatPostCode(this);";
    }
    $addrAutoComplete = isset($extra['autocomplete']);
    $attributes['data-addr-autocomplete'] = $addrAutoComplete;
    if ($addrAutoComplete) {
        $autocomplete = $extra['autocomplete'];
        if (isset($autocomplete['selector-pref'])) {
            $attributes['data-selector-pref'] = $autocomplete['selector-pref'];
        }
        if (isset($autocomplete['selector-city'])) {
            $attributes['data-selector-city'] = $autocomplete['selector-city'];
        }
        if (isset($autocomplete['selector-town'])) {
            $attributes['data-selector-town'] = $autocomplete['selector-town'];
        }

        if (isset($extra['trigger'])) {
            $attributes['data-trigger'] = $extra['trigger'];
        }
        
        $attributes['onfocus'] = "fw.lib.zipToAddrAutocomplete(this);";
    }

    return Form::textEx($name, $text, $attributes);
});
/**
 * 性別ラジオボタン
 *
 * @param $name:radio name
 * @param $text:ラベルテキスト
 * @param $attributes:その他属性 [属性名:値]
 * @param $checkedValue checked状態にする値
 */
Form::macro('gender', function ($name, $attributes = [], $checkedValue = null) {
    $html = '';
    $id = isset($attributes['id']) ? $attributes['id'] : $name;
    $keyValues = SystemHelper::getCodes(CodeDefine::GENDER);

    $radioAttr = SystemHelper::getAppSettingValue('page.html.input.radio');

    $elsementWrapperOpen  = $radioAttr["element-wrapper-open"];
    $elsementWrapperClose = $radioAttr["element-wrapper-close"];

    foreach ($keyValues as $key => $value) {
        $attributes['id'] = "{$id}-{$key}";
        $radio = Form::radioBase($name, $value, $attributes, $key, $key == $checkedValue);
        $html .= <<<__HTML__
            {$elsementWrapperOpen}
            {$radio}
            {$elsementWrapperClose}
        __HTML__;
    }

    $radioGroupWrapperOpen  = $radioAttr["wrapper-open"];
    $radioGroupWrapperClose = $radioAttr["wrapper-close"];

    return "{$radioGroupWrapperOpen}{$html}{$radioGroupWrapperClose}";
});
/**
 * 1月～12月の選択肢
 */
Form::macro('months', function ($name, $selectedValue = null, $attributes = []) {
    $months = [];
    for ($i = 0; $i < 12; $i++) {
        $months[$i] = sprintf('%02d月', $i + 1);
    }
    $attributes['class'] = classAttrBuilder('w-month-dropdown', $attributes);

    return Form::selectEx($name, $months, $selectedValue, $attributes);
});
/**
 * 生年月日入力項目
 */
Form::macro('birthday', function ($name, $text, $attributes = [], $ageSelector) {
    $attributes['class'] = classAttrBuilder('w-date birthday', $attributes);
    if ($ageSelector) {
        $attributes['data-age'] = $ageSelector;
        $attributes['onChange'] = "fw.lib.calcAge(this);";
    }
    if (SystemHelper::getAppSettingValue('page.support-parts.date')) {
        return Form::datePicker($name, $text, $attributes);
    } else {
        return Form::textEx($name, $text, $attributes);
    }
});
/**
 * 表示件数プルダウンフォームヘルパー定義
 */
Form::macro('displayCountSelect', function ($functionId, $attributes = []) {
    $name = "{$functionId}-display-count";
    if (!Arr::has($attributes, 'id')) {
        $attributes['id'] = $name;
    }
    return Form::selectEx(
        $name,
        SystemHelper::getAppSettingValue('page.pagination.display-count.selects'),
        old('page.count') ?? SystemHelper::getAppSettingValue('page.pagination.display-count.default'),
        $attributes
    );
});
