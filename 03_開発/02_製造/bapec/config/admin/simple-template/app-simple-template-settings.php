<?php
/*
|--------------------------------------------------------------------------
| SimpleTemplateの設定
|--------------------------------------------------------------------------
*/
$adminSimpleTemplateSettings = [
    /*
    |--------------------------------------------------------------------------
    | simple-clud-templateの設定
    |--------------------------------------------------------------------------
    | list: 一覧画面の設定
    */
    'crud' => [
        /*
        |--------------------------------------------------------------------------
        | 削除処理を一覧・詳細のどちらで行うか
        |--------------------------------------------------------------------------
        | list  : 一覧画面で実施
        | detail: 詳細画面で実施
        */
        'delete' => 'detail',
        /*
        |--------------------------------------------------------------------------
        | 詳細画面で行った追加・更新・削除の処理結果を反映するタイミングを定義
        |--------------------------------------------------------------------------
        | back    : 戻るボタン押下時
        | realtime: リアルタイム（トランザクション成功時）
        |           新規は一覧末尾に追加、更新・削除は対象行を更新する。
        */
        'page-refresh' => 'realtime'
    ]
];