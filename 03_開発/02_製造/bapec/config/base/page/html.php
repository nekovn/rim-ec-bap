<?php
/*
|--------------------------------------------------------------------------
| htmlの属性定義
|--------------------------------------------------------------------------
*/
$html = [
    'label' => [
        'class' => ''
    ],
    'input' => [
        'text' => [
            'class' => 'clearable',
        ],
        'date' => [
            'class' => 'clearable'
        ],
        'checkbox' => [
            'wrapper-open'  => '',
            'class'         => '',
            'label-class'   => '',
            'wrapper-close' => ''
        ],
        'radio' => [
            'wrapper-open'          => '<ul class="radio">',
            'element-wrapper-open'  => '<li>',
            'class'                 => '',
            'label'                 => 'radio_css',
            'element-wrapper-close' => '</li>',
            'wpaeer-close'          => '</ul>'
            ],
        'select' => [
            'class' => '',
        ],
        'textarea' => '',
    ],
    'button' => [
        /* 検索ボタン */
        'search' => [
            'class' => '',
            'icon'  => ''
        ],
        /* クリアボタン */
        'clear' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 戻るボタン */
        'back' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 新規作成ボタン */
        'create' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 登録ボタン */
        'store' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 更新ボタン */
        'update' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 削除ボタン */
        'delete' => [
            'class' => '',
            'icon'  => ''
        ],
        /* 検索子画面ボタン */
        'disp-search' => [
            'class' => '',
            'icon'  => ''
        ]
    ]
];