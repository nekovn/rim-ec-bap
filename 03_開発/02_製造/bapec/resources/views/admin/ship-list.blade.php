<?php
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
/**
 *  出荷一覧blade
 */
$screenName = '出荷一覧';
$functionId = 'ship-list';
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
                <label class=" mr-2">出荷指示日</label>
                {!!Form::datePicker('search-ship_direct_date_from', null, ['class'=>''])!!}
                    ～
                {!!Form::datePicker('search-ship_direct_date_to', null, ['class'=>''])!!}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-slip_no', '問合せ番号', ['class'=>'mr-2'])}}
                {{Form::text('search-slip_no', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>20])}}
            </div>
            <div class="form-group ">
                {{Form::labelEx('search-client_name', '依頼主名', ['class'=>'mr-2'])}}
                {{Form::text('search-client_name', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>60])}}
            </div>
            <!-- <div class="form-group ">
                {{Form::labelEx('search-goods_name', '商品名', ['class'=>'mr-2'])}}
                {{Form::text('search-goods_name', null, ['class'=>'form-control' ,'size'=>'20','maxlength'=>60])}}
            </div> -->
            <div class="form-group ">
                {{Form::labelEx('search-ship_status', '出荷ステータス', ['class'=>'mr-2'])}}
                {!!Form::checkboxes('search-ship_status',  SystemHelper::getCodes(CodeDefine::SHIP_STATUS)
                ,[], [])!!}

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
    <script src="{{mix('js/admin/page/ship.list.page.js')}}" defer></script>
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
