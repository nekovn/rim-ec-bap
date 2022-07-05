<?php
/*
 |--------------------------------------------------------------------------
 | カレンダー入力支援部品 fullcalendarの設定定義
 |--------------------------------------------------------------------------
 */
$fullcalendar = [
    /*
     |--------------------------------------------------------------------------
     | イベントの表示定義
     |--------------------------------------------------------------------------
     | auto     : おまかせ
     | block    : google calendar風
     | list-item: リスト形式
     */
    'eventDisplay' => 'block',
    /*
     |--------------------------------------------------------------------------
     | 月単位カレンダー定義
     |--------------------------------------------------------------------------
     */
    'month' => [
        /*
         |--------------------------------------------------------------------------
         | 初期表示するビュー定義
         |--------------------------------------------------------------------------
         | dayGridMonth    : 月単位
         | timeGridWeek    : 週単位
         | list-timeGridDay: 日単位
         */
        'initialView' => 'dayGridMonth',
        /*
         |--------------------------------------------------------------------------
         | １週間の開始曜日
         |--------------------------------------------------------------------------
         | 0: 日曜日
         | 1: 月曜日
         | ・・・
         */
        'firstDay' => 0,
        /*
         |--------------------------------------------------------------------------
         | 週、日表示の場合、上部に終日を表示するかどうか
         |--------------------------------------------------------------------------
         | true : 表示する
         | false: 表示しない
         */
        'allDaySlot' => false,
        /*
         |--------------------------------------------------------------------------
         | 月カレンダーの日をクリックを有効にするかどうか
         |--------------------------------------------------------------------------
         | true : 有効。クリックすると日単位の表示に切り替わる
         | false: 表示しない
         */
        'navLinks' => true,
        /*
         |--------------------------------------------------------------------------
         | ヘッダー部に配置するボタン定義
         |--------------------------------------------------------------------------
         | left  : 左端に配置する要素
         | center: 中央に配置する要素
         | right : 右端に配置する要素
         */
        'headerToolbar' => [
            'left'   => 'dayGridMonth,timeGridWeek,timeGridDay',
            'center' => 'title',
            'right'  => 'today prev,next'
        ],
        /*
         |--------------------------------------------------------------------------
         | FullCalendarのlocaleの上書き および 差分定義
         |--------------------------------------------------------------------------
         */
        'buttonText' => [
            'dayGridMonth' => '月',
            'timeGridWeek' => '週',
            'timeGridDay'  => '日',
            'today'        => '今日'
        ],
        /*
         |--------------------------------------------------------------------------
         | 月カレンダーに時刻を表示するか
         |--------------------------------------------------------------------------
         | true : 表示する
         | false: 表示しない
         */
        'displayEventTime' => true,
        /*
         |--------------------------------------------------------------------------
         | 業務時刻を表示するか
         |--------------------------------------------------------------------------
         | ['dayOfWeek' => [0～],  0: 日曜日, ～
         |  'startTime' => 'h:mm',
         |  'endTime' => 'h:mm'
         | ]     : 表示する
         | false: 表示しない
         */
        'businessHours' => [
            [
                'dayOfWeek' => [1,2,3,4,5], // 0:日曜日～
                'startTime' => '9:00',
                'endTime' => '18:00'
            ],
        ],
        /*
         |--------------------------------------------------------------------------
         | cacheを使用するか
         | FullCalendarにはない。
         |--------------------------------------------------------------------------
         | true : 使用する
         | false: 使用しない
         */
        'useCache' => true
    ]
];