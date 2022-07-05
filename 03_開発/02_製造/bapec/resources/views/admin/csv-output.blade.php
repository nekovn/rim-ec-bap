<?php
use \App\Enums\CodeDefine;
/**
 *  CSV出力blade
 *
 */
    $noFooter=true;
    $screenName = 'CSV出力';
    $functionId = 'csv_output';
    $attributes=['id'=>$functionId];
?>
@extends('admin.layouts.app')
@section('content')
<div class="card ">
   <div class="card-header">
        <b>{{$screenName}}</b>
        <div class="card-header-actions">

        </div>
    </div>
    <div class="card-body pt-0">
        {{Form::hidden("")}}
    {{Form::open(['class'=>'', 'id'=>"form-{$functionId}" ,'files' => true])}}
        <div class="row">
            <div class="col-12 m-3">
                <div class="form-group row mb-0">
                    <div class="col-8">
                        <div class="row input-group-output-data">
                            {{Form::labelEx("$attributes[id]_joken_file", '出力データ',['class'=>'mx-1 col-form-label required'])}}
                            {{Form::dropdown("$attributes[id]_joken_file", $fileNameList, null, ['class'=>'col-7 ml-2'], ['insert-empty'=>true])}}

                            {{Form::dispSearchButton(['id'=>"$attributes[id]-btn-disp",'class'=>"ml-2",'title'=>'条件ファイルの詳細を表示'],"表示")}}
                            {{Form::clearButton(['id'=>"$attributes[id]-btn-clear", 'class' => 'ml-2','style'=>'width:100px']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="{{$functionId}}-joken-detail-area" class="card mt1 " style="display: none;">
            {{--  <div class="card-header">
            </div>  --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        {{-- 条件入力 --}}
                        <div class="row mb-2">
                            <div class="form-inline">
                                {!!Form::Label("$attributes[id]_joken", '条件', ['class'=>'font-weight-bold float-left ml-1 mr-4'])!!}
                            </div>
                        </div>
                        {{-- 条件一覧 --}}
                        <div id="{{$functionId}}-joken-table" class="">
                            <div class="row mt-1">
                                {{-- <div id="total-count" class="col-12 text-right"></div> --}}
                                <div id="{{$attributes['id']}}-grid" class="ag-theme-alpine grid mb-2"></div>
                                {{-- {!! Form::grid($attributes['id'],[],false) !!} --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div id="{{$functionId}}-contents-table" class="card border-warning mt1">
                            <div class="card-header">
                                <div class="font-weight-bold">出力内容説明</div>
                            </div>
                            <div class="card-body">
                                <pre id="{{$functionId}}_contents_detail"></pre>
                            </div>
                        </div>
                    </div>

                </div>
    {{Form::close()}}

    <div class="btn-footer py-1 mt-2">
        {{Form::downloadButton(['id'=>"$attributes[id]-btn-download", 'class' => 'dlbtn float-left ml-2', 'title' => 'CSVファイル出力'], "出力") }}

        @if ( Route::has('csv-output.general_list'))
            {{Form::dispSearchButton(['id'=>"$attributes[id]-btn-generallist",'class'=>"dlbtn float-left ml-2",'title'=>'表示件数制限あり'],"一覧表示")}}
        @endif
        <input id="{{$functionId}}-btn-download-exec" type="hidden">
    </div>
    {{-- 画面出力用 --}}
    @if ( Route::has('csv-output.general_list'))
    {{Form::open(['class'=>'', 'id'=>"form-{$functionId}-generallist",'url'=>'/admin/general_list' , 'target'=> 'new_window'])}}
        <input name="generallist-param" type="hidden">
    {{Form::close()}}
    @endif
    {{-- 画面出力用 --}}
            </div>
        </div>
@endsection
@push('app-style')
    <link href="{{asset('vendor/ag-grid/css/ag-grid.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/ag-grid/css/ag-theme-alpine.min.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/animate/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{mix('css/admin/page/csv-output.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{asset('vendor/ag-grid/js/ag-grid-community.min.noStyle.js')}}" defer></script>
    <script src="{{asset('vendor/ag-grid/i18n/locale.en.js')}}" defer></script>
    <script src="{{asset('vendor/ag-grid/i18n/locale.ja.js')}}" defer></script>
    {{-- JSへ渡す情報 --}}
    <script>
        window.updateAuth = <?php echo $updAuth?>;                    // 更新権限
    </script>
    <script src="{{mix('js/admin/page/csv.output.page.js')}}" defer></script>
@endpush
