<?php
use App\Helpers\Util\SystemHelper;

/*
|--------------------------------------------------------------------------
| Flatpickrフォームヘルパー
|--------------------------------------------------------------------------
*/
/**
 * 日時：年月日入力項目
 */
Form::macro('datePicker', function ($name, $text, $attributes = [], $calendarIconClass = 'far fa-calendar') {
    $attributes['class'] = classAttrBuilder('input-date w-date', $attributes);
    return flatpickrHtmlBuilder($name, $text, $attributes,
                                SystemHelper::getAppSettingValue('page.support-parts.date.datepicker.use-calendar-icon'),
                                'datepicker-wrapper',
                                $calendarIconClass);
});
/**
 * 日時：年月入力項目
 */
Form::macro('monthPicker', function ($name, $text, $attributes = [], $calendarIconClass = 'far fa-calendar') {
    $attributes['class'] = classAttrBuilder('input-month w-month', $attributes);
    return flatpickrHtmlBuilder($name, $text, $attributes,
                                SystemHelper::getAppSettingValue('page.support-parts.date.monthpicker.use-calendar-icon'),
                                'monthpicker-wrapper',
                                $calendarIconClass);
});
/**
 * 日時：時分入力項目
 */
Form::macro('timePicker', function ($name, $text, $attributes = [], $calendarIconClass = 'far fa-clock') {
    $attributes['class'] = classAttrBuilder('input-time w-time', $attributes);
    return flatpickrHtmlBuilder($name, $text, $attributes,
                                SystemHelper::getAppSettingValue('page.support-parts.date.timepicker.use-calendar-icon'),
                                'timepicker-wrapper',
                                $calendarIconClass);
});
/**
 * 日時：年月日時分入力項目
 */
Form::macro('dateTimePicker', function ($name, $text, $attributes = [], $calendarIconClass = 'far fa-calendar') {
    $attributes['class'] = classAttrBuilder('input-date-time w-date-time', $attributes);
    return flatpickrHtmlBuilder($name, $text, $attributes,
                                SystemHelper::getAppSettingValue('page.support-parts.date.datetimepicker.use-calendar-icon'),
                                'datetime-wrapper',
                                $calendarIconClass);
});
/**
 * 日時：期間入力項目
 */
Form::macro('dateRangePicker', function ($name, $text, $attributes = [], $toAttributes) {
    $attributes['class'] = classAttrBuilder('input-range-from-date fp-range', $attributes);
    $attributes['data-to-id'] = "#{$toAttributes['attr']['id']}";
    $from = Form::datePicker($name, $text, $attributes);

    if (!Arr::has($toAttributes, 'attr.class')) {
        $toAttributes['attr']['class'] = '';
    }
    if (!Arr::has($toAttributes, 'name')) {
        $toAttributes['name'] = '';
    }
    if (!Arr::has($toAttributes, 'default-value')) {
        $toAttributes['default-value'] = '';
    }

    $toAttributes['attr']['class'] = classAttrBuilder('fp-range', $toAttributes['attr']);
    $to = Form::datePicker($toAttributes['name'], $toAttributes['default-value'], $toAttributes['attr']);
    return <<<__HTML__
        <div class="daterange-wrapper">
            {$from}
            <span class="separator-period">～</span>
            {$to}
        </div>
    __HTML__;
});

if (!function_exists('flatpickrHtmlBuilder')) {
    /**
     * クラス属性にFormヘルパー独自クラスを挿入する。
     *
     * @param string $name name属性
     * @param array $text 初期値
     * @param array $attributes タグ属性
     * @param string $wrapperClass カレンダー要素をラップするクラス名
     * @param string $calendarIconClass flatpickrカレンダーアイコンクラス名
     * @return 文字列
     */
    function flatpickrHtmlBuilder($name, $text, $attributes, $useCalendar = false, $wrapperClass, $calendarIconClass)
    {
        // $attributes['readonly'] = '';
        if (!$useCalendar) {
            return Form::textEx($name, $text, $attributes);
        }
        $attributes['data-input'] = '';
        $input = Form::textEx($name, $text, $attributes);
        return <<<__HTML__
          <div class="input-group {$wrapperClass}">
            {$input}
            <span class="input-group-append">
            <a class="input-group-text input-button" data-toggle>
              <i class="{$calendarIconClass}"></i>
            </a></span>
          </div>
        __HTML__;
    }
}
