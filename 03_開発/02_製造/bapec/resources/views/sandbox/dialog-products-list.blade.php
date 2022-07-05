<?php
use App\Enums\ClassDivDefine;

/**
 *  商品マスタblade
 */
$dialogId = 'dialog_products';
$functionId = $dialogId;
?>
@extends('admin.layouts.template.base-dialog')

{{-- 検索部 --}}
@section('dialog_products-content')
<div class="card-body">
  {{Form::open(['class'=>'searchcondition-area','id' =>'form-'.$functionId.'-searchcondition'])}}
  <div class="col-12 form-inline">
    <div class="form-group col-4">
      {{Form::label('search-code', '商品コード', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::text('search-code', null, ['class'=>'form-control'])}}
      </div>
    </div>
    <div class="form-group col-4">
      {{Form::label('search-name', '商品名', ['class'=>'col-3'])}}
        <div class="col-9">
          {{Form::text('search-name', null, ['class'=>'form-control'])}}
        </div>
    </div>
    <div class="form-group col-4">
      {{Form::label('search-jan_code', 'JANコード', ['class'=>'col-3'])}}
        <div class="col-9">
          {{Form::text('search-jan_code', null, ['class'=>'form-control'])}}
        </div>
    </div>
  </div>
  <div class="col-12 form-inline">
    <div class="form-group col-4">
      {{Form::label('search-class1_code', '小カテゴリ', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::dropdown('search-class1_code', $selections['class1'], '', ['class'=>'form-control'], ['insert-empty'=>true])}}
      </div>
    </div>
    <div class="form-group col-4">
      {{Form::label('search-class2_code', '細カテゴリ', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::dropdown('search-class2_code', [], '', ['class'=>'form-control'], ['insert-empty'=>true])}}
      </div>
    </div>
    <div class="form-group col-4">
      {{Form::label('search-brand_name', 'メーカー', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::text('search-brand_name', null, ['class'=>'form-control'])}}
      </div>
    </div>
  </div>
  <div class="col-12 form-inline">
    <div class="form-group col-5">
      {{Form::label('search-class_div', '定番区分', ['class'=>'col-3'])}}
      <input id="search-kind-0" class="form-check-input float-left" checked="checked" name="search-class_div" type="radio" value="0" data-parsley-multiple="search-class_div">
      <label for="search-kind-0" class="form-check-label float-left">全て</label>
      <input id="search-kind-1" class="form-check-input float-left" name="search-class_div" type="radio" value={{ClassDivDefine::STANDARD}} data-parsley-multiple="search-class_div">
      <label for="search-kind-1" class="form-check-label float-left">定番品</label>
      <input id="search-kind-2" class="form-check-input float-left" name="search-class_div" type="radio" value={{ClassDivDefine::SEASONAL}} data-parsley-multiple="search-class_div">
      <label for="search-kind-2" class="form-check-label float-left">季節品商品</label>
      <input id="search-kind-3" class="form-check-input float-left" name="search-class_div" type="radio" value={{ClassDivDefine::BACK_ORDER}} data-parsley-multiple="search-class_div">
      <label for="search-kind-3" class="form-check-label float-left">取り寄せ品</label>
    </div>
    <div class="d-block text-right ml-auto">
        {!! Form::searchButton(['id' => $functionId.'-btn-search', 'class' => '']) !!}
        {!! Form::clearButton(['id' => $functionId.'-btn-clear', 'class' => 'ml-3']) !!}
    </div>
  </div>

  {{-- 検索結果 --}}
  <div id="{{$functionId}}-search-result">
    {{-- 表示件数 limitを設定--}}
    @if (SystemHelper::getAppSettingValue('page.pagination'))
        <label class="col-1 col-form-label text-right">表示件数</label>
        {!! Form::displayCountSelect($functionId, ['class'=>"col-1 form-control"]) !!}
    @endif
    <div class="row mt-1">
    {!! Form::grid($functionId) !!}
    </div>
  </div>
  {{Form::close()}}
</div>
@endsection

@push('app-style')
  <script>
    window.class2 = @json($selections['class2']);
  </script>
  <link href="{{mix('css/admin/page/products.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
  <script src="{{mix('js/admin/page/forms/products/products.dialog.form.js')}}" defer></script>
@endpush