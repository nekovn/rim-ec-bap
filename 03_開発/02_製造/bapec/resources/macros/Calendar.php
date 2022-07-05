<?php
/**
 * FullCalendar向けフォームヘルパー定義
 */
/**
 * 月カレンダー
 */
Form::macro('monthCalendar', function ($name, $attributes = []) {
    return '<div id="'.$name.'-month-calendar"></div>';
});
