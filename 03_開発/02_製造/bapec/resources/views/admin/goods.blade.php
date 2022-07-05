<?php
use App\Enums\StatusDefine;
use App\Enums\VerticalPackingTypeDefine;
/**
 * 商品マスタblade
 */
$screenName = '商品マスタ';
$functionId = 'goods';
?>
@extends('admin.layouts.template.simple-crud')

{{-- 検索部 --}}
@section('search-condition')
  <div class="row col-12">
    <div class="col-3 ">
      <div class="form-group form-inline">
        {{Form::label('search-code', '商品コード', ['class'=>'mr-2 col-form-label'])}}
        {{Form::text('search-code', null, ['class'=>'form-control', 'size'=>'20', 'maxlength'=>'20'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-name', '商品名', ['class'=>'mr-2 col-form-label'])}}
        {{Form::text('search-name', null, ['class'=>'form-control', 'size'=>'', 'maxlength'=>'200'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-jan_code', 'JANコード', ['class'=>'mr-2 col-form-label'])}}
        {{Form::text('search-jan_code', null, ['class'=>'form-control', 'size'=>'20', 'maxlength'=>'20'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-sale_status', '販売ステータス', ['class'=>'mr-2 col-form-label'])}}
        {{Form::select('search-sale_status', $selections['saleStatuses'], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}}
      </div>
    </div>
  </div>
  <div class="row col-12">
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-maker_id', 'メーカー', ['class'=>'mr-2 col-form-label'])}}
        {{Form::select('search-maker_id', $selections['makers'], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-class1_code', '大カテゴリ', ['class'=>'mr-2 col-form-label'])}}
        {{Form::select('search-class1_code', $selections['class1'], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
        {{Form::label('search-class2_code', '小カテゴリ', ['class'=>'mr-2 col-form-label'])}}
        {{Form::select('search-class2_code', [], null, ['class'=>'form-control', 'placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group form-inline">
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
    </div>
  </div>
@endsection

{{-- 商品編集部 --}}
@section('detail')
  <div class="row">
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('code', '商品コード', ['class'=>'required'])}}
        {{Form::textEx('code', null, [
          'maxlength'=>'20',
          'required',
          // 'data-parsley-remote'=>url('/api/admin/goods/codes/{value}'),
          'data-parsley-remote-options'=>'{"async":false}',
          // 'data-parsley-duplicatecode',
          'data-parsley-trigger'=> 'blur',
          'data-parsley-remote-message'=>'入力された商品コードは既に存在します。',
          'data-parsley-excluded'=>"true",
          // 'data-parsley-debounce'=>"1000"
        ])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('jan_code', 'JANコード')}}
        {{Form::textEx('jan_code', null, ['maxlength'=>'13', 'readonly'])}}
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('is_published', '公開状態', ['class'=>'required'])}}
        {!!Form::radios('is_published',
            [['value'=> StatusDefine::KOKAI_ON, 'label'=>'公開'], ['value'=> StatusDefine::KOKAI_OFF, 'label'=>'非公開']],
            ['required'],
            '1'
        )!!}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('sale_status', '販売ステータス', ['class'=>'required'])}}
        {{Form::selectEx('sale_status', $selections['saleStatuses'], null, ['required', 'placeholder'=>'未選択'])}}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-6">
      <div class="form-group">
        {{Form::labelEx('name', '商品名', ['class'=>'required'])}}
        {{Form::textEx('name', null, ['maxlength'=>'200', 'required'])}}
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('sales_start_datetime', '商品展示開始日時')}}
        {!!Form::dateTimePicker('sales_start_datetime', null, [
          'class'=>'sales_start_datetime',
          'data-parsley-datetimerange'=>'sales'
        ])!!}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('sales_end_datetime', '商品展示終了日時')}}
        {!!Form::dateTimePicker('sales_end_datetime', null, [
          'class'=>'sales_end_datetime',
          'data-parsley-datetimerange'=>'sales'
        ])!!}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('volume', '規格')}}
        {{Form::textEx('volume', null, ['maxlength'=>'100'])}}
      </div>
    </div>
    <div class="col-3">
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      <div class="form-group">
        {{Form::labelEx('stock_management_type', '在庫管理区分', ['class'=>'required'])}}
        {!!Form::radios('stock_management_type', $selections['stockManagementTypes'], ['required'], 1)!!}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('maker_id', 'メーカー')}}
        {{Form::selectEx('maker_id', $selections['makers'], null, ['placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('supplier_id', '仕入先')}}
        {{Form::selectEx('supplier_id', $selections['suppliers'], null, ['placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      <div class="form-group">
        {{Form::labelEx('temperature_control_type', '温度管理区分', ['class'=>'required'])}}
        {!!Form::radios('temperature_control_type', $selections['temperatureControlTypes'], ['required'], 1)!!}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('unit_price', '単価', ['class'=>'required'])}}
        {{Form::numberEx('unit_price', null, ['data-parsley-lte'=>'9999999999.99', 'required', 'pattern'=>'[\d.]*'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('tax_type', '消費税区分', ['class'=>'required'])}}
        {{Form::selectEx('tax_type', $selections['taxTypes'], null, ['required', 'placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('tax_kind', '消費税種類', ['class'=>'required'])}}
        {{Form::selectEx('tax_kind', $selections['taxKinds'], null, ['required', 'placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-1">
      <div class="form-group">
        {{Form::labelEx('limited_unit_price', '限定単価')}}
        {{Form::numberEx('limited_unit_price', null, ['data-parsley-lte'=>'9999999999.99', 'pattern'=>'[\d.]*'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('limited_start_datetime', '限定期間開始日時')}}
        {!!Form::dateTimePicker('limited_start_datetime', null, [
          'class'=>'limited_start_datetime',
          'data-parsley-datetimerange'=>'limited'
        ])!!}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('limited_end_datetime', '限定期間終了日時')}}
        {!!Form::dateTimePicker('limited_end_datetime', null, [
          'class'=>'limited_end_datetime',
          'data-parsley-datetimerange'=>'limited'
        ])!!}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('purchase_unit_price', '仕入単価')}}
        {{Form::numberEx('purchase_unit_price', null, ['data-parsley-lte'=>'9999999999.99', 'pattern'=>'[\d.]*'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('purchase_tax_type', '仕入消費税区分')}}
        {{Form::selectEx('purchase_tax_type', $selections['taxTypes'], null, ['placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('purchase_tax_kind', '仕入消費税種類')}}
        {{Form::selectEx('purchase_tax_kind', $selections['taxKinds'], null, ['placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-3">
      <div class="form-group">
        {{Form::labelEx('estimated_delivery_date', '納期目安')}}
        {{Form::selectEx('estimated_delivery_date', $selections['estimatedDeliveryDates'], null, ['placeholder'=>'未選択'])}}
      </div>
    </div>
    <div class="col-2">
      <div class="form-group">
        {{Form::labelEx('delivery_leadtime', '出荷リードタイム', ['class'=>'required'])}}
        {{Form::numberEx('delivery_leadtime', '0', ['required', 'data-parsley-lte'=>'4294967295', 'pattern'=>'[\d]*'])}}
      </div>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-6">
      <div class="row">
        <div class="col">
          {{Form::labelEx('description', '商品説明')}}
        </div>
      </div>
      <div class="row">
        <div class="col">
          {{Form::textarea('description', null, ['id'=>'description', 'class'=>'form-control', 'maxlength'=>'4000'])}}
        </div>
      </div>
    </div>
    <div class="col-1"></div>
    <div class="col-5">
      <div class="row">
        <div class="col">
          {{Form::labelEx('is_vertical_packing', '縦梱包区分', ['class'=>'required'])}}
        </div>
      </div>
      <div class="row">
        <div class="col">
          {!!Form::radios('is_vertical_packing',
              [['value'=> VerticalPackingTypeDefine::NEED, 'label'=>'要'],['value'=> VerticalPackingTypeDefine::UN_NEEDED, 'label'=>'不要']],
              ['required'],
              0
          )!!}
        </div>
      </div>
      <div class="row">
        <div class="col">
          {{Form::labelEx('expiration_date_note', '賞味期限表記')}}
        </div>
      </div>
      <div class="row mb-2">
        <div class="col">
          {{Form::textarea('expiration_date_note', null, ['class'=>'form-control', 'rows'=>'2', 'maxlength'=>'200'])}}
        </div>
      </div>
      <div class="row">
        <div class="col">
          {{Form::labelEx('upload_image', '商品代表画像', ['class'=>'required'])}}
          <div class="row position-relative">
            <div class="image_preview">
              <img src="" id="image_preview" alt="プレビュー">
            </div>
            <div class="col-6 ml-3">
              {{Form::file('upload_image', [
                'id'=>'upload_image',
                'class'=>'custom-file-input form-control',
                'type'=>'file'
              ])}}
              <label class="custom-file-label" for="upload_image" data-browse="画像を選択"></label>
              {{Form::textEx('image', null, ['id'=>'image', 'readonly', 'required', 'style'=>'display:none;'])}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('app-style')
  <link href="{{asset('vendor/Trumbowyg/ui/trumbowyg.min.css')}}" rel="stylesheet">
  <link href="{{asset('vendor/Trumbowyg/plugins/colors/ui/trumbowyg.colors.min.css')}}" rel="stylesheet" >
  <link href="{{asset('vendor/Trumbowyg/plugins/table/ui/trumbowyg.table.min.css')}}" rel="stylesheet" >
  <link href="{{mix('css/admin/page/goods.page.css')}}" rel="stylesheet">
@endpush
@push('app-script')
  <script src="{{asset('vendor/Trumbowyg/trumbowyg.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/langs/ja.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/plugins/colors/trumbowyg.colors.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/plugins/table/trumbowyg.table.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/plugins/pasteembed/trumbowyg.pasteembed.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/plugins/pasteimage/trumbowyg.pasteimage.min.js')}}" defer></script>
  <script src="{{asset('vendor/Trumbowyg/plugins/noembed/trumbowyg.noembed.min.js')}}" defer></script>
  <script src="{{mix('js/admin/page/goods.page.js')}}" defer></script>
  <script>
    window.isBack = {{$isBack}};
    window.class1 = @json($selections['class1']);
    window.class2 = @json($selections['class2']);
  </script>
@endpush
