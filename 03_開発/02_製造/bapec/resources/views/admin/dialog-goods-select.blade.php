<?php
/**
 *  商品選択ダイアログblade
 */
use App\Enums\CodeDefine;
use App\Enums\StatusDefine;
$dialogId='dialog-goods-select';
$screenName="商品検索"
?>
@extends('admin.layouts.template.base-dialog')

@section($dialogId.'-content')
      {{-- メインCard --}}
      {{-- 検索条件 --}}
      <div class="condition-area border-bottom " >
      {{Form::open(['class'=>'searchcondition-area','id' =>'form-'.$dialogId.'-searchcondition'])}}
        @if ($functionId=='categories'){{-- カテゴリ設定商品選択 --}}
          <input type="hidden" name="search-category_code" value="" >
        @endif
        
        <div class="condition-area form-inline  d-flex justify-content-start " >
            <div class="form-group ">
              {{Form::label('search-code', '商品コード', ['class'=>'mr-2 '])}}
              {{Form::text('search-code', null, ['class'=>'form-control', 'size'=>'20', 'maxlength'=>'20'])}}
            </div>

            <div class="form-group ">
              {{Form::label('search-name', '商品名', ['class'=>'mr-2 '])}}
              {{Form::text('search-name', null, ['class'=>'form-control', 'size'=>'40', 'maxlength'=>'200'])}}
            </div>

            <div class="form-group ">
              {{Form::label('search-jan_code', 'JANコード', ['class'=>'mr-2 col-form-label'])}}
              {{Form::text('search-jan_code', null, ['class'=>'form-control', 'size'=>'20', 'maxlength'=>'20'])}}
            </div>
            <div class="form-group ">
              {{Form::label('search-maker_id', 'メーカー', ['class'=>'mr-2 col-form-label'])}}
              {{Form::select('search-maker_id', $selections['makers'], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}}
            </div>

            <div class="form-group ">
              {{Form::label('search-is_published', '公開状態', ['class'=>'mr-2 col-form-label'])}}
              <div class="form-check form-check-inline">
                {{Form::radio('search-is_published', '', true, ['class'=>'form-control', 'id'=>'search-is_published_all'])}}
                {{Form::label('search-is_published_all', 'すべて', ['class'=>'col-form-label'])}}
              </div>
              <div class="form-check form-check-inline">
                {{Form::radio('search-is_published', StatusDefine::KOKAI_ON, false, ['class'=>'form-control', 'id'=>'search-is_published_1'])}}
                {{Form::label('search-is_published_1', '公開', ['class'=>'col-form-label'])}}
              </div>
              <div class="form-check form-check-inline">
                {{Form::radio('search-is_published', StatusDefine::KOKAI_OFF, false, ['class'=>'form-control', 'id'=>'search-is_published_0'])}}
                {{Form::label('search-is_published_0', '非公開', ['class'=>'col-form-label'])}}
              </div>
            </div>

            <div class="form-group ">
              {{Form::label('search-sale_status', '販売ステータス', ['class'=>'mr-2 col-form-label'])}}
              {{Form::dropdown('search-payment_status'
            , SystemHelper::getCodes(CodeDefine::SALE_STATUS),null,[]
            , ['insert-empty' => true,'empty-label' => ''])}}
              {{-- {{Form::select('search-sale_status', $selections['saleStatuses'], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}} --}}
            </div>
        </div>
        <div class="btn-group-search " >
          {!! Form::searchButton(['id' => $dialogId.'-btn-search', 'class' => '']) !!}
          {!! Form::clearButton(['id' => $dialogId.'-btn-clear', 'class' => '']) !!}
        </div>
         {{Form::close()}}
      </div>
        
        {{-- 検索結果 --}}
        <div id="{{$dialogId}}-search-result" class="d-none">
          <code>表示件数、改ページ、並び替え、を行うと明細の選択<i class="far fa-check-square"></i>はクリアされます</code>
          <div class="row mt-1">
            <input type="hidden" id="{{$dialogId.'-display-count'}}" value="{{SystemHelper::getAppSettingValue('page.pagination.display-count.default')}}">
            {!! Form::grid($dialogId, [], false) !!}
          </div>

           <button id="{{$dialogId}}-btn-select" class="btn btn-outline-dark float-right">選　択</button>
        </div>

@endsection