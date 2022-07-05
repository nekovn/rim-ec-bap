<?php
use \App\Enums\CodeDefine;
/**
 *  汎用リストblade
 * 
 */
    $noFooter=true;
    $screenName = 'CSV出力一覧表示';
    $functionId = 'general_list';
?>
@extends('admin.layouts.template.base-card')

@section('main-area')
    <div class="pl-1 pt-1 pr-1">
        <div id="{{ $functionId}}-search-result">
            <div class="row mt-1 ml-1 mr-1">
                <h4 id="list-title" class="col-6 mb-1"></h4>
                <div id="total-count" class="col-6 text-right"></div>
                {!! Form::grid($functionId) !!}
            </div>
        </div>
    </div>
    <div class="btn-footer py-1 ">
        {!! Form::downloadButton(['id'=>"$functionId-btn-download", 'class' => 'dlbtn float-left ml-2 d-none', 'title' => 'CSVファイル出力'], "CSVダウンロード") !!}
    </div>
@endsection

@push('app-style')
    <link href="{{asset('vendor/ag-grid/css/ag-grid.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/ag-grid/css/ag-theme-alpine.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/animate/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{mix('css/admin/page/general.list.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{asset('vendor/ag-grid/js/ag-grid-community.min.noStyle.js')}}" defer></script>
    <script src="{{asset('vendor/ag-grid/i18n/locale.en.js')}}" defer></script>
    <script src="{{asset('vendor/ag-grid/i18n/locale.ja.js')}}" defer></script>

    <script src="{{mix('js/admin/page/general.list.page.js')}}" defer></script>
@endpush
