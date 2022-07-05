<?php
/**
 *  商品マスタblade
 */
  $screenName = '商品マスタ';
  $functionId = 'products';
?>
@extends('admin.layouts.template.simple-crud')

{{-- 検索部 --}}
@section('search-condition')
<div class="col-12 form-inline">
  <div class="form-group col-4">
    {{Form::label('search-code', '商品コード', ['class'=>'col-3'])}}
    <div class="col-9">
      {{Form::text('search-code', null, ['class'=>'form-control','size'=>'50'])}}
    </div>
  </div>
  <div class="form-group col-4">
    {{Form::label('search-name', '商品名', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::text('search-name', null, ['class'=>'form-control','size'=>'50'])}}
      </div>
  </div>
  <div class="form-group col-4">
    {{Form::label('search-jan_code', 'JANコード', ['class'=>'col-3'])}}
      <div class="col-9">
        {{Form::text('search-jan_code', null, ['class'=>'form-control','size'=>'50'])}}
      </div>
  </div>
</div>
<div class="col-12 form-inline mt-1">
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
      {{Form::text('search-brand_name', null, ['class'=>'form-control','size'=>'50'])}}
    </div>
  </div>
</div>
<div class="col-12 form-inline mt-1">
  <div class="form-group col-4">
    {{Form::label('search-class_div', '定番区分', ['class'=>'col-3'])}}
    <input id="search-kind-0" class="form-check-input float-left" checked="checked" name="search-class_div" type="radio" value="0" data-parsley-multiple="search-class_div">
    <label for="search-kind-0" class="form-check-label float-left">全て</label>
    <input id="search-kind-1" class="form-check-input float-left" name="search-class_div" type="radio" value="1" data-parsley-multiple="search-class_div">
    <label for="search-kind-1" class="form-check-label float-left">定番品</label>
    <input id="search-kind-2" class="form-check-input float-left" name="search-class_div" type="radio" value="2" data-parsley-multiple="search-class_div">
    <label for="search-kind-2" class="form-check-label float-left">季節品商品</label>
    <input id="search-kind-3" class="form-check-input float-left" name="search-class_div" type="radio" value="3" data-parsley-multiple="search-class_div">
    <label for="search-kind-3" class="form-check-label float-left">取り寄せ品</label>
  </div>
  <div class="form-group col-4">
    {{Form::label('search-update', '画面更新日付', ['class'=>'col-3'])}}
    {!!Form::datePicker('search-update_from', null, ['id'=>'attr-id-datepicker', 'class'=>'attr-class'])!!}
    {{Form::label('search-update', '　～　', ['class'=>'col-1 text-center'])}}
    {!!Form::datePicker('search-update_to', null, ['id'=>'attr-id-datepicker', 'class'=>'attr-class'])!!}
  </div>
  <div class="form-group col-4">
    {{Form::checkbox('search-unedited', 1, null, ['id' => 'search_unedited'])}}
    {{Form::labelEx('search_unedited', '取込後、未編集の商品のみ表示', ['class' => 'check_css'])}}
  </div>
</div>
@endsection

{{-- ユーザー編集部分 --}}
@section('detail')
  <div class="row form-group">
    <div class="col-6">
      {{Form::label('code', '商品コード', ['class'=>''])}}
      {{Form::textEx('code', null, ['class'=>'form-control'])}}
    </div>
    <div class="col-6">
      {{Form::label('name', '商品名', ['class'=>''])}}
      {{Form::textEx('name', null, ['class'=>'form-control', 'disabled'=>'disabled'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::label('name_kana', '商品名カナ', ['class'=>'float-left'])}}
      {{Form::textEx('name_kana', null, ['class'=>'form-control'])}}
    </div>
    <div class="col-6">
      {{Form::label('brand_name', 'メーカー名')}}
      {{Form::textEx('brand_name', null, ['class'=>'form-control'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      <input type="hidden" id="category_code" name="category_code" value="">
      {{Form::label('disp_category', 'カテゴリ')}}
      {{Form::textEx('disp_category', null, ['class'=>'form-control'])}}
    </div>
    <div class="col-6">
      <input type="hidden" id="case_qty" name="case_qty" value="">
      <input type="hidden" id="inbox_qty" name="inbox_qty" value="">
      <input type="hidden" id="fraction_qty" name="fraction_qty" value="">
      {{Form::label('qty_disp', '入り数')}}
      {{Form::textEx('qty_disp', '', ['class'=>'form-control'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      <input type="hidden" id="order_lot_type" name="order_lot_type" value="">
      <input type="hidden" id="order_lot" name="order_lot" value="">
      {{Form::label('order_lot_disp', '受注ロット')}}
      {{Form::textEx('order_lot_disp', '', ['class'=>'form-control'])}}
    </div>
    <div class="col-6">
      {{Form::label('class_div', '定番区分')}}
      {!!Form::radios('class_div', [['value'=>'1', 'label'=>'定番品'], ['value'=>'2', 'label'=>'季節品商品'], ['value'=>'3', 'label'=>'取り寄せ品']], ['id'=>'kind', 'class'=>'form-control'], '')!!}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::label('upload_image', '商品画像')}}
        <div class="row position-relative">
          <div class="image_preview">
            <img src="" id="image_preview">
          </div>
          <div class="col-6 ml-3">
            {{Form::file('upload_image', ['id'=>'upload_image', 'class'=>'custom-file-input required', 'type'=>'file'])}}
            <label class="custom-file-label" for="upload_image" data-browse="画像を選択"></label>
          </div>
          <input type="hidden" id="image" name="image" value="">
        </div>
    </div>
    <div class="col-6">
      {{Form::label('description', '商品説明')}}
      {{Form::textarea('description', null, ['class'=>'form-control', 'maxlength'=>'2000', 'rows'=>'10'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::label('size', '寸法/容量')}}
      {{Form::textEx('size', null, ['class'=>'form-control', 'maxlength'=>'100'])}}
    </div>
    <div class="col-6">
      {{Form::label('homepage_url', '商品HP')}}
      {{Form::textEx('homepage_url', null, ['class'=>'form-control', 'maxlength'=>'255'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      {{Form::label('video', '商品動画')}}
      {{Form::textEx('video', null, ['class'=>'form-control', 'maxlength'=>'255'])}}
    </div>
    <div class="col-6">
      <input type="hidden" id="unedited" name="unedited" value="">
      {{Form::label('un_edited_dsp', '編集状態')}}
      {{Form::textEx('un_edited_dsp', '', ['class'=>'form-control'])}}
    </div>
  </div>
  <div class="row form-group">
    <div class="col-6">
      <input type="hidden" id="edited_at" name="edited_at" value="">
      {{Form::label('edited_at_dsp', '更新日時')}}
      {{Form::textEx('edited_at_dsp', null, ['class'=>'form-control'])}}
    </div>
    <div class="col-6">
      {{Form::label('import_at', '取り込み日時')}}
      {{Form::textEx('import_at', null, ['class'=>'form-control'])}}
    </div>
  </div>
</div>
@endsection

@push('app-style')
  <script>
    window.class1 = @json($selections['class1']);
    window.class2 = @json($selections['class2']);
    window.from_dashboard = @json($from_dashboard);
  </script>
    <link href="{{mix('css/admin/page/products.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
    <script src="{{mix('js/admin/page/products.page.js')}}" defer></script>
@endpush