<?php
/*
|--------------------------------------------------------------------------
| htmlの属性定義
|--------------------------------------------------------------------------
*/
$html = [
    'label' => [
        'class' => 'mr-2'
    ],
    'input' => [
        'text' => [
            'class' => 'form-control clearable',
        ],
        'date' => [
            'class' => 'form-control clearable',
        ],
        'checkbox' => [
            'wrapper-open' => '<div class="form-group form-check form-check-inline">',
            'class'       => 'form-check-input',
            'label-class' => 'form-check-label mr-2',
            'wrapper-close' => '</div>'
        ],
        'radio' => [
            'wrapper-open'             => '<div class="col-form-label"><div class="form-group form-check form-check-inline">',
            'element-wrapper-open'     => '',
            'class'                    => 'form-check-input',
            'label'                    => 'form-check-label mr-2',
            'element-wrapper-close'    => '',
            'wrapper-close'            => '</div></div>'
        ],
        'select' => [
            'class' => 'form-control',
        ],
        'textarea' => 'form-control',
    ],
    'button' => [
        /* 検索ボタン */
        'search' => [
            'class' => 'btn btn-info btn-search',
            'icon'  => 'fas fa-search'
        ],
        /* クリアボタン */
        'clear' => [
            'class' => 'btn btn-outline-dark btn-clear',
            'icon'  => 'fas fa-eraser'
        ],
        /* 戻るボタン */
        'back' => [
            'class' => 'btn btn-secondary btn-back',
            'icon'  => 'fas fa-caret-left'
        ],
        /* 新規作成ボタン */
        'create' => [
            'class' => 'btn btn-outline-dark btn-create',
            'icon'  => 'fas fa-plus'
        ],
        /* 登録ボタン */
        'store' => [
            'class' => 'btn btn-outline-info btn-store',
            'icon'  => 'fas fa-edit'
        ],
        /* 更新ボタン */
        'update' => [
            'class' => 'btn btn-outline-info btn-update',
            'icon'  => 'fas fa-edit'
        ],
        /* 削除ボタン */
        'delete' => [
            'class' => 'btn btn-outline-danger btn-delete',
            'icon'  => 'fas fa-trash-alt'
        ],
        /* 検索子画面ボタン */
        'disp-search' => [
            'class' => 'btn btn-outline-dark btn-ref',
            'icon'  => 'fas fa-list-ul'
        ],
        /* アップロードボタン */
        'upload' => [
            'class' => 'btn btn-info btn-upload',
            'icon'  => 'fas fa-upload'
        ],
        /* ダウンロードボタン */
        'download' => [
            'class' => 'btn btn-outline-dark btn-download',
            'icon'  => 'fas fa-download'
        ]
    ]
];