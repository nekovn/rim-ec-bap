<?php
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
/**
 *  受注一覧blade
 */
$screenName = '受注一覧';
$functionId = 'order-list';
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

    <div class="condition-area border-bottom " >
    {{Form::open(['class'=>'searchcondition-area p-2 condition-area','id' =>'form-'.$functionId.'-searchcondition'
    ,'data-is-back' => $isBack??false])}}
        <div class="condition-area form-inline  d-flex justify-content-start " >
            <div class="form-group ">
                {{Form::label('search-id', '受注ID', ['class'=>'mr-2'])}}
                {{Form::text('search-id', null, ['class'=>'form-control' ,'size'=>'20'])}}
            </div>
            <div class="form-group ">
                <label class=" mr-2">受注日時</label>
                {!!Form::datePicker('search-ordered_at_from', null, ['class'=>''])!!}
                    ～
                {!!Form::datePicker('search-ordered_at_to', null, ['class'=>''])!!}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-customer_id', '顧客ID', ['class'=>'mr-2'])}}
                {{Form::text('search-customer_id', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>20])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-name', '顧客名', ['class'=>'mr-2'])}}
                {{Form::text('search-customer_name', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>60])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-email', 'メールアドレス', ['class'=>'mr-2'])}}
                {{Form::text('search-email', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>254])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-goods_code', '商品コード', ['class'=>'mr-2'])}}
                {{Form::text('search-goods_code', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>20])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-goods_name', '商品名', ['class'=>'mr-2'])}}
                {{Form::text('search-goods_name', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>60])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-payment_status', '決済ステータス', ['class'=>'mr-2'])}}
                {{Form::dropdown('search-payment_status'
                , SystemHelper::getCodes(CodeDefine::PAYMENT_STATUS),null,[]
                , ['insert-empty' => true,'empty-label' => ''])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-order_status', '受注ステータス', ['class'=>'mr-2'])}}
                {!!Form::checkboxes('search-order_status',  SystemHelper::getCodes(CodeDefine::ORDER_STATUS)
                ,[], [StatusDefine::UKETSUKE,StatusDefine::KESSAI_MACHI])!!}
            </div>
        </div>
        <div class="d-block text-right ml-auto">
            {!! Form::searchButton(['id' => $functionId.'-btn-search', 'class' => '']) !!}
            {!! Form::clearButton(['id' => $functionId.'-btn-clear', 'class' => 'ml-3']) !!}
        </div>
    {{Form::close()}}
    </div>

    {{-- 検索結果 --}}
    <div id="{{$functionId}}-search-result" class="d-none">
        <div class="row mt-1">
        {!! Form::grid($functionId) !!}
        </div>
    </div>

    </div>
</div>
@endsection
@push('app-style')
    <link href="{{mix('css/admin/page/order.list.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/order.list.page.js')}}" defer></script>
@endpush
@push('head-style')
  <link href="{{asset('vendor/ag-grid/css/ag-grid.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/ag-grid/css/ag-theme-alpine.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/animate/css/animate.min.css')}}" rel="stylesheet">
@endpush
@push('head-script')
<script src="{{asset('vendor/ag-grid/js/ag-grid-community.min.noStyle.js')}}" defer></script>
<script src="{{asset('vendor/ag-grid/i18n/locale.en.js')}}" defer></script>
<script src="{{asset('vendor/ag-grid/i18n/locale.ja.js')}}" defer></script>
@endpush
