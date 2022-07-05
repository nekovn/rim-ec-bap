<?php

use App\Helpers\Util\SystemHelper;
/**
 * Grid向けフォームヘルパー定義
 */
Form::macro('grid', function ($name, $attributes = [], $isDispCountSelect = true) {

    $html = '<div class="col-12 d-flex justify-content-end" id="'.$name.'-grid-count-area">';
    /*-- 表示件数 limitを設定--*/
    if (SystemHelper::getAppSettingValue('page.pagination')) {
        if ($isDispCountSelect) {
            $html = '<div class="col-12 d-flex justify-content-between" id="' . $name . '-grid-count-area">';
            $html .= '<div class="tbl_length" >';
            $html .= '<label class=""><span class="mr-1">表示件数</span> ';
            $html .= Form::displayCountSelect($name, ['class'=>"form-control-sm d-inline-block"]);
            $html .= '</label>';
            $html .= '</div> ';
        }
    }
    $html .= '<div class="'.$name.'-grid-total-count my-auto font-sm"></div>';
    $html .= '</div >';

    $html .= '<div id="'.$name. '-grid" class="ag-theme-alpine grid mb-2" 
        data-oitem="' . old('page.sortItem') . '" data-oorder="' . old('page.sortOrder') . '"  data-opage="' . old('page.page') . '"></div>';
    $html .= '<ul id="'.$name.'-grid-pagination" class="pagination mt-0 mb-1"></ul>';
    return $html;
});
