<?php
/*
|--------------------------------------------------------------------------
| ボタン系フォームヘルパー
|--------------------------------------------------------------------------
*/
/**
 * アイコンボタンフォームヘルパー定義
 */
Form::macro('iconButton', function ($attributes, $btnIcon, $caption) {
    return Form::button("<i class='{$btnIcon}'></i>{$caption}", $attributes);
});
/**
 * 検索ボタンフォームヘルパー定義
 */
Form::macro('searchButton', function ($attributes = [], $caption = '検索') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.search');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * クリアボタンフォームヘルパー定義
 */
Form::macro('clearButton', function ($attributes = [], $caption = 'クリア') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.clear');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 戻るボタンフォームヘルパー定義
 */
Form::macro('backButton', function ($attributes = [], $caption = '戻る') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.back');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 新規登録ボタンフォームヘルパー定義
 */
Form::macro('createButton', function ($attributes = [], $caption = '新規登録') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.create');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 登録ボタンフォームヘルパー定義
 */
Form::macro('storeButton', function ($attributes = [], $caption = '登録') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.store');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 更新ボタンフォームヘルパー定義
 */
Form::macro('updateButton', function ($attributes = [], $caption = '更新') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.update');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 削除ボタンフォームヘルパー定義
 */
Form::macro('deleteButton', function ($attributes = [], $caption = '削除') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.delete');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
/**
 * 検索画面表示ボタンフォームヘルパー定義
 */
Form::macro('dispSearchButton', function ($attributes = [], $caption = '') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.disp-search');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});

/**
 * アップロードボタンフォームヘルパー定義
 */
Form::macro('uploadButton', function ($attributes = [], $caption = 'アップロード') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.upload');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});

/**
 * ダウンロードボタンフォームヘルパー定義
 */
Form::macro('downloadButton', function ($attributes = [], $caption = 'ダウンロード') {
    $buttonAttr = SystemHelper::getAppSettingValue('page.html.button.download');
    $attributes['class'] = classAttrBuilder(data_get($buttonAttr, 'class', ''), $attributes);
    return Form::iconButton($attributes, $buttonAttr['icon'], $caption);
});
