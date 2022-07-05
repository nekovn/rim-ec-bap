<?php
use \App\Enums\CodeDefine;
/**
 *  佐川連携ファイル出力blade
 *
 */
    $noFooter=true;
    $screenName = '佐川連携ファイル出力';
    $functionId = 'sagawa-output';
    $attributes=['id'=>$functionId];
?>
@extends('admin.layouts.app')
@section('content')
<div class="card">
   <div class="card-header">
        <b>{{$screenName}}</b>
        <div class="card-header-actions">

        </div>
    </div>
    <div class="card-body">
        <div class="card mb-5">
            <div class="card-header">
                商品マスタ出力
            </div>
            <div class="card-body p-3">
                {{Form::downloadButton(['id'=>"{$functionId}-btn-download-goods", 'class' => 'dlbtn float-left ml-2', 'title' => 'CSVファイル出力'], "ダウンロード") }}
            </div>
            @if (session('download-goods-message'))
            <div id="download-goods-message" class="alert alert-danger m-4">
                {{ session('download-goods-message') }}
            </div>
            @endif
        </div>
        <div class="card mb-5">
            <div class="card-header">
                出荷指示出力
            </div>
            <div class="card-body p-3">
                {{Form::downloadButton(['id'=>"{$functionId}-btn-download-ships", 'class' => 'dlbtn float-left ml-2', 'title' => 'CSVファイル出力'], "ダウンロード") }}
            </div>
            @if (session('download-ships-message'))
            <div id="download-ships-message" class="alert alert-danger m-4">
                {{ session('download-ships-message') }}
            </div>
            @endif
        </div>
    </div>
</div>
<input id="{{$functionId}}-btn-download-exec" type="hidden">
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/sagawa-output.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/sagawa.output.page.js')}}" defer></script>
@endpush
